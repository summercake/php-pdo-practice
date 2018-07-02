<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    // This method can prevent sql injection
    $sql = "SELECT * FROM user WHERE username=? and password=?";
    $statement = $pdo->prepare($sql);
    $statement->execute(array("Nancy", "1234"));
    echo $statement->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}
