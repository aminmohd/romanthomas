# PHP `intl` requirement (CodeIgniter 4)

CodeIgniter 4.7.x **requires** the PHP **intl** extension.

For **roman-thomas only**, `intl` is loaded from `php-extensions/intl.so` via `config/php-8512.ini` when you use:

- `./bin/php`
- `./spark`
- `./serve`

This does **not** change LAMPP or other PHP installs.

## Verify

```bash
cd /opt/lampp/htdocs/roman-thomas
./bin/php -m | grep intl
./spark list
```

## Run CI4

```bash
./serve
```

Apache: document root `public/`, and configure the vhost to use `/opt/php-8.5.12` **with** `PHPRC` pointing at this project's `config/php-8512.ini` if you use PHP-FPM/CGI for CI4.
