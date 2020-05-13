# Docker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/docker-php.svg?style=flat-square)](https://packagist.org/packages/soyhuce/docker-php)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/soyhuce/docker-php/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/docker-php.svg?style=flat-square)](https://packagist.org/packages/soyhuce/docker-php)

This package allows you to communicate with a Docker instance via unix socket or API.

API mode is based on [API Docker Engine](https://docs.docker.com/engine/api/v1.40)

## Installation

You can install the package via composer:
 
 `composer require soyhuce/docker-php`

## Utilisation

#### Configuration

Publish configuration file via:

`php artisan vendor:publish --provider="Soyhuce\Docker\ServiceProvider"`

#### API

To use this package with API drive, you need to expose Docker on HTTP port.

For example, you can do this: 

`socat TCP-LISTEN:<port-number>,reuseaddr,fork UNIX-CLIENT:<path-to-unix-socket>`

So, in your configuration file, you have to define your configuration file like this:

```
[
    'driver' => 'api',
    'version' => 'v1.40',
    'drivers' => [
        'api' => [
            'url' => 'http://127.0.0.1:<port-number>',
        ]
    ],
]
```

#### Working with Docker containers

* create($imageName, $containerName) : Create a container from an image
* start($containerId) : Start a container from its id

#### Working with Docker images

* create($imageName, $tagName) : Pull an image
* all() : Retrieve all images on your Docker instance
* remove($imageName) : Remove an image from its name
