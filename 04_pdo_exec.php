<?php
header('content-type:text/html;charset=utf-8');
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    //exec() execute one sql and return the number of rows, except select
    // $sql = 'UPDATE user set username="mike" WHERE id=1';
    $sql = 'DELETE FROM user WHERE id=2';
    $res = $pdo->exec($sql);
    echo $res;
} catch (PDOException $e) {
    echo $e->getMessage();
}
