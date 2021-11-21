<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Util
{
  const USHRT_MAX = 65535;

  const CMD_CONNECT = 1000; # Connections requests
  const CMD_EXIT = 1001; # Disconnection requests
  const CMD_ENABLE_DEVICE = 1002; # Ensure the machine to be at the normal work condition
  const CMD_DISABLE_DEVICE = 1003; # Make the machine to be at the shut-down condition, generally demonstrates ‘in the work ...’on LCD

  const CMD_RESTART = 1004; # Restart the machine
  const CMD_POWEROFF = 1005; # Turn Off the machine
  const CMD_SLEEP = 1006; # Sleep the machine
  const CMD_RESUME = 1007; # Resume the machine from Sleep
  const CMD_TEST_TEMP = 1011;
  const CMD_TESTVOICE = 1017; # Voice test to the device
  const CMD_CHANGE_SPEED = 1101;


  const CMD_WRITE_LCD = 66; # Write in LCD
  const CMD_CLEAR_LCD = 67; # Clear LCD

  const CMD_ACK_OK = 2000; # Return value for order perform successfully
  const CMD_ACK_ERROR = 2001; # Return value for order perform failed
  const CMD_ACK_DATA = 2002; # Return data
  const CMD_ACK_UNAUTH = 2005; # Connection unauthorized

  const CMD_PREPARE_DATA = 1500; # Prepares to transmit the data
  const CMD_DATA = 1501; # Transmit a data packet
  const CMD_FREE_DATA = 1502; # Clear machines open buffer

  const CMD_USER_TEMP_RRQ = 9; # Read some fingerprint template or some kind of data entirely
  const CMD_ATT_LOG_RRQ = 13; # Read all attendance record
  const CMD_CLEAR_DATA = 14; # Clear Data
  const CMD_CLEAR_ATT_LOG = 15; # Clear attendance records

  const CMD_GET_TIME = 201; # Obtain the machine time
  const CMD_SET_TIME = 202; # Set machines time

  const CMD_VERSION = 1100; # Obtain the firmware edition
  const CMD_DEVICE = 11; # Read in the machine some configuration parameter

  const CMD_SET_USER = 8; # Upload the user information (from PC to terminal).
  const CMD_USER_TEMP_WRQ = 10; # Upload some fingerprint template
  const CMD_DELETE_USER = 18; # Delete some user
  const CMD_DELETE_USER_TEMP = 19; # Delete some fingerprint template
  const CMD_CLEAR_ADMIN = 20; # Cancel the manager

  const LEVEL_USER = 0; # User level as User
  const LEVEL_ADMIN = 14; # User level as Admin

  const FCT_ATTLOG = 1;
  const FCT_WORKCODE = 8;
  const FCT_FINGERTMP = 2;
  const FCT_OPLOG = 4;
  const FCT_USER = 5;
  const FCT_SMS = 6;
  const FCT_UDATA = 7;

  const COMMAND_TYPE_GENERAL = 'general';
  const COMMAND_TYPE_DATA = 'data';

  const ATT_STATE_FINGERPRINT = 1;
  const ATT_STATE_PASSWORD = 0;
  const ATT_STATE_CARD = 2;

  const ATT_TYPE_CHECK_IN = 0;
  const ATT_TYPE_CHECK_OUT = 1;
  const ATT_TYPE_OVERTIME_IN = 4;
  const ATT_TYPE_OVERTIME_OUT = 5;

  /**
   * Encode a timestamp send at the timeclock
   * copied from zkemsdk.c - EncodeTime
   *
   * @param string $t Format: "Y-m-d H:i:s"
   * @return int
   */
  static public function encodeTime($t)
  {
    $timestamp = strtotime($t);
    $t = (object)[
      'year' => (int)date('Y', $timestamp),
      'month' => (int)date('m', $timestamp),
      'day' => (int)date('d', $timestamp),
      'hour' => (int)date('H', $timestamp),
      'minute' => (int)date('i', $timestamp),
      'second' => (int)date('s', $timestamp),
    ];

    $d = (($t->year % 100) * 12 * 31 + (($t->month - 1) * 31) + $t->day - 1) *
      (24 * 60 * 60) + ($t->hour * 60 + $t->minute) * 60 + $t->second;

    return $d;
  }

  /**
   * Decode a timestamp retrieved from the timeclock
   * copied from zkemsdk.c - DecodeTime
   *
   * @param int|string $t
   * @return false|string Format: "Y-m-d H:i:s"
   */
  static public function decodeTime($t)
  {
    $second = $t % 60;
    $t = $t / 60;

    $minute = $t % 60;
    $t = $t / 60;

    $hour = $t % 24;
    $t = $t / 24;

    $day = $t % 31 + 1;
    $t = $t / 31;

    $month = $t % 12 + 1;
    $t = $t / 12;

    $year = floor($t + 2000);

    $d = date('Y-m-d H:i:s', strtotime(
      $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second
    ));

    return $d;
  }

  /**
   * @param string $hex
   * @return string
   */
  static public function reverseHex($hex)
  {
    $tmp = '';

    for ($i = strlen($hex); $i >= 0; $i--) {
      $tmp .= substr($hex, $i, 2);
      $i--;
    }

    return $tmp;
  }

  /**
   * Checks a returned packet to see if it returned self::CMD_PREPARE_DATA,
   * indicating that data packets are to be sent
   * Returns the amount of bytes that are going to be sent
   *
   * @param ZKTeco $self
   * @return bool|number
   */
  static public function getSize(ZKTeco $self)
  {
    $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($self->_data_recv, 0, 8));
    $command = hexdec($u['h2'] . $u['h1']);

    if ($command == self::CMD_PREPARE_DATA) {
      $u = unpack('H2h1/H2h2/H2h3/H2h4', substr($self->_data_recv, 8, 4));
      $size = hexdec($u['h4'] . $u['h3'] . $u['h2'] . $u['h1']);
      return $size;
    } else {
      return false;
    }
  }

  /**
   * This function calculates the chksum of the packet to be sent to the
   * time clock
   * Copied from zkemsdk.c
   *
   * @inheritdoc
   */
  static public function createChkSum($p)
  {
    $l = count($p);
    $chksum = 0;
    $i = $l;
    $j = 1;
    while ($i > 1) {
      $u = unpack('S', pack('C2', $p['c' . $j], $p['c' . ($j + 1)]));

      $chksum += $u[1];

      if ($chksum > self::USHRT_MAX) {
        $chksum -= self::USHRT_MAX;
      }
      $i -= 2;
      $j += 2;
    }

    if ($i) {
      $chksum = $chksum + $p['c' . strval(count($p))];
    }

    while ($chksum > self::USHRT_MAX) {
      $chksum -= self::USHRT_MAX;
    }

    if ($chksum > 0) {
      $chksum = -($chksum);
    } else {
      $chksum = abs($chksum);
    }

    $chksum -= 1;
    while ($chksum < 0) {
      $chksum += self::USHRT_MAX;
    }

    return pack('S', $chksum);
  }

  /**
   * This function puts a the parts that make up a packet together and
   * packs them into a byte string
   *
   * @inheritdoc
   */
  static public function createHeader($command, $chksum, $session_id, $reply_id, $command_string)
  {
    $buf = pack('SSSS', $command, $chksum, $session_id, $reply_id) . $command_string;

    $buf = unpack('C' . (8 + strlen($command_string)) . 'c', $buf);

    $u = unpack('S', self::createChkSum($buf));

    if (is_array($u)) {
      $u = reset($u);
    }
    $chksum = $u;

    $reply_id += 1;

    if ($reply_id >= self::USHRT_MAX) {
      $reply_id -= self::USHRT_MAX;
    }

    $buf = pack('SSSS', $command, $chksum, $session_id, $reply_id);

    return $buf . $command_string;

  }

  /**
   * Checks a returned packet to see if it returned Util::CMD_ACK_OK,
   * indicating success
   *
   * @inheritdoc
   */
  static public function checkValid($reply)
  {
    $u = unpack('H2h1/H2h2', substr($reply, 0, 8));

    $command = hexdec($u['h2'] . $u['h1']);
    /** TODO: Some device can return 'Connection unauthorized' then should check also */
    if ($command == self::CMD_ACK_OK || $command == self::CMD_ACK_UNAUTH) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Get User Role string
   * @param integer $role
   * @return string
   */
  static public function getUserRole($role)
  {
    switch ($role) {
      case self::LEVEL_USER:
        $ret = 'User';
        break;
      case self::LEVEL_ADMIN:
        $ret = 'Admin';
        break;
      default:
        $ret = 'Unknown';
    }

    return $ret;
  }

  /**
   * Get Attendance State string
   * @param integer $state
   * @return string
   */
  static public function getAttState($state)
  {
    switch ($state) {
      case self::ATT_STATE_FINGERPRINT:
        $ret = 'Fingerprint';
        break;
      case self::ATT_STATE_PASSWORD:
        $ret = 'Password';
        break;
      case self::ATT_STATE_CARD:
        $ret = 'Card';
        break;
      default:
        $ret = 'Unknown';
    }

    return $ret;
  }

  /**
   * Get Attendance Type string
   * @param integer $type
   * @return string
   */
  static public function getAttType($type)
  {
    switch ($type) {
      case self::ATT_TYPE_CHECK_IN:
        $ret = 'Check-in';
        break;
      case self::ATT_TYPE_CHECK_OUT:
        $ret = 'Check-out';
        break;
      case self::ATT_TYPE_OVERTIME_IN:
        $ret = 'Overtime-in';
        break;
      case self::ATT_TYPE_OVERTIME_OUT:
        $ret = 'Overtime-out';
        break;
      default:
        $ret = 'Undefined';
    }

    return $ret;
  }

  /**
   * Receive data from device
   * @param ZKTeco $self
   * @param int $maxErrors
   * @param bool $first if 'true' don't remove first 4 bytes for first row
   * @return string
   */
  static public function recData(ZKTeco $self, $maxErrors = 10, $first = true)
  {
    $data = '';
    $bytes = self::getSize($self);

    if ($bytes) {
      $received = 0;
      $errors = 0;

      while ($bytes > $received) {
        $ret = @socket_recvfrom($self->_zkclient, $dataRec, 1032, 0, $self->_ip, $self->_port);

        if ($ret === false) {
          if ($errors < $maxErrors) {
            //try again if false
            $errors++;
            sleep(1);
            continue;
          } else {
            //return empty if has maximum count of errors
            self::logReceived($self, $received, $bytes);
            unset($data);
            return '';
          }
        }

        if ($first === false) {
          //The first 4 bytes don't seem to be related to the user
          $dataRec = substr($dataRec, 8);
        }

        $data .= $dataRec;
        $received += strlen($dataRec);

        unset($dataRec);
        $first = false;
      }

      //flush socket
      @socket_recvfrom($self->_zkclient, $dataRec, 1024, 0, $self->_ip, $self->_port);
      unset($dataRec);
    }

    return $data;
  }

  /**
   * @param ZKTeco $self
   * @param int $received
   * @param int $bytes
   */
  static private function logReceived(ZKTeco $self, $received, $bytes)
  {
    self::logger($self, 'Received: ' . $received . ' of ' . $bytes . ' bytes');
  }

  /**
   * Write log
   * @param ZKTeco $self
   * @param string $str
   */
  static private function logger(ZKTeco $self, $str)
  {
    if (defined('ZK_LIB_LOG')) {
      //use constant if defined
      $log = ZK_LIB_LOG;
    } else {
      $dir = dirname(dirname(__FILE__));
      $log = $dir . '/logs/error.log';
    }

    $row = '<' . $self->_ip . '> [' . date('d.m.Y H:i:s') . '] ';
    $row .= (empty($self->_section) ? '' : '(' . $self->_section . ') ');
    $row .= $str;
    $row .= PHP_EOL;

    file_put_contents($log, $row, FILE_APPEND);
  }
}
