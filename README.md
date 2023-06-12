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
//  2 nd  parameter is int $port Default: 4370
  
    $zk = new ZKTeco('192.168.1.201');
    
//  or you can use with port
//    $zk = new ZKTeco('192.168.1.201', 8080);
    
```

2. Call ZKTeco methods

* __Connect__ 
```php
    /**
     * Connect to device
     *
     * @return bool
     */
    $zk->connect();   
```

* __Disconnect__ 
```php
    /**
     * Disconnect from device
     *
     * @return bool
     */
    $zk->disconnect();
```

* __Enable Device__ 
```php
    /**
     * Enable device
     *
     * @return mixed
     */
    $zk->enableDevice();   
```
> **NOTE**: You have to call after read/write any info of Device.

* __Disable Device__ 
```php
    /**
     * Disable device
     *
     * @return mixed
     */
    $zk->disableDevice(); 
```
> **NOTE**: You have to call before read/write any info of Device. 


* __Device Version__ 
```php
    /**
     * Get device version
     *
     * @return mixed
     */
    $zk->version(); 
```


* __Device Os Version__ 
```php
    /**
     * Get OS version
     *
     * @return mixed
     */
    $zk->osVersion(); 
```

* __Power Off__ 
```php
    /**
     * turn off the device
     *
     * @return mixed
     */
    $zk->shutdown(); 
```

* __Restart__ 
```php
    /**
     * restart the device
     *
     * @return mixed
     */
    $zk->restart(); 
```

* __Sleep__ 
```php
    /**
     * make sleep mood the device
     *
     * @return mixed
     */
    $zk->sleep(); 
```

* __Resume__ 
```php
    /**
     * resume the device from sleep
     *
     * @return mixed
     */
    $zk->resume(); 
```

* __Voice Test__ 
```php
    /**
     * voice test Sound will "Thank you"
     *
     * @return mixed
     */
    $zk->testVoice(); 
```

* __Platform__ 
```php
    /**
     * Get platform
     *
     * @return mixed
     */
    $zk->platform(); 
```

* __Firmware Version__ 
```php
    /**
     * Get firmware version
     *
     * @return mixed
     */
    $zk->fmVersion(); 
```

* __Work Code__ 
```php
    /**
     * Get work code
     *
     * @return mixed
     */
    $zk->workCode(); 
```

* __SSR__ 
```php
    /**
     * Get SSR
     *
     * @return mixed
     */
    $zk->ssr(); 
```

* __Pin Width__ 
```php
    /**
     * Get pin width
     *
     * @return mixed
     */
    $zk->pinWidth(); 
```

* __Serial Number__ 
```php
    /**
     * Get device serial number
     *
     * @return mixed
     */
    $zk->serialNumber(); 
```

* __Device Name__ 
```php
    /**
     * Get device name
     *
     * @return mixed
     */
    $zk->deviceName(); 
```

* __Get Device Time__ 
```php
    /**
     * Get device time
     *
     * @return bool|string Format: "Y-m-d H:i:s"
     */
    $zk->getTime(); 
```

* __Set Device Time__ 
```php
    /**
     * Set device time
     *
     * @param string $t Format: "Y-m-d H:i:s"
     * @return mixed
     */
    $zk->setTime(); 
```

* __Get Users__ 
```php
    /**
     * Get users data
     *
     * @return array [userid, name, cardno, uid, role, password]
     */
    $zk->getUser(); 
```

* __Set Users__ 
```php
    /**
     * Set user data
     *
     * @param int $uid Unique ID (max 65535)
     * @param int|string $userid ID in DB (same like $uid, max length = 9, only numbers - depends device setting)
     * @param string $name (max length = 24)
     * @param int|string $password (max length = 8, only numbers - depends device setting)
     * @param int $role Default Util::LEVEL_USER
     * @param int $cardNo
     * @return mixed
     */
    $zk->setUser(); 
```

* __Clear All Admin__ 
```php
    /**
     * Remove admin
     *
     * @return mixed
     */
    $zk->clearAdmin(); 
```

* __Restores Access Control set to the default condition__
```php
    /**
     * This will restores Access Control set to the default condition
     *
     * @return mixed
     */
    $zk->clearAccessControl(); 
```

* __Remove A User__ 
```php
    /**
     * Remove user by UID
     *
     * @param int $uid
     * @return mixed
     */
    $zk->removeUser($uid); 
```

* __Get Attendance Log__ 
```php
    /**
     * Get attendance log
     *
     * @return array [uid, id, state, timestamp]
     */
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
    /**
     * Clear attendance log
     *
     * @return mixed
     */
    $zk->clearAttendance(); 
```

* __Refresh Machine Interior Data__
```php
    /**
     * This will refresh the machine interior data
     *
     * @return mixed
     */
    $zk->refreshData(); 
```
# THE END
