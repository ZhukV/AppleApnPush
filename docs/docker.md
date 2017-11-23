Use Docker
==========

With this package, you can use the Docker container for testing and development this library.
The image of Docker contain all required for run the demo scenarios - HTTP/2, cURL, PHP 7.1

For build the image and run the container, please execute in you console/terminal:

```bash
# Go to directory where you clone the library
$ cd /path/to/apple-apn-push

# Build the image
$ docker build -t apple-apn-push:latest .

# Run the container with shared volumes
$ docker run -d -it --name apple-apn-push -v "${PWD}:/code" apple-apn-push
```

After starting the container you can attach to the container and execute any commands:

```bash
$ docker exec -it apple-apn-push bash

# Go to code path (we share this path via -v "${PWD}:/code")
$ cd /code

# Run tests
$ ./bin/phpunit

# Run code style
$ ./bin/phpcs --standard=vendor/escapestudios/symfony2-coding-standard/Symfony2/ src/
$ ./bin/phpcs --standard=tests/phpcs-ruleset.xml tests/

# Run PHPMetrics
$ ./bin/phpmetrics src

# Run demo scripts (Attention: you should configure the demo parameters)
$ php demo/jwt.php 
$ php demo/certificate.php 
```
