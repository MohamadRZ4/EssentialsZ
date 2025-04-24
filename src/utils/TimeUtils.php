<?php

namespace MohamadRZ\EssentialsZ\utils;

class TimeUtils
{
    public const TICKS_PER_SECOND = 20;
    public const SECONDS_PER_MINUTE = 60;
    public const MINUTES_PER_HOUR = 60;
    public const HOURS_PER_DAY = 24;
    public const DAYS_PER_MONTH = 30; // Approximation
    public const MONTHS_PER_YEAR = 12;

    /**
     * Converts ticks to seconds.
     */
    public static function ticksToSeconds(int $ticks): int {
        return (int) floor($ticks / self::TICKS_PER_SECOND);
    }

    /**
     * Converts seconds to ticks.
     */
    public static function secondsToTicks(int $seconds): int {
        return $seconds * self::TICKS_PER_SECOND;
    }

    /**
     * Converts seconds to a human-readable time string (e.g. 2h 5m 30s).
     */
    public static function secondsToReadable(int $seconds): string {
        $years = floor($seconds / (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR * self::HOURS_PER_DAY * self::DAYS_PER_MONTH * self::MONTHS_PER_YEAR));
        $seconds %= (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR * self::HOURS_PER_DAY * self::DAYS_PER_MONTH * self::MONTHS_PER_YEAR);

        $months = floor($seconds / (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR * self::HOURS_PER_DAY * self::DAYS_PER_MONTH));
        $seconds %= (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR * self::HOURS_PER_DAY * self::DAYS_PER_MONTH);

        $days = floor($seconds / (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR * self::HOURS_PER_DAY));
        $seconds %= (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR * self::HOURS_PER_DAY);

        $hours = floor($seconds / (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR));
        $seconds %= (self::SECONDS_PER_MINUTE * self::MINUTES_PER_HOUR);

        $minutes = floor($seconds / self::SECONDS_PER_MINUTE);
        $seconds %= self::SECONDS_PER_MINUTE;

        $parts = [];
        if ($years > 0) $parts[] = "{$years}y";
        if ($months > 0) $parts[] = "{$months}mo";
        if ($days > 0) $parts[] = "{$days}d";
        if ($hours > 0) $parts[] = "{$hours}h";
        if ($minutes > 0) $parts[] = "{$minutes}m";
        if ($seconds > 0) $parts[] = "{$seconds}s";

        return implode(" ", $parts) ?: "0s";
    }

    /**
     * Converts timestamp to formatted date string.
     */
    public static function timestampToDate(int $timestamp, string $format = "Y-m-d H:i:s"): string {
        return date($format, $timestamp);
    }

    /**
     * Converts date string to timestamp.
     */
    public static function dateToTimestamp(string $date): int {
        return strtotime($date);
    }

    /**
     * Gets current timestamp.
     */
    public static function getCurrentTimestamp(): int {
        return time();
    }

    /**
     * Gets the difference between two timestamps in a readable format.
     */
    public static function diffReadable(int $timestamp1, int $timestamp2): string {
        $diff = abs($timestamp1 - $timestamp2);
        return self::secondsToReadable($diff);
    }

    /**
     * Converts minutes to readable format.
     */
    public static function minutesToReadable(int $minutes): string {
        return self::secondsToReadable($minutes * self::SECONDS_PER_MINUTE);
    }

    /**
     * Converts readable time string (e.g. "1h 30m") to seconds.
     */
    public static function readableToSeconds(string $readable): int {
        preg_match_all('/(\d+)([a-z]+)/i', $readable, $matches);
        $seconds = 0;
        foreach ($matches[1] as $i => $value) {
            $unit = strtolower($matches[2][$i]);
            switch ($unit) {
                case "y": $seconds += $value * self::MONTHS_PER_YEAR * self::DAYS_PER_MONTH * self::HOURS_PER_DAY * self::MINUTES_PER_HOUR * self::SECONDS_PER_MINUTE; break;
                case "mo": $seconds += $value * self::DAYS_PER_MONTH * self::HOURS_PER_DAY * self::MINUTES_PER_HOUR * self::SECONDS_PER_MINUTE; break;
                case "d": $seconds += $value * self::HOURS_PER_DAY * self::MINUTES_PER_HOUR * self::SECONDS_PER_MINUTE; break;
                case "h": $seconds += $value * self::MINUTES_PER_HOUR * self::SECONDS_PER_MINUTE; break;
                case "m": $seconds += $value * self::SECONDS_PER_MINUTE; break;
                case "s": $seconds += $value; break;
            }
        }
        return $seconds;
    }
}
