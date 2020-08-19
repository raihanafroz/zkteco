<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Platform
{
    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function get(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~Platform';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function getVersion(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~ZKFPVersion';

        return $self->_command($command, $command_string);
    }
}