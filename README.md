# ReadMe

This is an extension for TYPO3 to host a composer server in a TYPO3 instance. Every package can have specific access
rights to allow access only for authorized accounts (authorization method is http-basic, see [here][composer-http-basic]
for further details). In addition, packages can be added to groups to easily build bundles for often used package
collections.

## Configuration
All configuration values are stored in `$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server']...`.

| Key: | Type: | Description: | Default: |
| --- | --- | --- | --- |
| `...['frontend']['disable']` | _boolean_ | If true, Frontend is completely disabled. | _false_ |
| `...['frontend']['title']` | _string_ | Application title for frontend. | _Composer Server_ |
| `...['frontend']['footer']['copyrightName']` | _string_ | Name of the copyright holder. | - |
| `...['frontend']['footer']['copyrightHomepage']` | _string_ | Homepage of the copyright holder. | - |
| `...['frontend']['footer']['copyrightNotice']` | _string_ | Copyright notice in footer. | _All rights reserved._ |
| `...['frontend']['footer']['description']` | _string_ | Short description text for footer. | - |
| `...['frontend']['cookieName']` | _string_ | The cookie name for frontend login. | _auth_ |
| `...['frontend']['cookieLifetime']` | _integer_ | Duration in seconds the cookie is valid. | _3600_ |
| `...['frontend']['bruteForceSleepDuration']` | _integer_ | Duration in seconds to sleep when trying to log in with invalid login credentials. | _2_ |
| `...['updateUri']` | _string_ | The URL path to trigger the update command. Set this to empty to disable updates via URL. | _update_ |
| `...['hashingAlgorithm']` | _string_ | The hashing algorithm for package checksums. See [here](https://www.php.net/manual/de/function.hash-hmac-algos.php) for other possible values. | _sha256_ |
| `...['hostname']` | _string_ | The hostname for the server, if not `$_SERVER['HTTP_HOST']`.  | _Value of $\_SERVER['HTTP_HOST']_ |

## Updating cached packages
There are three ways to update packages:
1. Running console command.
2. Running task via TYPO3 scheduler (extension [scheduler][typo3-ext-scheduler] required) to trigger console command.
3. Request update URL.

### Console command
To update cached packages via console, run the following command:

```bash
./vendor/bin/typo3 composer-server:update
```

Optionally, the package updates can be forced (even if no changes exist) with the `--force-reload` flag (or just `-f`):

```bash
./vendor/bin/typo3 composer-server:update --force-reload
```

### TYPO3 scheduler task
Add a new task in TYPO3 scheduler to trigger the console command `composer-server:update` .

### Request update URL
By default, the update URL is `/update` (configurable through extension configuration). Git hooks are a good way to
trigger this only when changes are pushed. Therefore, the update is only running when necessary.

[composer-http-basic]: https://getcomposer.org/doc/articles/authentication-for-private-packages.md#http-basic
[typo3-ext-scheduler]: https://docs.typo3.org/c/typo3/cms-scheduler/master/en-us/
