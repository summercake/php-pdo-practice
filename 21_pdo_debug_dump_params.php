<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    $sql = 'INSERT user (username, password, email) values(?,?,?)';
    $statement = $pdo->prepare($sql);
    $username = 'Hoo9';
    $password = '1234';
    $email = 'Hoo9@hoo.com';
    $statement->bindParam(1, $username);
    $statement->bindParam(2, $password);
    $statement->bindParam(3, $email);
    $statement->execute();
    $statement->debugDumpParams();
} catch (PDOException $e) {
    echo $e->getMessage();
}
