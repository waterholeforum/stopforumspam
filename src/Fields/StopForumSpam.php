<?php

namespace Waterhole\StopForumSpam\Fields;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;
use Throwable;
use Waterhole\Auth\SsoPayload;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class StopForumSpam extends Field
{
    public function __construct(public ?User $model, public ?SsoPayload $payload = null)
    {
    }

    public function render(): string
    {
        return '';
    }

    public function validating(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $data = $validator->getData();
            $checks = config('waterhole.stopforumspam.checks', []);

            $params = collect([
                'ip' => request()->ip(),
                'username' => $this->payload->user->name ?? $data['name'],
                'email' => $this->payload->user->email ?? $data['email'],
            ])->filter(fn($v, $k) => !empty($checks[$k]));

            if ($params->isEmpty()) {
                return;
            }

            try {
                $json = Http::timeout(config('waterhole.stopforumspam.timeout', 3))
                    ->asForm()
                    ->post(config('waterhole.stopforumspam.endpoint'), [...$params, 'json' => true])
                    ->json();
            } catch (Throwable) {
                return;
            }

            if (empty($json['success'])) {
                return;
            }

            $frequency = 0;
            $confidence = 0.0;
            $blacklisted = false;

            foreach (['ip', 'email', 'username'] as $key) {
                if (!isset($json[$key])) {
                    continue;
                }

                if (!empty($json[$key]['blacklisted'])) {
                    $blacklisted = true;
                }

                $frequency += $json[$key]['frequency'] ?? 0;
                $confidence += $json[$key]['confidence'] ?? 0;
            }

            $minFrequency = (int) config('waterhole.stopforumspam.min_frequency', 2);
            $minConfidence = (float) config('waterhole.stopforumspam.min_confidence', 50);

            $tooFrequent = $minFrequency > 0 && $frequency >= $minFrequency;
            $tooConfident = $minConfidence > 0 && $confidence >= $minConfidence;

            if ($blacklisted || $tooFrequent || $tooConfident) {
                $validator->errors()->add('spam', __('waterhole-stopforumspam::messages.blocked'));
            }
        });
    }
}
