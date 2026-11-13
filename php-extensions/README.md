# Project-local PHP extensions (PHP 8.5.12 only)

`intl.so` is built for **this project's** PHP 8.5.12 API (`20250925`) and loaded via `config/php-8512.ini`.

Does **not** modify LAMPP or system PHP.

## Rebuild intl (if needed)

```bash
cd /home/ubuntu/Downloads/php-8.5.12/ext/intl   # PHP source matching 8.5.12
/opt/php-8.5.12/bin/phpize
./configure --with-php-config=/opt/php-8.5.12/bin/php-config
make
cp modules/intl.so /opt/lampp/htdocs/roman-thomas/php-extensions/
```

## Verify

```bash
./bin/php -m | grep intl
./spark list
```
