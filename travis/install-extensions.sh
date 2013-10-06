#!/bin/bash

set -e

function addExtToPHP
{
	echo "extension=$1.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
}

# Install PHP Redis extension
git clone git://github.com/nicolasff/phpredis.git
cd phpredis
phpize
./configure
make
make install
cd ..

addExtToPHP "redis"