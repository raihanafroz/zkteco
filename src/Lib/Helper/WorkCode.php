<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class WorkCode
{
    /**
     * Get Work Code
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function get(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = 'WorkCode';

        return $self->_command($command, $command_string);
    }
}
