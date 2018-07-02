<?php
header('content-type:text/html;charset=utf-8');
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    //exec() execute one sql and return the number of rows, except select
    $sql = 'INSERT user (username, password, email) values("jack4","1234","jack@jack.com"),("jack5","1234","jack@jack.com"),("jack6","1234","jack@jack.com")';
    $res = $pdo->exec($sql);
    var_dump($res);
    echo '<br>';
    echo $pdo->lastInsertId();
    echo '<br>';
} catch (PDOException $e) {
    echo $e->getMessage();
}
