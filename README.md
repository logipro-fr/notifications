# Notifications

Allow to send web push notification on website.

# Install

```console
git clone git@github.com:
./install

Copy the public key from public_key to your script.js (in line 2): const applicationServerKey = 'COPY_YOUR_PUBLIC_KEY_HERE';
```

# To Contribute to Notifications

## Requirements

* docker
* git
* a web browser (chrome, firefox, ...)

To use notification you need a website who using a HTTPS protocol. If you test with the integrate webapp test, you need to autorize HTTP communication on your web browser for the service worker (parameters in inspectate menu).

## Unit test

```console
bin/phpunit
```

Using Test-Driven Development (TDD) principles (thanks to Kent Beck and others), following good practices (thanks to Uncle Bob and others).

## Manual tests

```console
./start
```

Then, you can access Swagger at http://172.17.0.1:11403/ in your browser to test different routes. 
You can also access phpMyAdmin at http://172.17.0.1:11402/ and test the demo page with `NGINX` at http://172.17.0.1:11480/index.html.


In `docker/mariad/db.env`, you can set a new password for the root user.

To stop the application, use:

```console
./stop
```

## Quality

Some indicators that seem interesting.

* phpcs PSR12
* phpstan level 9
* 100% coverage obtained naturally thanks to the “classic school” TDD approach
* we hunt mutants with “Infection”. We aim for an MSI score of 100% for “panache”


Quick check with:
```console
./codecheck
./bin/phpunit
```

Check coverage with:
```console
bin/phpunit --coverage-html var
```
and view 'var/index.html' with your browser

Check infection with:
```console
bin/infection
```
and view 'var/infection.html' with your browser