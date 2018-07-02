<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    $username = "' or 1=1 #";
    $password = "' or 1=1 #";
    $username = $pdo->quote($username);
    $password = $pdo->quote($password);
    $sql = "SELECT * FROM user WHERE username={$username} and password={$password}";
    echo $sql;
    // $statement = $pdo->query($sql);
    // echo $statement->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}
