danielbachhuber/dh-cache-command
================================

Page cache detection and WP Super Cache configuration.

[![Build Status](https://travis-ci.org/danielbachhuber/dh-cache-command.svg?branch=master)](https://travis-ci.org/danielbachhuber/dh-cache-command)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing) | [Support](#support)

## Using

This package implements the following commands:

### wp dh-cache detect

Detects presence of a page cache.

~~~
wp dh-cache detect [--format=<format>]
~~~

Page cache detection happens in two steps:

1. If the `WP_CACHE` constant is true and `advanced-cache.php` exists,
then `page_cache=enabled`. However, if `advanced-cache.php` is missing,
then `page_cache=broken`.
2. Scans `active_plugins` options for known page cache plugins, and
reports them if found.

See 'Examples' section for demonstrations of usage.

**OPTIONS**

	[--format=<format>]
		Render output in a specific format.
		---
		default: table
		options:
		  - table
		  - json
		  - yaml
		---

**EXAMPLES**

    # WP Super Cache detected.
    $ wp dh-cache detect
    +-------------------+----------------+
    | key               | value          |
    +-------------------+----------------+
    | page_cache        | enabled        |
    | page_cache_plugin | wp-super-cache |
    +-------------------+----------------+

    # Page cache detected but plugin is unknown.
    $ wp dh-cache detect
    +-------------------+---------+
    | key               | value   |
    +-------------------+---------+
    | page_cache        | enabled |
    | page_cache_plugin | unknown |
    +-------------------+---------+

    # No page cache detected.
    $ wp dh-cache detect
    +-------------------+----------+
    | key               | value    |
    +-------------------+----------+
    | page_cache        | disabled |
    | page_cache_plugin | none     |
    +-------------------+----------+



### wp dh-cache configure-super-cache-settings

Configures WP Super Cache settings.

~~~
wp dh-cache configure-super-cache-settings 
~~~

Imposes expected value for the following settings:

* Enabled: Full-page caching.
* Enabled: Expert cache delivery method.
* Enabled: .htaccess rewrite rules for expert cache.
* Disabled: Caching pages for logged-in users.
* Disabled: Caching pages with GET parameters.
* Enabled: Serve existing cache while being generated.
* Disabled: Make known users anonymous and serve supercached files.
* Disabled: Proudly tell the world your server is Stephen Fry proof.
* Enabled: Mobile device support.

See 'Examples' section for demonstrations of usage.

**EXAMPLES**

    # Three settings are incorrect and updated.
    $ wp dh-cache configure-super-cache-settings
    Updated 'Don't cache pages for known users' to 'enabled'.
    Updated 'Don't cache pages with GET parameters' to 'enabled'.
    Updated 'Serve existing cache while being generated' to 'enabled'.
    Success: Updated 3 WP Super Cache settings.

    # No cache settings are incorrect.
    $ wp dh-cache configure-super-cache-settings
    Success: All WP Super Cache settings are correctly configured without changes.



### wp dh-cache verify-super-cache-settings

Verifies WP Super Cache configuration settings.

~~~
wp dh-cache verify-super-cache-settings [--format=<format>]
~~~

Checks the following configuration settings for correct values:

* Enabled: Full-page caching.
* Enabled: Expert cache delivery method.
* Enabled: .htaccess rewrite rules for expert cache.
* Disabled: Caching pages for logged-in users.
* Disabled: Caching pages with GET parameters.
* Enabled: Serve existing cache while being generated.
* Disabled: Make known users anonymous and serve supercached files.
* Disabled: Proudly tell the world your server is Stephen Fry proof.
* Enabled: Mobile device support.

See 'Examples' section for demonstrations of usage.

**OPTIONS**

	[--format=<format>]
		Render output in a specific format.
		---
		default: table
		options:
		  - table
		  - json
		  - yaml
		---

**EXAMPLES**

    # One cache setting is incorrect.
    $ wp dh-cache verify-super-cache-settings
    +-----------------------------------+----------+----------+
    | setting                           | actual   | expected |
    +-----------------------------------+----------+----------+
    | Caching enabled                   | enabled  | enabled  |
    | Don't cache pages for known users | disabled | enabled  |
    | [...]                             |          |          |
    +-----------------------------------+----------+----------+
    Error: 1 WP Super Cache setting is incorrect.

## Installing

Installing this package requires WP-CLI v1.1.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with:

    wp package install git@github.com:danielbachhuber/dh-cache-command.git

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

For a more thorough introduction, [check out WP-CLI's guide to contributing](https://make.wordpress.org/cli/handbook/contributing/). This package follows those policy and guidelines.

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/danielbachhuber/dh-cache-command/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/danielbachhuber/dh-cache-command/issues/new). Include as much detail as you can, and clear steps to reproduce if possible. For more guidance, [review our bug report documentation](https://make.wordpress.org/cli/handbook/bug-reports/).

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/danielbachhuber/dh-cache-command/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, [please follow our guidelines for creating a pull request](https://make.wordpress.org/cli/handbook/pull-requests/) to make sure it's a pleasant experience. See "[Setting up](https://make.wordpress.org/cli/handbook/pull-requests/#setting-up)" for details specific to working on this package locally.

## Support

Github issues aren't for general support questions, but there are other venues you can try: https://wp-cli.org/#support


*This README.md is generated dynamically from the project's codebase using `wp scaffold package-readme` ([doc](https://github.com/wp-cli/scaffold-package-command#wp-scaffold-package-readme)). To suggest changes, please submit a pull request against the corresponding part of the codebase.*
