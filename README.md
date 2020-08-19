# ZKTeco - Laravel Library #

[![Issues](https://img.shields.io/github/issues/raihanafroz/zkteco?style=flat-square)](https://github.com/raihanafroz/zkteco/issues)
[![Forks](https://img.shields.io/github/forks/raihanafroz/zkteco?style=flat-square)](https://github.com/raihanafroz/zkteco/network/members)
[![Stars](https://img.shields.io/github/stars/raihanafroz/zkteco?style=flat-square)](https://github.com/raihanafroz/zkteco/stargazers)
[![Total Downloads](https://img.shields.io/packagist/dt/rats/zkteco?style=flat-square)](https://packagist.org/packages/rats/zkteco)


The `rats/zkteco` package provides easy to use functions to ZKTeco Device activities.

__Requires:__  **Laravel** >= **6.0**

__License:__ MIT or later

## Instructions:
You can install the package via composer:

``` bash
composer require rats/zkteco
```
The package will automatically register itself.


## Usage

The ZKTeco Class `use Rats\Zkteco\Lib\ZKTeco;` class.

```php
// create ZKTeco object
//  1 s't parameter is string $ip Device IP Address
//  2 nd  parameter is integer $port Default: 4370
  
    $zk = new ZKTeco('192.168.1.201');
    
```









# end