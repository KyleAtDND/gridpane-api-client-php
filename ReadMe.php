# GridPane PHP API Client Library #

## API Client Version

This is the first version of a community sponsored PHP API client for GridPane.

## API version support

This client **only** supports GridPane's API v1.  Please see their [API documentation](https://gridpane.com/kb/gridpane-api-introduction-and-postman-documentation/) for more information.

## Requirements
* PHP 8.1+

## Installation

The GridPane PHP API client can be installed using [Composer](https://packagist.org/packages/kyleatdnd/gridpane-api-client-php).

### Composer

To install run `composer require kyleatdnd/gridpane-api-client-php`

## Configuration

Configuration is done through an instance of `GridPane\API\HttpClient`.
The block is mandatory and if not passed, an error will be thrown.

``` php
// load Composer
require 'vendor/autoload.php';

use GridPane\API\HttpClient as GridPaneAPI;

$token     = "6wiIBWbGkBMo1mRDMuVwkw1EPsNkeUj95PIz2akv"; // replace this with your GridPane Bearer token

$client = new GridPaneAPI();
$client->setAuth('bearer', ['token' => $token]);
```

## Usage

### Basic Operations

``` php
// Get all servers
$servers = $client->servers()->findAll();
print_r($servers);

// Get all servers regarding a specific user.
// $servers = $client->users($requesterId)->servers()->requested();
// print_r($servers);

// Create a new server
$newServer = $client->servers()->create([
    'servername' => 'hal9000',                          
    'ip' => '199.199.199.199',                        
    'datacenter' => 'space-station-v',                     
    'webserver' => 'nginx',      
    'database' => 'percona'
]);
print_r($newServer);

// Update a server
$client->servers()->update(1,[
    'security_updates_reboot_time' => '04:00'
]);

// Delete a server
$client->servers()->delete(1);

// Get all sites
$users = $client->sites()->findAll();
print_r($users);
```

### Pagination
The GridPane API offers a way to get the next pages for the requests and is documented in [the GridPane Developer Documentation](https://developer.zendesk.com/rest_api/docs/core/introduction#pagination).

The way to do this is to pass it as an option to your request.

``` php
$servers = $this->client->servers()->findAll(['per_page' => 100, 'page' => 2]);
```

The allowed options are
* per_page
* page

### Retrying Requests

Add the `RetryHandler` middleware on the `HandlerStack` of your `GuzzleHttp\Client` instance. By default `GridPane\Api\HttpClient` 
retries: 
* timeout requests
* those that throw `Psr\Http\Message\RequestInterface\ConnectException:class`
* and those that throw `Psr\Http\Message\RequestInterface\RequestException:class` that are identified as ssl issue.

#### Available options
Options are passed on `RetryHandler` as an array of values.

* max = 2 _limit of retries_
* interval = 300 _base delay between retries in milliseconds_
* max_interval = 20000 _maximum delay value_
* backoff_factor = 1 _backoff factor_
* exceptions = [ConnectException::class] _Exceptions to retry without checking retry_if_
* retry_if = null _callable function that can decide whether to retry the request or not_

## Contributing

Pull Requests are always welcome but before you send one please read our [contribution guidelines](#CONTRIBUTING.md). It would
speed up the process and would make sure that everybody follows the community's standard.

### Debugging

To help would be contributors, we've added a REPL tool. It is a simple wrapper for [psysh](http://psysh.org) and symfony's console.
On your terminal, run `bin/console <subdomain> <email> <api token>`. This would automatically create an instance of `GridPane\API\HttpClient` on $client variable.
After that you would be able to enter any valid php statement. The goal of the tool is to speed up the process in which developers
can experiment on the code base.

## Copyright and license

Copyright 2013-present GridPane

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

