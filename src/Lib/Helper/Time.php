<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Time
{
    /**
     * Set time
     *
     * @param ZKTeco $self
     * @param string $t Format: "Y-m-d H:i:s"
     * @return mixed
     */
    static public function set(ZKTeco $self, string $t): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_SET_TIME;
        $command_string = pack('I', Util::encodeTime($t));

        return $self->_command($command, $command_string);
    }

    /**
     * Get time
     *
     * @param ZKTeco $self
     * @return bool|string
     */
    static public function get(ZKTeco $self): bool|string
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_GET_TIME;
        $command_string = '';

        $ret = $self->_command($command, $command_string);

        if ($ret) {
            return Util::decodeTime(hexdec(Util::reverseHex(bin2hex($ret))));
        } else {
            return false;
        }
    }
}