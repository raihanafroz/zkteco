<?php

namespace Rats\Zkteco\Lib;

use Exception;
use Rats\Zkteco\Lib\Helper\Attendance;
use Rats\Zkteco\Lib\Helper\Connect;
use Rats\Zkteco\Lib\Helper\Device;
use Rats\Zkteco\Lib\Helper\Face;
use Rats\Zkteco\Lib\Helper\Fingerprint;
use Rats\Zkteco\Lib\Helper\Os;
use Rats\Zkteco\Lib\Helper\Pin;
use Rats\Zkteco\Lib\Helper\Platform;
use Rats\Zkteco\Lib\Helper\SerialNumber;
use Rats\Zkteco\Lib\Helper\Ssr;
use Rats\Zkteco\Lib\Helper\Time;
use Rats\Zkteco\Lib\Helper\User;
use Rats\Zkteco\Lib\Helper\Util;
use Rats\Zkteco\Lib\Helper\Version;
use Rats\Zkteco\Lib\Helper\WorkCode;


class ZKTeco
{
    public string $_ip;
    public int $_port;
    public $_zkClient;

    public string $_data_recv = '';
    public int $_session_id = 0;
    public string $_section = '';

    /**
     * ZKLib constructor.
     *
     * @param string $ip Device IP
     * @param int $port Default: 4370
     */
    public function __construct(string $ip, $port = 4370)
    {
        $this->_ip = $ip;
        $this->_port = $port;

        $this->_zkClient = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        $timeout = array('sec' => 60, 'usec' => 500000);
        socket_set_option($this->_zkClient, SOL_SOCKET, SO_RCVTIMEO, $timeout);

    }

    /**
     * Create and send command to device
     *
     * @param string $command
     * @param string $command_string
     * @param string $type
     *
     * @return float|bool|int|string
     */
    public function _command(string $command, string $command_string, $type = Util::COMMAND_TYPE_GENERAL): float|bool|int|string
    {
        $checkSum = 0;
        $session_id = $this->_session_id;

        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->_data_recv, 0, 8));
        $reply_id = hexdec($u['h8'] . $u['h7']);

        $buf = Util::createHeader($command, $checkSum, $session_id, $reply_id, $command_string);

        socket_sendto($this->_zkClient, $buf, strlen($buf), 0, $this->_ip, $this->_port);

        try {
            @socket_recvfrom($this->_zkClient, $this->_data_recv, 1024, 0, $this->_ip, $this->_port);

            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr($this->_data_recv, 0, 8));

            $ret = false;
            $session = hexdec($u['h6'] . $u['h5']);

            if ($type === Util::COMMAND_TYPE_GENERAL && $session_id === $session) {
                $ret = substr($this->_data_recv, 8);
            } else if ($type === Util::COMMAND_TYPE_DATA && !empty($session)) {
                $ret = $session;
            }

            return $ret;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Connect to device
     *
     * @return bool
     */
    public function connect(): bool
    {
        return Connect::connect($this);
    }

    /**
     * Disconnect from device
     *
     * @return bool
     */
    public function disconnect(): bool
    {
        return Connect::disconnect($this);
    }

    /**
     * Get device version
     *
     * @return mixed
     */
    public function version(): mixed
    {
        return Version::get($this);
    }

    /**
     * Get OS version
     *
     * @return mixed
     */
    public function osVersion(): mixed
    {
        return Os::get($this);
    }

    /**
     * Get platform
     *
     * @return mixed
     */
    public function platform(): mixed
    {
        return Platform::get($this);
    }

    /**
     * Get firmware version
     *
     * @return mixed
     */
    public function fmVersion(): mixed
    {
        return Platform::getVersion($this);
    }

    /**
     * Get work code
     *
     * @return mixed
     */
    public function workCode(): mixed
    {
        return WorkCode::get($this);
    }

    /**
     * Get SSR
     *
     * @return mixed
     */
    public function ssr(): mixed
    {
        return Ssr::get($this);
    }

    /**
     * Get pin width
     *
     * @return mixed
     */
    public function pinWidth(): mixed
    {
        return Pin::width($this);
    }

    /**
     * @return mixed
     */
    public function faceFunctionOn(): mixed
    {
        return Face::on($this);
    }

    /**
     * Get device serial number
     *
     * @return mixed
     */
    public function serialNumber(): mixed
    {
        return SerialNumber::get($this);
    }

    /**
     * Get device name
     *
     * @return mixed
     */
    public function deviceName(): mixed
    {
        return Device::name($this);
    }

    /**
     * Disable device
     *
     * @return mixed
     */
    public function disableDevice(): mixed
    {
        return Device::disable($this);
    }

    /**
     * Enable device
     *
     * @return mixed
     */
    public function enableDevice(): mixed
    {
        return Device::enable($this);
    }

    /**
     * Get users data
     *
     * @return array [userid, name, cardno, uid, role, password]
     */
    public function getUser(): array
    {
        return User::get($this);
    }

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
    public function setUser(int $uid, int|string $userid, string $name, int|string $password, $role = Util::LEVEL_USER, $cardNo = 0): mixed
    {
        return User::set($this, $uid, $userid, $name, $password, $role, $cardNo);
    }

    /**
     * Remove All users
     *
     * @return mixed
     */
    public function clearUsers(): mixed
    {
        return User::clear($this);
    }

    /**
     * Remove admin
     *
     * @return mixed
     */
    public function clearAdmin(): mixed
    {
        return User::clearAdmin($this);
    }

    /**
     * This will restores Access Control set to the default condition
     *
     * @return mixed
     */
    public function clearAccessControl(): mixed
    {
        return User::clearAccessControl($this);
    }

    /**
     * Remove user by UID
     *
     * @param int $uid
     * @return mixed
     */
    public function removeUser(int $uid): mixed
    {
        return User::remove($this, $uid);
    }

    /**
     * Get fingerprint data array by UID
     * TODO: Can get data, but don't know how to parse the data. Need more documentation about it...
     *
     * @param int $uid Unique ID (max 65535)
     * @return array Binary fingerprint data array (where key is finger ID (0-9))
     */
    public function getFingerprint(int $uid): array
    {
        return Fingerprint::get($this, $uid);
    }

    /**
     * Set fingerprint data array
     * TODO: Still can not set fingerprint. Need more documentation about it...
     *
     * @param int $uid Unique ID (max 65535)
     * @param array $data Binary fingerprint data array (where key is finger ID (0-9) same like returned array from 'getFingerprint' method)
     * @return int Count of added fingerprints
     */
    public function setFingerprint(int $uid, array $data): int
    {
        return Fingerprint::set($this, $uid, $data);
    }

    /**
     * Remove fingerprint by UID and fingers ID array
     *
     * @param int $uid Unique ID (max 65535)
     * @param array $data Fingers ID array (0-9)
     * @return int Count of deleted fingerprints
     */
    public function removeFingerprint(int $uid, array $data): int
    {
        return Fingerprint::remove($this, $uid, $data);
    }

    /**
     * Get attendance log
     *
     * @return array [uid, id, state, timestamp]
     */
    public function getAttendance(): array
    {
        return Attendance::get($this);
    }

    /**
     * Clear attendance log
     *
     * @return mixed
     */
    public function clearAttendance(): mixed
    {
        return Attendance::clear($this);
    }

    /**
     * Set device time
     *
     * @param string $t Format: "Y-m-d H:i:s"
     * @return mixed
     */
    public function setTime(string $t): mixed
    {
        return Time::set($this, $t);
    }

    /**
     * Get device time
     *
     * @return bool|string Format: "Y-m-d H:i:s"
     */
    public function getTime(): bool|string
    {
        return Time::get($this);
    }

    /**
     * turn off the device
     *
     * @return mixed
     */
    public function shutdown(): mixed
    {
        return Device::powerOff($this);
    }

    /**
     * restart the device
     *
     * @return mixed
     */
    public function restart(): mixed
    {
        return Device::restart($this);
    }

    /**
     * make sleep mood the device
     *
     * @return mixed
     */
    public function sleep(): mixed
    {
        return Device::sleep($this);
    }

    /**
     * resume the device from sleep
     *
     * @return mixed
     */
    public function resume(): mixed
    {
        return Device::resume($this);
    }

    /**
     * voice test Sound will "Thank you"
     *
     * @return mixed
     */
    public function testVoice(): mixed
    {
        return Device::testVoice($this);
    }

    /**
     * Clear LCD
     *
     * @return mixed
     */
    public function clearLCD(): mixed
    {
        return Device::clearLCD($this);
    }

    /**
     * Write LCD
     *
     * @return mixed
     */
    public function writeLCD(): mixed
    {
        return Device::writeLCD($this, 2, "RAIHAN Afroz Topu");
    }

    /**
     * This will refresh the machine interior data
     *
     * @return mixed
     */
    public function refreshData(): mixed
    {
        return Device::refreshData($this);
    }
}