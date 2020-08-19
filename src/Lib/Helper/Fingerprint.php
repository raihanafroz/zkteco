<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Fingerprint
{
    /**
     * TODO: Can get data, but don't know how to parse the data. Need more documentation about it...
     *
     * @param ZKTeco $self
     * @param integer $uid Unique Employee ID in ZK device
     * @return array Binary fingerprint data array (where key is finger ID (0-9))
     */
    static public function get(ZKTeco $self, $uid)
    {
        $self->_section = __METHOD__;

        $data = [];
        //fingers of the hands
        for ($i = 0; $i <= 9; $i++) {
          $finger = new Fingerprint();
            $tmp = $finger->_getFinger($self, $uid, $i);
            if ($tmp['size'] > 0) {
                $data[$i] = $tmp['tpl'];
            }
            unset($tmp);
        }
        return $data;
    }


    /**
     * @param ZKTeco $self
     * @param integer $uid Unique Employee ID in ZK device
     * @param integer $finger Finger ID (0-9)
     * @return array
     */
    private function _getFinger(ZKTeco $self, $uid, $finger)
    {
        $command = Util::CMD_USER_TEMP_RRQ;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $command_string = $byte1 . $byte2 . chr($finger);

        $ret = [
            'size' => 0,
            'tpl' => ''
        ];

        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return $ret;
        }

        $data = Util::recData($self, 10, false);

        if (!empty($data)) {
            $templateSize = strlen($data);
            $prefix = chr($templateSize % 256) . chr(round($templateSize / 256)) . $byte1 . $byte2 . chr($finger) . chr(1);
            $data = $prefix . $data;
            if (strlen($templateSize) > 0) {
                $ret['size'] = $templateSize;
                $ret['tpl'] = $data;
            }
        }

        return $ret;
    }

    /**
     * TODO: Still can not set fingerprint. Need more documentation about it...
     *
     * @param ZKTeco $self
     * @param int $uid Unique Employee ID in ZK device
     * @param array $data Binary fingerprint data array (where key is finger ID (0-9) same like returned array from 'get' method)
     * @return int Count of added fingerprints
     */
    static public function set(ZKTeco $self, $uid, array $data)
    {
        $self->_section = __METHOD__;


        $count = 0;
        foreach ($data as $finger => $item) {
            $allowSet = true;
            $fingerPrint = new Fingerprint();
            if ($fingerPrint->_checkFinger($self, $uid, $finger) === true) {
                $allowSet = $fingerPrint->_removeFinger($self, $uid, $finger);
            }
            if ($allowSet === true && $fingerPrint->_setFinger($self, $item) === true) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param ZKTeco $self
     * @param string $data Binary fingerprint data item
     * @return bool|mixed
     */
    private function _setFinger(ZKTeco $self, $data)
    {
        $command = Util::CMD_USER_TEMP_WRQ;
        $command_string = $data;

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @param int $uid Unique Employee ID in ZK device
     * @param array $data Fingers ID array (0-9)
     * @return int Count of deleted fingerprints
     */
    static public function remove(ZKTeco $self, $uid, array $data)
    {
        $self->_section = __METHOD__;

        $count = 0;
        foreach ($data as $finger) {
          $fingerPrint = new Fingerprint();
            if ($fingerPrint->_checkFinger($self, $uid, $finger) === true) {
                if ($fingerPrint->_removeFinger($self, $uid, $finger) === true) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * @param ZKTeco $self
     * @param int $uid Unique Employee ID in ZK device
     * @param int $finger Finger ID (0-9)
     * @return bool
     */
    private function _removeFinger(ZKTeco $self, $uid, $finger)
    {
        $command = Util::CMD_DELETE_USER_TEMP;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $command_string = ($byte1 . $byte2) . chr($finger);

        $self->_command($command, $command_string);
        $fingerPrint = new Fingerprint();
        return !($fingerPrint->_checkFinger($self, $uid, $finger));
    }

    /**
     * @param ZKTeco $self
     * @param int $uid Unique Employee ID in ZK device
     * @param int $finger Finger ID (0-9)
     * @return bool Returned true if exist
     */
    private function _checkFinger(ZKTeco $self, $uid, $finger)
    {
      $fingerPrint = new Fingerprint();
        $res = $fingerPrint->_getFinger($self, $uid, $finger);
        return (bool)($res['size'] > 0);
    }
}