# ZKTeco - Laravel Library #

[![Issues](https://img.shields.io/github/issues/raihanafroz/zkteco?style=flat-square)](https://github.com/raihanafroz/zkteco/issues)
[![Forks](https://img.shields.io/github/forks/raihanafroz/zkteco?style=flat-square)](https://github.com/raihanafroz/zkteco/network/members)
[![Stars](https://img.shields.io/github/stars/raihanafroz/zkteco?style=flat-square)](https://github.com/raihanafroz/zkteco/stargazers)
[![Total Downloads](https://img.shields.io/packagist/dt/rats/zkteco?style=flat-square)](https://packagist.org/packages/rats/zkteco)
[![License](https://poser.pugx.org/rats/zkteco/license.svg)](https://packagist.org/packages/rats/zkteco)


The `rats/zkteco` package provides easy to use functions to ZKTeco Device activities.

__Requires:__  **Laravel** >= **6.0**

__License:__ MIT or later

## Installation:
You can install the package via composer:

``` bash
composer require rats/zkteco
```
The package will automatically register itself.


## Usage

1. Create a object of ZKTeco class.

```php
    use Rats\Zkteco\Lib\ZKTeco;

//  1 s't parameter is string $ip Device IP Address
//  2 nd  parameter is integer $port Default: 4370
  
    $zk = new ZKTeco('192.168.1.201');
    
//  or you can use with port
//    $zk = new ZKTeco('192.168.1.201', 8080);
    
```

2. Call ZKTeco methods

* __Connect__ 
```php
//    connect
//    this return bool
    $zk->connect();   
```

* __Disconnect__ 
```php
//    disconnect
//    this return bool

    $zk->disconnect();   
```

* __Enable Device__ 
```php
//    enable
//    this return bool/mixed

    $zk->enableDevice();   
```
> **NOTE**: You have to call after read/write any info of Device.

* __Disable Device__ 
```php
//    disable 
//    this return bool/mixed

    $zk->disableDevice(); 
```
> **NOTE**: You have to call before read/write any info of Device. 






# end