<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;

use \DateTime;
use \DateTimeZone;

/**
 * Class DateTimeTrait
 */
class DateTimeUtility
{

    /**
     * Parse and validate the $dt argument
     *
     * Optionally return the validated datetime object or true if $dt is valid
     * Optionally return the errors array or false if $dt is invalid
     *
     * @param string|int|DateTime $dt
     * @param bool                $returnDateTime
     * @param bool                $returnErrors
     *
     * @return bool|DateTime
     */
    public static function getDateTime($dt, bool $returnDateTime = true, bool $returnErrors = false)
    {
        $dtParsed = self::parseDateTime($dt);

        return (
            (!self::isParsedDateTimeValid($dtParsed, true))
                ? (($returnErrors) ? $dtParsed['errors'] : false)
                : (($returnDateTime) ? $dtParsed['datetime'] : true)
        );
    }

    /**
     * @return string
     */
    public static function mysqlDateTimeFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @return string
     */
    public static function getZeroDateTimeString()
    {
        return '0000-00-00 00:00:00';
    }

    /**
     * @return DateTime
     */
    public static function getZeroDateTime()
    {
        return DateTime::createFromFormat(self::mysqlDateTimeFormat(), self::getZeroDateTimeString());;
    }

    /**
     * @param string|DateTime $dt
     *
     * @return bool|null
     */
    public static function isZeroDateTime($dt)
    {
        $dtParsed = self::parseDateTime($dt);

        if (!self::isParsedDateTimeValid($dtParsed, false)) {
            return null;
        }

        return (self::getZeroDateTime() == $dtParsed['datetime']) ? true : false;
    }

    /**
     * @param string $timezone
     *
     * @return bool
     */
    public static function isValidTimezone(string $timezone)
    {
        return (in_array($timezone, DateTimeZone::listIdentifiers())) ? true : false;
    }

    /**
     * Check if the $dt (date) value is smaller than today,
     * e.g. yesterday
     *
     * @param string|DateTime $dt
     *
     * @return bool|null
     */
    public static function isOlderThanToday($dt)
    {
        $dtParsed = self::parseDateTime($dt);
        if (!($dtParsed['datetime'] instanceof DateTime)) {
            return null;
        }

        $today = new DateTime();

        return ($dtParsed['datetime']->format('Y-m-d') < $today->format('Y-m-d')) ? true : false;
    }

    /**
     * Parse and validate datetime string or datetime object
     * Here is just a taste of what the php datetime parser understands:
     * - seconds
     *      "-10"
     * - minutes
     *      "+15 m"
     *      "+15 min"
     *      "+15 minutes"
     * - hours
     *      "-2 hours"
     * - days
     *      "+ 3 day"
     *      "+ 3 days"
     * - weeks
     *      "+ 1 week"
     *      "monday - 1 week"
     * - months
     *      "+ 1 MONTH"
     *      "+ 1 MONTHs"
     * - years
     *      "- 20 Years"
     *
     * For more information visit the docs
     * @link http://php.net/manual/en/datetime.formats.php
     *
     * @param string|int|DateTime $dt
     *
     * @return DateTime|bool|array
     */
    private static function parseDateTime($dt)
    {
        $datetime = null;
        $errors   = [
            'warning_count' => 0,
            'warnings'      => [],
            'error_count'   => 0,
            'errors'        => []
        ];

        if (is_string($dt) || is_numeric($dt)) {
            try {
                $datetime = new DateTime($dt);
                $errors = DateTime::getLastErrors();
                if (!($datetime instanceof DateTime) && is_numeric($dt)) {
                    $datetime = DateTime::createFromFormat(self::mysqlDateTimeFormat(), date(self::mysqlDateTimeFormat(), $dt));
                    $errors = DateTime::getLastErrors();
                }
            }
            catch (\Exception $e) {
                if (is_numeric($dt)) {
                    $datetime = DateTime::createFromFormat(self::mysqlDateTimeFormat(), date(self::mysqlDateTimeFormat(), $dt));
                    $errors = DateTime::getLastErrors();
                }
                else {
                    $errors['error_count']++;
                    $errors['errors'][] = $e->getMessage();
                }
            }
        }
        elseif ($dt instanceof DateTime) {
            $datetime = DateTime::createFromFormat(self::mysqlDateTimeFormat(), $dt->format(self::mysqlDateTimeFormat()));
            $errors = DateTime::getLastErrors();

            if ((0 < $errors['warning_count'] || 0 < $errors['error_count']) || !($datetime instanceof DateTime)) {
                $datetime = new DateTime($dt->format(self::mysqlDateTimeFormat()));
                $errors = DateTime::getLastErrors();
            }
        }

        // test some edge cases
        if ($datetime instanceof DateTime) {
            if (!self::isValidTimezone($datetime->getTimezone()->getName())) {
                // valid DateTime $dt string value with invalid timezone, examples:
                // "x0000-01-01 00:00:00"
                // "0000-01-01 00:00:00y"
                // "x0000-01-01 00:00:00y"
                $errors['error_count']++;
                $errors['errors'][] = "invalid timezone";
            }
        }

        return [
            'errors'   => $errors,
            'datetime' => $datetime
        ];
    }

    /**
     * @param array $dtParsed
     * @param bool  $strict
     *
     * @return bool
     */
    private static function isParsedDateTimeValid(array $dtParsed, bool $strict = true)
    {
        $isDateTime          = ($dtParsed['datetime'] instanceof DateTime);
        $hasErrorsOrWarnings = (0 < $dtParsed['errors']['warning_count'] || 0 < $dtParsed['errors']['error_count']);

        return ($isDateTime && (($strict && !$hasErrorsOrWarnings) || !$strict)) ? true : false;
    }
}