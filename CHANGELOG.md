# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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
