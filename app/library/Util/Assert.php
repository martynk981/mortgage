<?php

namespace App\Util;

/**
 * Static assert methods that should be used to assert method parameters and
 * return values
 *
 * The methods in this class either returns the object that is asserted or
 * throws an \InvalidArgumentException.
 *
 * @final
 *
 * @author Nickolay Martynenko <martynk981@gmail.com>
 */
final class Assert
{
    /**
     * Assert that a value is a integer
     *
     * @param mixed     $value      Value to test
     * @param int|null  $min        Min value for $value
     * @param int|null  $max        Max value for $value
     *
     * @throws \InvalidArgumentException  If $value isn't a integer
     * @throws \InvalidArgumentException  If $min or $max isn't null or integer
     * @throws \InvalidArgumentException  If $value isn't in the range $min - $max
     *
     * @return int $value
     */
    public static function isInteger($value, $min = null, $max = null)
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException("Not an integer:" . json_encode($value));
        }

        if ($min !== null) {
            if (!is_int($min)) {
                throw new \InvalidArgumentException("Parameter 'min' is not an int: " . json_encode($min));
            }
            if ($value < $min) {
                throw new \InvalidArgumentException("Integer smaller than '$min' : $value");
            }
        }
        if ($max !== null) {
            if (!is_int($max)) {
                throw new \InvalidArgumentException("Parameter 'max' is not an int: " . json_encode($max));
            }
            if ($value > $max) {
                throw new \InvalidArgumentException("Integer greater than '$max' : $value");
            }
        }

        return $value;
    }

    /**
     * Assert that a value is a float
     *
     * @param mixed             $value      Value to test
     * @param int|float|NULL    $min        Min value for $value
     * @param int|float|NULL    $max        Max value for $value
     *
     * @throws \InvalidArgumentException  If $value isn't a float
     * @throws \InvalidArgumentException  If $min or $max isn't null, float or integer
     * @throws \InvalidArgumentException  If $value isn't in the range $min - $max
     *
     * @return float $value
     */
    public static function isFloat($value, $min = null, $max = null)
    {
        if (!is_float($value)) {
            throw new \InvalidArgumentException("Not an float:" . json_encode($value));
        }

        if ($min !== null) {
            if (!(is_int($min) || is_float($min))) {
                throw new \InvalidArgumentException("Parameter 'min' is not an int or float: " . json_encode($min));
            }
            if ($value < $min) {
                throw new \InvalidArgumentException("Float smaller than '$min' : $value");
            }
        }
        if ($max !== null) {
            if (!(is_int($max) || is_float($max))) {
                throw new \InvalidArgumentException("Parameter 'max' is not an int or float : " . json_encode($max));
            }
            if ($value > $max) {
                throw new \InvalidArgumentException("Float greater than '$max' : $value");
            }
        }

        return $value;
    }
}