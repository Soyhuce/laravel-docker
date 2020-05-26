# Docker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/laravel-docker.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-docker)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/soyhuce/laravel-docker/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/laravel-docker.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-docker)

This package allows you to communicate with a Docker instance via unix socket or API.

API mode is based on [API Docker Engine](https://docs.docker.com/engine/api/v1.40)

## Installation

You can install the package via composer:
 
 `composer require soyhuce/laravel-docker`

## Utilisation

#### Configuration

Publish configuration file via:

`php artisan vendor:publish --provider="Soyhuce\Docker\ServiceProvider"`

#### API

To use this package with API drive, you can expose Docker on HTTP port.

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

You can also use the unix socket to connect to Docker :

```
[
    'driver' => 'api',
    'version' => 'v1.40',
    'drivers' => [
        'socket' => [
            'unix_socket' => '/var/run/docker.sock',
        ],
    ],
]
```


#### Working with Docker containers

* create($imageName, $containerName) : Create a container from an image
* start($containerId) : Start a container from its name or id
* stop($containerId) : Stop a container from its name or id
* wait($containerId) : Wait a container from its name or id
* delete($containerId) : Delete a container from its name or id

#### Working with Docker images

* create($imageName, $tagName) : Pull an image
* all() : Retrieve all images on your Docker instance
* remove($imageName) : Remove an image from its name
