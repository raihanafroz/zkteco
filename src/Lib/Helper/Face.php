<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Face
{
    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function on(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = 'FaceFunOn';

        return $self->_command($command, $command_string);
    }
}

