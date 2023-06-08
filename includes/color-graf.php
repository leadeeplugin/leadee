<?php

class ColorGraf
{

    function ColorDate($param, $day_plus = 1)
    {

        $range = ['day' => ['from' => 1, 'to' => 31], 'week' => ['from' => 32, 'to' => 84], 'month' => ['from' => 85, 'to' => 10000]];
        $colors =
            [
                'day' => ['#F04438', '#FCC02A', '#F9ED37', '#8AC44B', '#1BBDD4', '#478ECC', '#913E98'],
                'week' => ['#F04438', '#F8981D', '#8AC44B', '#478ECC', '#F04438', '#F8981D', '#8AC44B', '#478ECC', '#F04438', '#F8981D', '#8AC44B', '#478ECC'],
                'month' => ['#478ECC', '#2B74B9', '#ADD57F', '#8AC44B', '#69A042', '#FBF376', '#F9ED37', '#FCC02A', '#E77373', '#F04438', '#D62D30', '#70B2E2']
            ];

        if (empty($param['timezone'])) $param['timezone'] = 'GMT';
        ini_alter('date.timezone', $param['timezone']);
        date_default_timezone_set($param['timezone']);
        if (empty($param['out_date_format'])) $param['out_date_format'] = 'Y-m-d H:i:s';
        if ($param['from'] > 1000000000000) $param['from'] = $param['from'] / 1000;
        if ($param['to'] > 1000000000000) $param['to'] = $param['to'] / 1000;

        $days_from = strtotime(date('Y-m-d 00:00:00', intval($param['from'])));
        $days_to = strtotime(date('Y-m-d 23:59:59', intval($param['to'])));

        $day_start = (int)date('d', $days_from);
        $month_start = (int)date('m', $days_from);
        $year_start = (int)date('Y', $days_from);
        $month_end = (int)date('m', $days_to);
        $year_end = (int)date('Y', $days_to);
        $month_all = ($year_end - $year_start) * 12 + ($month_end - $month_start) + 1;
        $diff = (array)date_diff(date_create(date('Y-m-d', $days_from)), date_create(date('Y-m-d', $days_to)));
        $days_all = $diff['days'] + $day_plus;

        $days_range = '';
        $colors_range = [];

        foreach ($range as $key => $value) {
            if ($days_all >= $value['from'] && $days_all <= $value['to']) {
                $days_range = $key;
                break;
            }
        }

        if ($days_range == 'day') {
            $day_from = $days_from;
            for ($i = 0; $i < $days_all; $i++) {
                $day_to = strtotime('+1 day -1 second', $day_from);
                $day_of_week = (int)date('N', $day_from) - 1;
                $colors_range[] = ['range' => ['from' => date($param['out_date_format'], $day_from), 'to' => date($param['out_date_format'], $day_to)], 'color' => $colors[$days_range][$day_of_week]];
                $day_from = strtotime('+1 day', $day_from);
            }

        } elseif ($days_range == 'week') {

            $day_from = $days_from;
            $day_start = (int)date('N', $days_from) - 1;
            for ($i = 0; $i < $days_all; $i += 7) {
                $step = round($i / 7, 1);
                $shift_start = ($step == 0 ? 0 : ((7 - $day_start) + ($i - 7)));
                $shift_end = ($step == 0 ? (7 - $day_start) : 7);
                $day_from = strtotime('+' . $shift_start . ' day', $days_from);
                $day_to = strtotime('+' . $shift_end . ' day -1 second', $day_from);
                if ($day_to > $days_to) $day_to = $days_to;
                $colors_range[] = ['range' => ['from' => date($param['out_date_format'], $day_from), 'to' => date($param['out_date_format'], $day_to)], 'color' => $colors[$days_range][$step]];
            }

        } elseif ($days_range == 'month') {

            $y = $year_start;
            $m = $month_start;
            for ($i = 0; $i < $month_all; $i++) {
                if ($m > 12) {
                    $m -= 12;
                    $y++;
                }
                $m_next = $m == 12 ? 1 : $m + 1;
                $y_next = $m == 12 ? $y + 1 : $y;
                $d = $i == 0 ? $day_start : 1;
                $day_from = strtotime($y . '-' . $m . '-' . $d . ' 00:00:00');
                $day_to = strtotime($y_next . '-' . $m_next . '-01 00:00:00') - 1;
                if ($day_to > $days_to) $day_to = $days_to;
                $colors_range[] = ['range' => ['from' => date($param['out_date_format'], $day_from), 'to' => date($param['out_date_format'], $day_to)], 'color' => $colors[$days_range][$m - 1]];
                $m++;
            }

        }

        return $colors_range;
    }

}
