<?php
// method 1
/* try {
$dsn = 'mysql:host=localhost;dbname=study';
$username = 'root';
$pwd = '';
$pdo = new PDO($dsn, $username, $pwd);
var_dump($pdo);
} catch (PDOException $e) {
echo $e->getMessage();
} */

// method 2
try {
    $dsn = 'uri:file://D:\Laragon\php-pdo\dsn.txt';
    $username = 'root';
    $pwd = '';
    $pdo = new PDO($dsn, $username, $pwd);
    var_dump($pdo);
} catch (PDOException $e) {
    echo $e->getMessage();
}
