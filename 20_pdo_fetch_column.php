<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    // This method can prevent sql injection
    $sql = 'SELECT username, password, email FROM user';
    $res = $pdo->query($sql);
    // fetchColum will move down pointer every time
    echo $res->fetchColumn(0);
    echo $res->fetchColumn(0);
} catch (PDOException $e) {
    echo $e->getMessage();
}
