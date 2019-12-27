<?php
/**
 * Calendar Class
 */

class CALENDAR
{
    protected $year;
    protected $month;
    protected $day;
    protected $events;

    public function __construct(array $attrs=array())
    {
        if (isset($attrs['year']) && $attrs['year'])
            $this->year = (int)$attrs['year'];
        else
            $this->year = date('Y');

        if (isset($attrs['month']) && $attrs['month'])
            $this->month = str_pad((int)$attrs['month'], 2, '0', STR_PAD_LEFT) ;
        else
            $this->month = date('m');

        if (isset($attrs['day']) && $attrs['day'])
            $this->day = str_pad((int)$attrs['day'], 2, '0', STR_PAD_LEFT);
        else
            $this->day = date('d');
    }

    public function getNextMonth($y, $m) {
        $y = (int)$y;
        $m = (int)$m;

        $m++;

        if ($m % 13 == 0 || $m > 12) {
            $y++;
            $m = 1;
        }

        return array('year' => $y, 'month' => $m);
    }

    public function getPrevMonth($y, $m) {
        $y = (int)$y;
        $m = (int)$m;

        $m--;

        if ($m <= 0) {
            $y--;
            $m = 12;
        }

        return array('year' => $y, 'month' => $m);
    }

    public function getMonthLastDay($y, $m) {
        $year  = (int)$y;
        $month = (int)$m;

        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public function get($y=0, $m=0) {
        $y = (int)$y;
        $m = (int)$m;

        if (!$y)
            $y = $this->year;

        if (!$m)
            $m = $this->month;

        $w = date('w', mktime(0, 0, 0, $m, 1, $y));
        $l = $this->getMonthLastDay($y, $m);

        $cells = array();

        if ($w == 0) { // 달력의 1일이 일요일
            for ($i=1; $i <= $l; $i++) {
                $cells[] = $this->cells($y, $m, $i);
            }

            $prev = $this->getPrevMonth($y, $m);

            $left = 5 * 7 - $l;
            $next = $this->getNextMonth($y, $m);

            for ($i = 1; $i <= $left; $i++) {
                $cells[] = $this->cells($next['year'], $next['month'], $i, false);
            }
        } else {
            $prev = $this->getPrevMonth($y, $m);
            $c = $this->getMonthLastDay($prev['year'], $prev['month']);

            $p = $c - $w + 1; // 이전 달 표시 날짜 수

            for ($i = $p; $i <= $c; $i++) {
                $cells[] = $this->cells($prev['year'], $prev['month'], $i, false);
            }

            $c = $this->getMonthLastDay($y, $m);

            for ($i = 1; $i <= $c; $i++) {
                $cells[] = $this->cells($y, $m, $i);
            }

            //35(7*5) or 42(7*6) cells
            $b = $w + $c;
            $left = 0;

            if ($b <= 35) {
                $left = 35 - $b;
            } else {
                $left = 42 - $b;
            }

            $next = $this->getNextMonth($y, $m);

            if ($left > 0) {
                for ($i = 1; $i <= $left; $i++) {
                    $cells[] = $this->cells($next['year'], $next['month'], $i, false);
                }
            }
        }

        return array(
            'year'  => $y,
            'month' => $m,
            'prev'  => $prev,
            'next'  => $next,
            'cells' =>$cells
        );
    }

    private function cells($y, $m, $d, $c=true)
    {
        $cell = array();

        $year  = (int)$y;
        $month = str_pad((int)$m, 2, '0', STR_PAD_LEFT);
        $day   = str_pad((int)$d, 2, '0', STR_PAD_LEFT);

        $holiday = $this->getHoliday($year, $month, $day);

        $cell['year']  = $year;
        $cell['month'] = (int)$month;
        $cell['day']   = (int)$day;

        $cell['holiday'] = $holiday;

        $cell['current'] = $c;

        return $cell;
    }

    private function getHoliday($y, $m, $d)
    {
        $DB     = $GLOBALS['DB'];
        $config = $GLOBALS['config'];

        $year  = (int)$y;
        $month = str_pad((int)$m, 2, '0', STR_PAD_LEFT);
        $day   = str_pad((int)$d, 2, '0', STR_PAD_LEFT);

        $sql = " select name from `{$config['holiday_table']}` where year = :year and month = :month and day = :day ";

        $DB->prepare($sql);
        $DB->bindValueArray([
            ':year'  => $year,
            ':month' => $month,
            ':day'   => $day
        ]);

        $DB->execute();

        return $DB->fetchColumn();
    }
}