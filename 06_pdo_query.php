<?php
header('content-type:text/html;charset=utf-8');
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    //exec() execute one sql and return the number of rows, except select
    // $sql = 'UPDATE user set username="mike" WHERE id=1';
    $sql = 'SELECT * FROM user';
    $res = $pdo->query($sql);
    if ($res === false) {
        echo $pdo->errorCode();
        echo '<br>';
        $err = $pdo->errorInfo();
        print_r($err);
    } else {
        foreach ($res as $row) {
            echo '<br>';
            print_r($row);
        }
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
