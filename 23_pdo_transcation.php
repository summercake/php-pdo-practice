<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 0, PDO::ATTR_ERRMODE => 0);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    /* $sql = <<<EOF
    CREATE TABLE IF NOT EXISTS userAccount(
    id INT UNSIGNED AUTO_INCREMENT KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    money DECIMAL(10,2)
    )ENGINE=INNODB;
    EOF;
    $res = $pdo->exec($sql);
    $sql1 = "INSERT userAccount(username, money) VALUES ('mike',5000),('hoo',200)";
    $res = $pdo->exec($sql1);
    var_dump($res); */

    $pdo->beginTransaction();
    $sql = 'UPDATE userAccount SET money=money-2000 WHERE username="mike"';
    $res = $pdo->exec($sql);
    if ($res == 0) {
        throw new PDOException('mike transcation failed');
    }
    $sql = 'UPDATE userAccount SET money=money+2000 WHERE username="hoo"';
    $res1 = $pdo->exec($sql);
    if ($res1 == 0) {
        throw new PDOException('hoo transcation failed');
    }
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollback();
    echo $e->getMessage();
}
