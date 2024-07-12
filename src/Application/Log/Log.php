<?php

namespace App\Application\Log;

class Log
{
    private static function log(string $level, string $message): void
    {
        $file = fopen('log.txt', 'a');
        fwrite($file, "$level: $message" . PHP_EOL);
        fclose($file);
    }

    public static function info(string $message): void
    {
        self::log('INFO', $message);
    }

    public static function error(string $message): void
    {
        self::log('ERROR', $message);
    }
}