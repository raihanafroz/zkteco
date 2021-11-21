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

You have to enable your php socket if it is not enable. 


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


* __Device Version__ 
```php
//    get device version 
//    this return bool/mixed

    $zk->version(); 
```


* __Device Os Version__ 
```php
//    get device os version 
//    this return bool/mixed

    $zk->osVersion(); 
```

* __Power Off__ 
```php
//    turn off the device 
//    this return bool/mixed

    $zk->shutdown(); 
```

* __Restart__ 
```php
//    restart the device 
//    this return bool/mixed

    $zk->restart(); 
```

* __Sleep__ 
```php
//    sleep the device 
//    this return bool/mixed

    $zk->sleep(); 
```

* __Resume__ 
```php
//    resume the device from sleep 
//    this return bool/mixed

    $zk->resume(); 
```

* __Voice Test__ 
```php
//    voice test of the device "Thank you" 
//    this return bool/mixed

    $zk->testVoice(); 
```

* __Platform__ 
```php
//    get platform 
//    this return bool/mixed

    $zk->platform(); 
```

* __Firmware Version__ 
```php
//    get firmware version
//    this return bool/mixed

    $zk->fmVersion(); 
```

* __Work Code__ 
```php
//    get work code
//    this return bool/mixed

    $zk->workCode(); 
```

* __SSR__ 
```php
//    get SSR
//    this return bool/mixed

    $zk->ssr(); 
```

* __Pin Width__ 
```php
//    get  Pin Width
//    this return bool/mixed

    $zk->pinWidth(); 
```

* __Serial Number__ 
```php
//    get device serial number
//    this return bool/mixed

    $zk->serialNumber(); 
```

* __Device Name__ 
```php
//    get device name
//    this return bool/mixed

    $zk->deviceName(); 
```

* __Get Device Time__ 
```php
//    get device time

//    return bool/mixed bool|mixed Format: "Y-m-d H:i:s"

    $zk->getTime(); 
```

* __Set Device Time__ 
```php
//    set device time
//    parameter string $t Format: "Y-m-d H:i:s"
//    return bool/mixed

    $zk->setTime(); 
```

* __Get Users__ 
```php
//    get User
//    this return array[]

    $zk->getUser(); 
```

* __Set Users__ 
```php
//    set user

//    1 s't parameter int $uid Unique ID (max 65535)
//    2 nd parameter int|string $userid ID in DB (same like $uid, max length = 9, only numbers - depends device setting)
//    3 rd parameter string $name (max length = 24)
//    4 th parameter int|string $password (max length = 8, only numbers - depends device setting)
//    5 th parameter int $role Default Util::LEVEL_USER
//    6 th parameter int $cardno Default 0 (max length = 10, only numbers

//    return bool|mixed

    $zk->setUser(); 
```

* __Clear All Admin__ 
```php
//    remove all admin
//    return bool|mixed

    $zk->clearAdmin(); 
```

* __Clear All Users__ 
```php
//    remove all users
//    return bool|mixed

    $zk->clearAdmin(); 
```

* __Remove A User__ 
```php
//    remove a user by $uid
//    parameter integer $uid
//    return bool|mixed

    $zk->removeUser(); 
```

* __Get Attendance Log__ 
```php
//    get attendance log

//    return array[]

//    like as 0 => array:5 [▼
//              "uid" => 1      /* serial number of the attendance */
//              "id" => "1"     /* user id of the application */
//              "state" => 1    /* the authentication type, 1 for Fingerprint, 4 for RF Card etc */
//              "timestamp" => "2020-05-27 21:21:06" /* time of attendance */
//              "type" => 255   /* attendance type, like check-in, check-out, overtime-in, overtime-out, break-in & break-out etc. if attendance type is none of them, it gives  255. */
//              ]

    $zk->getAttendance(); 
```

* __Clear Attendance Log__ 
```php
//    clear attendance log

//    return bool/mixed

    $zk->clearAttendance(); 
```







# end
