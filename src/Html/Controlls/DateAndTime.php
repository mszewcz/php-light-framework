<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Html\Controlls;

use MS\LightFramework\Html\Tags;

/**
 * Class DateAndTime
 *
 * @package MS\LightFramework\Html\Controlls
 */
final class DateAndTime extends AbstractControlls
{
    /**
     * Returns years array
     *
     * @return array
     */
    private static function getYears(): array
    {
        $years = [-1 => static::getConstant('TXT_DATETIME_YYYY', 'TXT_DATETIME_YYYY')];
        for ($i = 1978; $i <= 2037; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    /**
     * Returns months array
     *
     * @return array
     */
    private static function getMonths(): array
    {
        $months = [-1 => static::getConstant('TXT_DATETIME_MMM', 'TXT_DATETIME_MMM')];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = static::getConstant('TXT_DATETIME_MONTH_'.$i.'_SHORT', 'TXT_DATETIME_MONTH_'.$i.'_SHORT');
        }
        return $months;
    }

    /**
     * Returns days array
     *
     * @param int $maxDays
     * @return array
     */
    private static function getDays(int $maxDays = 31): array
    {
        $days = [-1 => static::getConstant('TXT_DATETIME_DD', 'TXT_DATETIME_DD')];
        for ($i = 1; $i <= $maxDays; $i++) {
            $days[$i] = \sprintf('%02s', $i);
        }
        return $days;
    }

    /**
     * Returns hours array
     *
     * @return array
     */
    private static function getHours(): array
    {
        $hours = [-1 => static::getConstant('TXT_DATETIME_HH', 'TXT_DATETIME_HH')];
        for ($i = 0; $i <= 23; $i++) {
            $hours[$i] = \sprintf('%02s', $i);
        }
        return $hours;
    }

    /**
     * Returns minutes array
     *
     * @return array
     */
    private static function getMinutes(): array
    {
        $minutes = [-1 => static::getConstant('TXT_DATETIME_II', 'TXT_DATETIME_II')];
        for ($i = 0; $i <= 59; $i++) {
            $minutes[$i] = \sprintf('%02s', $i);
        }
        return $minutes;
    }

    /**
     * Returns seconds array
     *
     * @return array
     */
    private static function getSeconds(): array
    {
        $seconds = [-1 => static::getConstant('TXT_DATETIME_SS', 'TXT_DATETIME_SS')];
        for ($i = 0; $i <= 59; $i++) {
            $seconds[$i] = \sprintf('%02s', $i);
        }
        return $seconds;
    }

    /**
     * Returns timestamp controll (date & time inputs)
     *
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     * @return string
     */
    public static function inputTimestamp(string $name = '', $value = 0, array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'time-controll' => true];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $timeControll = $userAttributes['time-controll'];
        $isEnabled = !isset($userAttributes['disabled']);
        $userAttributes = static::clearAttributes($userAttributes, ['time-controll']);
        $timezone = \date_default_timezone_get();

        $nameDate = \sprintf('%s_date', $name);
        $nameTime = \sprintf('%s_time', $name);
        $valDate = $valTime = '';

        if (\is_array($value)) {
            $valDate = $value['date'];
            $valTime = $value['time'];
        } elseif ($value != 0) {
            $valDate = (new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('Y-m-d');
            $valTime = (new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('H:i:s');
        }

        $userAttributes['id'] = $nameDate;
        $userAttributes['class'] = 'date';
        $inputDate = Text::inputText($nameDate, $valDate, $userAttributes);

        $userAttributes['id'] = $nameTime;
        $userAttributes['class'] = 'time';
        $inputTime = Text::inputHidden($nameTime, '00:00:00', $userAttributes);

        if ($timeControll === true) {
            $inputTime = Text::inputText($nameTime, $valTime, $userAttributes);
        }

        $btnTitle = static::getConstant(
            'TXT_PANEL_DATA_FORM_TIMESTAMP_CONTROLL_RESET',
            'TXT_PANEL_DATA_FORM_TIMESTAMP_CONTROLL_RESET'
        );
        $resetBtn = $isEnabled ? Tags::span('', ['class' => 'timestamp-reset', 'title' => $btnTitle]) : '';
        $controll = $inputDate.$inputTime.$resetBtn;

        return Tags::div($controll, ['class' => 'timestamp-controll cf']);
    }

    /**
     * Returns timestamp select controlls
     *
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     * @return string
     */
    public static function selectTimestamp(string $name = '', $value = 0, array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'time-controll' => true];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $timeControll = $userAttributes['time-controll'];
        $isEnabled = !isset($userAttributes['disabled']);
        $userAttributes = static::clearAttributes($userAttributes, ['time-controll']);
        $timezone = \date_default_timezone_get();

        $nameY = \sprintf('%s_y', $name);
        $nameM = \sprintf('%s_m', $name);
        $nameD = \sprintf('%s_d', $name);
        $nameH = \sprintf('%s_h', $name);
        $nameI = \sprintf('%s_i', $name);
        $nameS = \sprintf('%s_s', $name);
        $maxD = 31;

        $valY = $valM = $valD = $valH = $valI = $valS = -1;

        if (\is_array($value)) {
            $valY = $value['y'];
            $valM = $value['m'];
            $valD = $value['d'];
            $valH = \intval(0 + $value['h']);
            $valI = \intval(0 + $value['i']);
            $valS = \intval(0 + $value['s']);
        } elseif ($value != 0) {
            $valY = (new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('Y');
            $valM = (new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('n');
            $valD = (new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('j');
            $valH = \intval((new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('H'));
            $valI = \intval((new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('i'));
            $valS = \intval((new \DateTime('@'.$value))->setTimezone(new \DateTimeZone($timezone))->format('s'));
        }

        if ($valY > 0 && $valM > 0) {
            $format = \sprintf('%04s-%02s-01 00:00:00', $valY, $valM);
            $maxD = (int)(new \DateTime($format))->format('t');
        }

        $userAttributes['id'] = $nameY;
        $userAttributes['wrapper-class'] = 'year';
        $selectY = Choice::select($nameY, static::getYears(), [$valY], $userAttributes);

        $userAttributes['id'] = $nameM;
        $userAttributes['wrapper-class'] = 'month';
        $selectM = Choice::select($nameM, static::getMonths(), [$valM], $userAttributes);

        $userAttributes['id'] = $nameD;
        $userAttributes['wrapper-class'] = 'day';
        $selectD = Choice::select($nameD, static::getDays($maxD), [$valD], $userAttributes);

        $selectH = Text::inputHidden($nameH, '00', $userAttributes);
        $selectI = Text::inputHidden($nameI, '00', $userAttributes);
        $selectS = Text::inputHidden($nameS, '00', $userAttributes);

        if ($timeControll === true) {
            $userAttributes['id'] = $nameH;
            $userAttributes['wrapper-class'] = 'hour';
            $selectH = Choice::select($nameH, static::getHours(), [$valH], $userAttributes);

            $userAttributes['id'] = $nameI;
            $userAttributes['wrapper-class'] = 'minute';
            $selectI = Choice::select($nameI, static::getMinutes(), [$valI], $userAttributes);

            $userAttributes['id'] = $nameS;
            $userAttributes['wrapper-class'] = 'second';
            $selectS = Choice::select($nameS, static::getSeconds(), [$valS], $userAttributes);
        }

        $btnTitle = static::getConstant(
            'TXT_PANEL_DATA_FORM_TIMESTAMP_CONTROLL_RESET',
            'TXT_PANEL_DATA_FORM_TIMESTAMP_CONTROLL_RESET'
        );
        $resetBtn = $isEnabled ? Tags::span('', ['class' => 'timestamp-reset', 'title' => $btnTitle]) : '';
        $controll = $selectY.$selectM.$selectD.$selectH.$selectI.$selectS.$resetBtn;

        return Tags::div($controll, ['class' => 'timestamp-controll cf']);
    }
}
