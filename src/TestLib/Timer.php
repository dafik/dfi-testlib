<?php
/**
 * Created by IntelliJ IDEA.
 * User: z.wieczorek
 * Date: 20.10.15
 * Time: 12:28
 */

namespace TestLib;

class Timer
{

    /**
     * @var array
     */
    private static $times = array(
        'hour' => 3600000,
        'minute' => 60000,
        'second' => 1000
    );

    /**
     * @var array
     */
    private static $startTimes = array();

    /**
     * @var float
     */
    public static $requestTime;

    /**
     * Starts the timer.
     */
    public static function start()
    {
        self::$startTimes[] = microtime(true);
    }

    /**
     * Stops the timer and returns the elapsed time.
     *
     * @return float
     */
    public static function stop()
    {
        return microtime(true) - array_pop(self::$startTimes);
    }

    /**
     * Formats the elapsed time as a string.
     *
     * @param  float $time
     * @return string
     */
    public static function secondsToTimeString($time)
    {
        $ms = round($time * 1000);

        foreach (self::$times as $unit => $value) {
            if ($ms >= $value) {
                $time = floor($ms / $value * 100.0) / 100.0;

                return $time . ' ' . ($time === 1 ? $unit : $unit . 's');
            }
        }

        return $ms . ' ms';
    }

    /**
     * Formats the elapsed time since the start of the request as a string.
     *
     * @return string
     */
    public static function timeSinceStartOfRequest()
    {
        return self::secondsToTimeString(microtime(true) - self::$requestTime);
    }

    /**
     * Returns the resources (time, memory) of the request as a string.
     *
     * @return string
     */
    public static function resourceUsage()
    {
        return sprintf(
            'Time: %s, Memory: %4.2fMb',
            self::timeSinceStartOfRequest(),
            memory_get_peak_usage(true) / 1048576
        );
    }
}