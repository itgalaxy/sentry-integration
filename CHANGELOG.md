# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 2.2.9 - 2018-11-16

* Chore: update `sentry/sentry` to `1.10.0` version.

## 2.2.8 - 2018-08-27

* Chore: regenerate autoload.

## 2.2.7 - 2018-08-27

* Chore: update `raven-js` to `3.27.0` version.

## 2.2.6 - 2018-08-27

* Chore: update `sentry/sentry` to `1.9.2` version.

## 2.2.5 - 2018-08-09

* Chore: update `raven-js` to `3.26.4` version.

## 2.2.4 - 2018-06-25

* Chore: update `raven-js` to `3.26.3` version.
* Chore: update `sentry/sentry` to `1.9.1` version.

## 2.2.3 - 2018-05-04

* Chore: update `sentry/sentry` to `1.9.0` version.

## 2.2.2 - 2018-04-25

* Fixed: remove EOL in `inline` raven script. 

## 2.2.1 - 2018-04-25

* Fixed: regression around import scripts for `SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE`. 

## 2.2.0 - 2018-04-25

* Fixed: don't include source map in inlined `raven-js` script. 
* Fixed: optimize `composer` autoloader. 
* Feature: add `SENTRY_INTEGRATION_PUBLIC_DSN_ENQUEUE_MODE` constant to control how register and enqueue `sentry` JavaScript file.
* Chore: update `raven-js` to `3.24.2` version.
* Chore: implement `Makefile.js` and `npm run build` script for easy release project.

## 2.1.9 - 2018-04-11

* Chore: update `raven-js` to `3.24.1` version.

## 2.1.8 - 2018-03-28

* Chore: update `raven-js` to `3.24.0` version.

## 2.1.7 - 2018-03-21

* Chore: update `sentry/sentry` to `1.8.4` version.

## 2.1.6 - 2018-03-02

* Chore: update `raven-js` to `3.23.3` version.

## 2.1.5 - 2018-03-02

* Chore: update `raven-js` to `3.23.1` version.

## 2.1.4 - 2018-03-02

* Chore: update `raven-js` to `3.22.4` version.

## 2.1.3 - 2018-02-13

* Chore: update `raven-js` to `3.22.3` version.

## 2.1.2 - 2018-02-08

* Chore: update `sentry/sentry` to `1.8.3` version.

## 2.1.1 - 2018-01-17

* Chore: update `raven-js` to `3.22.1` version.

## 2.1.0 - 2018-01-06

* Added: `ExpectCTTracker` for `Expect-CT` security header.
* Added: `XXSSProtectionTracker` for `X-XSS-Protection` security header.

## 2.0.0 - 2017-12-22

* Added: `raven.min.js.map` file for debug error(s) inside `raven-js`.
* Changed: remove all `php` default metrics, please use `sentry_integration_send_data` filter.
* Chore: update `raven-js` to `3.21.0` version.

## 1.0.0

* Initial publish release.
