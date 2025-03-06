# WordPress Link Checker

WordPress Link Checker is a command line tool that checks for broken links on a WordPress website.

## Example

```shell
$ php wp-check https://example.com --exclude-type=post --exclude-post=sample-page
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
$ docker compose exec php bash -c "php wp-check <url> [--exclude-type=<post-type>] ... [--exclude-post=<post-link>] ..."
$ docker compose down
```

## Parameters 

The `<url>` is the URL of the WordPress website to check for broken links. 

**NOTE:** URL can include the authentication credentials in the form: `https://user:password@example.com`. 

The `--exclude-type` and `--exclude-post` options are used to exclude post types and posts from the check.

## Requirements

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## License

[MIT](license.txt)