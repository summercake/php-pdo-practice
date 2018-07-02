<?php
// method 1
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    //exec() execute one sql and return the number of rows, except select
    $sql = <<<EOF
        CREATE TABLE IF NOT EXISTS user(
            id INT UNSIGNED AUTO_INCREMENT KEY,
            username VARCHAR(20) NOT NULL UNIQUE,
            password CHAR(32) NOT NULL,
            email VARCHAR(30) NOT NULL
        );
EOF;
    $res = $pdo->exec($sql);
    var_dump($res);

    $sql = 'INSERT user (username, password, email) values("jack","' . md5('1234') . '","jack@jack.com")';
    $res = $pdo->exec($sql);
    var_dump($res);
} catch (PDOException $e) {
    echo $e->getMessage();
}
