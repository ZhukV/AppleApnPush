Apple Apn Push
==============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/53f2239f-c4cc-4643-85c9-a9f79850e863/mini.png)](https://insight.sensiolabs.com/projects/53f2239f-c4cc-4643-85c9-a9f79850e863)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ZhukV/AppleApnPush/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ZhukV/AppleApnPush/?branch=master)
[![Build Status](https://github.com/ZhukV/AppleApnPush/workflows/Testing/badge.svg?branch=master)](https://github.com/ZhukV/AppleApnPush/actions)

Send push notifications to apple devices (iPhone, iPad, iPod).

Support authenticators:

* Certificate
* Json Web Token

Supported protocols:

* HTTP/2

Requirements
------------

Now library work only with HTTP/2 protocol, and next libraries is necessary:

* [cURL](http://php.net/manual/ru/book.curl.php)
* The protocol [HTTP/2](https://en.wikipedia.org/wiki/HTTP/2) must be supported in cURL.
* PHP 7.1 or higher

Installation
------------

Add AppleApnPush in your composer.json:

````json
{
    "require": {
        "apple/apn-push": "~3.0.0"
    }
}
````

Now tell composer to download the library by running the command:

```bash
$ php composer.phar update apple/apn-push
```

Documentation
----------

The source of the documentation is stored in the `docs` folder in this package:

[Read the Documentation](docs/index.md)

[Develop and testing via Docker](docs/docker.md)

License
-------

This library is under the MIT license. See the complete license in library

```
LICENSE
```

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/ZhukV/AppleApnPush/issues).

Contributors:
-------------

Thanks to [everyone participating](https://github.com/ZhukV/AppleApnPush/graphs/contributors) in the development of this AppleApnPush library!

> **Note:** if you want to be a contributor to this library, we can send the OpenSource license of PhpStorm for development this library.
For this, please contact me via email zhuk2205`[dot]`gmail.com

Thanks
------

Very thank for [JetBrains](https://www.jetbrains.com/) for providing the OpenSource license for development this library with [PhpStorm](https://www.jetbrains.com/phpstorm/).
