<?php
require_once __DIR__.'/config.php';

$lines = file('./holiday.txt');

foreach ($lines as $no => $line) :
    if ($no == 0)
        continue;

    $line = trim($line);
    $exp = array_map('trim', explode("\t", $line));

    $temp = array_map('trim', explode('-', $exp[1]));

    $year  = $temp[0];
    $month = $temp[1];
    $day   = $temp[2];
    $name  = str_replace('<br/>', ',', $exp[2]);

    $sql = "insert into `{$config['holiday_table']}` (year, month, day, name ) values (:year, :month, :day, :name)";

    $DB->prepare($sql);
    $DB->bindValueArray([
        ':year' => $year,
        ':month' => $month,
        ':day' => $day,
        ':name' => $name
    ]);

    $DB->execute();
endforeach;