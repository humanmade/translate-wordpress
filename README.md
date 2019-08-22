<!-- logo -->
<img src="https://cdn.weglot.com/logo/logo-hor.png" height="40" />

# Translate WP website – Weglot Translate

[![Weglot Slack][slack-image]][slack-url]
[![Build Status][travis-image]][travis-url]
[![CodeFactor][codefactor-image]][codefactor-url]


## Requirements

You can download plugin on [WordPress.org](https://wordpress.org/plugins/weglot)

- PHP version 5.4 and later
- Weglot API Key, starting at [free level](https://dashboard.weglot.com/register)


## Developers

To install the development environment:
- `git clone https://github.com/weglot/translate-wordpress.git weglot`
- `cd weglot && composer install && npm install`


[travis-image]: https://api.travis-ci.com/weglot/translate-wordpress.svg?branch=master
[travis-url]: https://travis-ci.com/weglot/translate-wordpress

[slack-image]: https://weglot-community.now.sh/badge.svg
[slack-url]: https://weglot-community.now.sh/

[codefactor-image]: https://www.codefactor.io/repository/github/weglot/translate-wordpress/badge/dev
[codefactor-url]: https://www.codefactor.io/repository/github/weglot/translate-wordpress/overview/dev


### Execute tests

- `./bin/install-wp-tests.sh "wptest" "root" "" "127.0.0.1" "4.9.5"`
- `./vendor/bin/phpunit`

## Before each release : test the following

### Switcher language

_Functioning and links_

- Default button 
- In menu
- Widget
- Shortcode

### Options

- Exclude blocks
- Exclude URLs
- Auto switch

### Admin settings 

- Change country flag
- Select language
- Check success / error on api key

### Links

- Internal links must have the language prefix
- External links not translating

### WooCommerce

- Checkout
- Redirect Thank you page
- Email transactional

### Performance
- Checker la différence page originale / page de destination