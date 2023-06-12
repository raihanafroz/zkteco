<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Device
{
    /**
     * This will showing the device name
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function name(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~DeviceName';

        return $self->_command($command, $command_string);
    }

    /**
     * This will enable the device
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function enable(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_ENABLE_DEVICE;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * This will disable the device
     *
     * @param ZKTeco $self
     * @return mixed
     */
    static public function disable(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DISABLE_DEVICE;
        $command_string = chr(0) . chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * This will turn off the device
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function powerOff(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_POWEROFF;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }


    /**
     * This will restart the device
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function restart(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_RESTART;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }


    /**
     * This will sleep the device
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function sleep(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_SLEEP;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }


    /**
     * This will resume the device from sleep
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function resume(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_RESUME;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }


    /**
     * This will play voice "Thank you"
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function testVoice(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_TESTVOICE;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }


    /**
     * This will clear the LCD screen
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function clearLCD(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_LCD;
        return $self->_command($command, '');
    }


    /**
     * @param ZKTeco $self
     * @param $rank *** Line number of text
     * @param $text *** Text which will display in the LCD screen
     * @return mixed *** this will write text into the LCD
     */
    public static function writeLCD(ZKTeco $self, $rank, $text): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_WRITE_LCD;
        $byte1 = chr((int)($rank % 256));
        $byte2 = chr((int)($rank >> 8));
        $byte3 = chr(0);
        $command_string = $byte1 . $byte2 . $byte3 . ' ' . $text;
        return $self->_command($command, $command_string);
    }

    /**
     * This will refresh the machine interior data
     *
     * @param ZKTeco $self
     * @return mixed
     */
    public static function refreshData(ZKTeco $self): mixed
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_REFRESH_DATA;
        return $self->_command($command, '');
    }
}