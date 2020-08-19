<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class User
{
    /**
     * @param ZKTeco $self
     * @param int $uid Unique ID (max 65535)
     * @param int|string $userid (max length = 9, only numbers - depends device setting)
     * @param string $name (max length = 24)
     * @param int|string $password (max length = 8, only numbers - depends device setting)
     * @param int $role Default Util::LEVEL_USER
     * @param int $cardno Default 0 (max length = 10, only numbers)
     * @return bool|mixed
     */
    static public function set(ZKTeco $self, $uid, $userid, $name, $password, $role = Util::LEVEL_USER, $cardno = 0)
    {
        $self->_section = __METHOD__;

        if (
            (int)$uid === 0 ||
            (int)$uid > Util::USHRT_MAX ||
            strlen($userid) > 9 ||
            strlen($name) > 24 ||
            strlen($password) > 8 ||
            strlen($cardno) > 10
        ) {
            return false;
        }

        $command = Util::CMD_SET_USER;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $cardno = hex2bin(Util::reverseHex(dechex($cardno)));

        $command_string = implode('', [
            $byte1,
            $byte2,
            chr($role),
            str_pad($password, 8, chr(0)),
            str_pad($name, 24, chr(0)),
            str_pad($cardno, 4, chr(0)),
            str_pad(chr(1), 9, chr(0)),
            str_pad($userid, 9, chr(0)),
            str_repeat(chr(0), 15)
        ]);
//        die($command_string);
        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @return array [userid, name, cardno, uid, role, password]
     */
    static public function get(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_USER_TEMP_RRQ;
        $command_string = chr(Util::FCT_USER);

        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return [];
        }

        $userData = Util::recData($self);

        $users = [];
        if (!empty($userData)) {
            $userData = substr($userData, 11);

            while (strlen($userData) > 72) {
                $u = unpack('H144', substr($userData, 0, 72));

                $u1 = hexdec(substr($u[1], 2, 2));
                $u2 = hexdec(substr($u[1], 4, 2));
                $uid = $u1 + ($u2 * 256);
                $cardno = hexdec(substr($u[1], 78, 2) . substr($u[1], 76, 2) . substr($u[1], 74, 2) . substr($u[1], 72, 2)) . ' ';
                $role = hexdec(substr($u[1], 6, 2)) . ' ';
                $password = hex2bin(substr($u[1], 8, 16)) . ' ';
                $name = hex2bin(substr($u[1], 24, 74)) . ' ';
                $userid = hex2bin(substr($u[1], 98, 72)) . ' ';

                //Clean up some messy characters from the user name
                $password = explode(chr(0), $password, 2);
                $password = $password[0];
                $userid = explode(chr(0), $userid, 2);
                $userid = $userid[0];
                $name = explode(chr(0), $name, 3);
                $name = utf8_encode($name[0]);
                $cardno = str_pad($cardno, 11, '0', STR_PAD_LEFT);

                if ($name == '') {
                    $name = $userid;
                }

                $users[$userid] = [
                    'uid' => $uid,
                    'userid' => $userid,
                    'name' => $name,
                    'role' => intval($role),
                    'password' => $password,
                    'cardno' => $cardno,
                ];

                $userData = substr($userData, 72);
            }
        }

        return $users;
    }

    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function clear(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_DATA;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function clearAdmin(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_ADMIN;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @param integer $uid
     * @return bool|mixed
     */
    static public function remove(ZKTeco $self, $uid)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DELETE_USER;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $command_string = ($byte1 . $byte2);

        return $self->_command($command, $command_string);
    }
}