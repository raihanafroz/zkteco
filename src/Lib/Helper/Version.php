<?php

namespace Rats\Zkteco\Lib\Helper;;

use Rats\Zkteco\Lib\Helper\Util;
use Rats\Zkteco\Lib\ZKTeco;

class Version
{
    /**
     * Get Machine Version
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function get(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_VERSION;
        $command_string = '';

        return $self->_command($command, $command_string);
    }
}