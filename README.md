# Waterhole StopForumSpam

**StopForumSpam checks for Waterhole registration.**

When enabled, registration is checked against StopForumSpam using the configured inputs (IP, email, and username). If a match is found, the registration is rejected with a validation error.

## Installation

```
composer require waterhole/stopforumspam
```

## Configuration

Publish the config:

```
php artisan vendor:publish --tag=waterhole-stopforumspam-config
```

This extension runs automatically on registration once installed.
