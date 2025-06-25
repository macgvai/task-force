<?php

namespace victor\helpers;

use DateTime;
use DateInterval;

class TimeElapsedString
{
    public function getTime($datetime, $full = false)
    {
        $now = new DateTime();
        try {
            $ago = new DateTime($datetime);
        } catch (\Exception $e) {
            return 'неверная дата';
        }

        $diff = $now->diff($ago);

        // Calculate weeks without creating dynamic property
        $weeks = floor($diff->d / 7);
        $days = $diff->d - $weeks * 7;

        $string = [
            'y' => ['год', 'года', 'лет'],
            'm' => ['месяц', 'месяца', 'месяцев'],
            'w' => ['неделя', 'недели', 'недель'],
            'd' => ['день', 'дня', 'дней'],
            'h' => ['час', 'часа', 'часов'],
            'i' => ['минута', 'минуты', 'минут'],
            's' => ['секунда', 'секунды', 'секунд'],
        ];

        $result = [];
        foreach ($string as $k => $v) {
            $value = match($k) {
                'w' => $weeks,
                'd' => $days,
                default => $diff->$k
            };

            if ($value) {
                $result[] = $this->pluralize($value, $v[0], $v[1], $v[2]);
            }
        }

        if (!$full) {
            $result = array_slice($result, 0, 1);
        }

        return $result ? implode(', ', $result) . ' назад' : 'только что';
    }

    private function pluralize($count, $one, $two, $many)
    {
        $count = abs($count);
        $mod10 = $count % 10;
        $mod100 = $count % 100;

        if ($mod100 >= 11 && $mod100 <= 19) {
            return "$count $many";
        }

        switch ($mod10) {
            case 1:
                return "$count $one";
            case 2:
            case 3:
            case 4:
                return "$count $two";
            default:
                return "$count $many";
        }
    }
}