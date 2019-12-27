<?php
require_once __DIR__.'/config.php';

$calendar = new CALENDAR();

if (isset($_REQUEST['year']) && $_REQUEST['year'])
    $year = (int)$_REQUEST['year'];
else
    $year = date('Y');

if (isset($_REQUEST['month']) && $_REQUEST['month'])
    $month = (int)$_REQUEST['month'];
else
    $month = date('n');

$data = $calendar->get($year, $month);

//print_r($data); exit;

$prev_year  = $data['prev']['year'];
$prev_month = $data['prev']['month'];

$next_year  = $data['next']['year'];
$next_month = $data['next']['month'];

$prev = '<a href="./?year='.$prev_year.'&amp;month='.$prev_month.'">이전</a>';
$next = '<a href="./?year='.$next_year.'&amp;month='.$next_month.'">다음</a>';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link type="text/css" href="css/calendar.css?v=<?php echo time(); ?>" rel="stylesheet" />
<title>Calendar</title>
</head>
<body>
    <div id="calendar">
        <div class="top">
            <div class="prev"><?php echo $prev; ?></div>
            <h3><?php echo $data['year']; ?>년 <?php echo $data['month']; ?>월</h3>
            <div class="next"><?php echo $next; ?></div>
        </div>
        <div class="calendar">
            <table>
            <thead>
            <tr>
                <th scope="row">일</th>
                <th scope="row">월</th>
                <th scope="row">화</th>
                <th scope="row">수</th>
                <th scope="row">목</th>
                <th scope="row">금</th>
                <th scope="row">토</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <?php
            foreach ($data['cells'] as $idx => $cell) :
                if ($idx > 0 && $idx % 7 == 0)
                    echo '</tr>'.PHP_EOL.'<tr>'.PHP_EOL;

                $holiday = str_replace(',', '<br>', $cell['holiday']);

                $current = '';

                if ($cell['current'])
                    $current = ' class="current';

                if ($holiday) :
                    if ($current)
                        $current .= ' holiday';
                    else
                        $current = ' class="holiday';
                endif;

                if ($idx % 7 == 0) :
                    if ($current)
                        $current .= ' sunday';
                    else
                        $current = ' class="sunday';
                endif;

                if ($current)
                    $current .= '"';

                echo '<td'.$current.'>'.PHP_EOL;
                echo '<div class="day">'.$cell['day'].'</div>'.PHP_EOL;
                echo '<div class="holiday">'.$holiday.'</div>'.PHP_EOL;
                if (!empty($events))
                    echo '<ul class="event">'.$events.'</ul>'.PHP_EOL;
                echo '</td>'.PHP_EOL;
            ?>
            <?php
            endforeach;
            ?>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
</body>
</html>