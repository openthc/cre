# OpenTHC CRE Testing

Just running `test.sh` should be enough.
You may need to source your environment file (`.env`) depending on your configuration.


```
./test.sh
```

## Specific Tests

From in the `./test` directory you can run specific tests or one of the named suites in the config

```
../vendor/bin/phpunit --list-suites
../vendor/bin/phpunit --list-tests
../vendor/bin/phpunit ./0_Auth/0_Alpha_Test.php
```


## Reports

The default script generates a report in `./webroot/test-output`.
