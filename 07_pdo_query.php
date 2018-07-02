<?php
header('content-type:text/html;charset=utf-8');
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    $sql = 'INSERT user (username, password, email) values("Nancy","1234","Nancy@jack.com")';
    // query() will return PDOStatement Obj
    $res = $pdo->query($sql);
    if ($res === false) {
        echo $pdo->errorCode();
        echo '<br>';
        $err = $pdo->errorInfo();
        print_r($err);
    } else {
        print_r($res);
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
