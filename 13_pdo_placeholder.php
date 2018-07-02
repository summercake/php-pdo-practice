<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    // This method can prevent sql injection
    $sql = "SELECT * FROM user WHERE username=:username and password=:password";
    $statement = $pdo->prepare($sql);
    $username = 'Nancy';
    $password = '1234';
    $statement->execute(array(":username" => $username, ":password" => $password));
    echo $statement->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}
