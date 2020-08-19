<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Device
{
    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function name(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~DeviceName';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function enable(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_ENABLE_DEVICE;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function disable(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DISABLE_DEVICE;
        $command_string = chr(0) . chr(0);

        return $self->_command($command, $command_string);
    }
}