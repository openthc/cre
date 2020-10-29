# OpenTHC CRE Testing

## Getting started

- PHP 7.3.*

- Composer

Download all the vendor dependencies

From the source root:

```bash
$ composer install

# Tip: Update all packages...
$ composer update
```

Running the PHPUnit tests

```bash

# Runs all test suites defined in phpunit.xml
./vendor/bin/phpunit -c test/phpunit.xml

# Runs all test cases defined in php test script
./vendor/bin/phpunit -c test/phpunit.xml test/Section/SectionTest.php

```


## Reports

Uses XSL to reformat to pretty stuffs

* https://stackoverflow.com/questions/3127108/xsl-xsltemplate-match
* https://www.google.com/search?q=xsl%3Atemplate+match&oq=xsl%3Atemplate+match&aqs=chrome..69i57j69i58.3167j0j7&sourceid=chrome&ie=UTF-8
* https://stackoverflow.com/questions/3116942/doing-file-path-manipulations-in-xslt
http://www.xmlmaster.org/en/article/d01/c08/
https://lenzconsulting.com/how-xslt-works/
