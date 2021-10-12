<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 20.3.17
 * Time: 0:00
 */

namespace Dravencms;


use Tracy\Debugger AS TDebugger;

final class Debugger extends TDebugger
{
    public static $lastUsage = 0;

    public static function memoryPoint($name = null): void
    {
        $usage = memory_get_usage();

        $convert = function ($size)
        {
            $unit = ['b','kb','mb','gb','tb','pb'];
            return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        };

        self::barDump(($name ? $name.': ' : '').$convert($usage - self::$lastUsage));
        self::$lastUsage = $usage;
    }
}