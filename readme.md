# WordPress Link Checker

WordPress Link Checker is a command line tool that checks for broken links on a WordPress website.

## Example

```shell
$ php wp-check https://example.com --user-agent=WordPressLinkChecker/1.1.0 --exclude-type=post --exclude-post=https://example.com/sample-page
```

## Setup

```shell
$ docker compose up -d 
$ docker compose exec php bash -c "composer install"
$ docker compose down
```

## Usage

```shell
$ docker compose up -d
$ docker compose exec php bash -c "php wp-check <url> [--user-agent=<user-agent>] [--exclude-type=<post-type>] ... [--exclude-post=<post-link>] ..."
$ docker compose down
```

## Parameters 

The `<url>` argument is the URL of the WordPress website to check for broken links. 

**NOTE:** URL can include the authentication credentials in the form: `https://user:password@example.com`. 

- `--user-agent` - option is used to set a custom user agent for the requests.
- `--exclude-type` - option is used to exclude post types by slug from the check.
- `--exclude-post` - option is used to exclude posts by URL from the check.

## Changelog

Version 1.1.0

- Added `--user-agent` parameter to set a custom user agent for requests.
- Added support for WordPress Multisite installations in subdirectory mode.

Version 1.0.0

- Initial release.

## Requirements

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## License

[MIT](license.txt)