<?php

namespace Waterhole\StopForumSpam;

use Waterhole\Extend;
use Waterhole\StopForumSpam\Fields\StopForumSpam;

class StopForumSpamServiceProvider extends Extend\ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stopforumspam.php', 'waterhole.stopforumspam');

        $this->extend(function (Extend\Forms\RegistrationForm $form) {
            $form->add(StopForumSpam::class, 'stopforumspam');
        });
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'waterhole-stopforumspam');

        $this->publishes(
            [
                __DIR__ . '/../config/stopforumspam.php' => config_path(
                    'waterhole/stopforumspam.php',
                ),
            ],
            'waterhole-stopforumspam-config',
        );
    }
}
