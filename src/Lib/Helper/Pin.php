<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Pin
{
    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function width(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~PIN2Width';

        return $self->_command($command, $command_string);
    }
}