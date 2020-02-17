# Purge Cloudflare Cache
Purges [all](https://api.cloudflare.com/#zone-purge-all-file) cache from Cloudflare CDN. Supports [tags, hosts](https://api.cloudflare.com/#zone-purge-files-by-cache-tags-or-host), or [file](https://api.cloudflare.com/#zone-purge-files-by-url) based purging.

## Required Environment Variables:
* CF_EMAIL - Cloudflare Email
* CF_KEY - Cloudflare API Key

## Example Usage:
```
Usage : cloudflare-purge.php --domain example.com [--tags <tags> | --hosts <hostnames> | --files <files>]
 --domain   | -d  : Domain name.

 Optionally,
 Type of purge, by default all caching is purge. Comma separated if more than one value.
    --tags  |  Tags you want to purge.
    --hosts |  Hostnames you want to purge.
    --files |  Files you want to purge.

 --list     | -l  : List domains avaible.
 --help     | -h  : Help message.
```
