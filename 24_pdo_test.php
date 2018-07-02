<?php

// 1. PDO connect mysql speed
$pStartTime = microtime(true);
for ($i = 1; $i <= 100; $i++) {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
}
$pEndTime = microtime(true);
$res1 = $pEndTime - $pStartTime;
echo $res1 . '<br>';
echo '<hr>';

// 2. mysqli connect speed
$mStartTime = microtime(true);
for ($i = 1; $i <= 100; $i++) {
    mysqli_connect('localhost', 'root', '');
    mysqli_select_db('study');
}
$mEndTime = microtime(true);
$res2 = $mEndTime - $mStartTime;
echo $res2 . '<br>';
echo '<hr>';

// 3. compare two methods
if ($res1 > $res2) {
    echo 'The Connection Speed of PDO is ' . round($res1 / $res2) . " times of mysqli";
    echo '<hr>';
} else {
    echo 'The Connection Speed of mysqli is ' . round($res2 / $res1) . " times of PDO";
    echo '<hr>';
}
