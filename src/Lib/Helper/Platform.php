<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Platform
{
    /**
     * Get platform information
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function get(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~Platform';

        return $self->_command($command, $command_string);
    }

    /**
     * Get device version
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function getVersion(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~ZKFPVersion';

        return $self->_command($command, $command_string);
    }
}