# Contributing

First off, thank you for considering contributing to Sentry Integration.

## 1. Get started

If you've noticed a bug or have a question [search the issue tracker](https://github.com/itgalaxy/sentry-integration/issues?q=something) to see if someone else in the community has already created a ticket. If not, go ahead and [make one](https://github.com/itgalaxy/sentry-integration/issues/new)!

## 2. Fork & create a branch and submit a PR

If this is something you think you can fix, then [fork WordPress Sentry](https://help.github.com/articles/fork-a-repo) and create a branch with a descriptive name.

A good branch name would be (where issue #64 is the ticket you're working on):

```sh
git checkout -b issue-64-fix-errors-not-reporting
```

After pushing this to your fork you can [create a pull request](https://help.github.com/articles/creating-a-pull-request-from-a-fork/) to contribute your changes.

## 3. Local Development

Install composer and npm dependencies:

```sh
$ composer install && npm install
```

Create a database for your tests to use and update your `tests/wp-config.php` as necessary.

```sh
$ mysqladmin create wp_phpunit_tests -u root
```

The database name defaults to `wp_phpunit_tests`, but you can change this in the `tests/wp-config.php` without affecting the Travis configuration which is environment variable-based.

Run the tests:

```sh
$ npm run test
```
