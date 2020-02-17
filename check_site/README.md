
# Simple HTTP PHPUnit Site Check Test

## Required Environment Variables:
* DOMAIN - domain name.
* GOOGLE_GA_STRING - test string.
* CONTAINER - container service name.

## Optional Environment Variables:
* SKIP_UNIT_TESTS

## Example Usage:
```
docker run --rm -it -v /tmp:/tests -e DOMAIN=antoniobaltazar.com -e GOOGLE_GA_STRING='UA-12912270-4' \
alpha01-jenkins phpunit /check_site/tests/CheckSiteTest.php --verbose --log-junit /tests/test.xml
```
