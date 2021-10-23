Gindow Cloud PHP SDK

## Requirement
1. PHP >= 7.2
2. **[Composer](https://getcomposer.org/)**

## Installation

```shell
$ composer require "gindowcloud/gindowcloud"

$ php artisan vendor:publish --provider="GindowCloud\Provider"
```

## Config .env
- GINDOW_URL =
- GINDOW_CLIENT_ID =
- GINDOW_CLIENT_SECRET =


## Usage
``` php
use GindowCloud\Facades\Settings;
use GindowCloud\Facades\Sms;
use GindowCloud\Facades\Captcha;

// Settings
Settings::set('site_name', 'My Site');
Settings::get('site_name', 'Default');
Settings::all();

// Sms
Sms::send('13966668888', 'Hello World');

// Authentication
Captcha::send('13966668888');
Captcha::check('13966668888', '1234');
```