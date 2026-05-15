<?php

namespace Api\Helpers;

class Date
{
    /**
     * Переводчит строковую дату в таймстапм с миллисекундами
     * @param $dateString
     * @return false|int|string
     */
    public static function getTimestampWithMilliseconds($dateString)
    {
        $timestamp = strtotime($dateString);
        $timestamp = $timestamp . '000';
        $timestamp = (int) $timestamp;
        return $timestamp;
    }

    /**
     * ПОлучает текущее время таймстамп с миллисекундами
     * @return int
     */
    public static function getNowMilliseconds()
    {
        $microtime = microtime(true);
        $parts = explode('.', $microtime);
        $milliTime = $parts[0] . substr($parts[1], 0, 3);
        return (int) $milliTime;
    }
}