<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    // This method can prevent sql injection
    $sql = 'INSERT user (username, password, email) values(?,?,?)';
    $statement = $pdo->prepare($sql);
    $username = 'Hoo2';
    $password = '1234';
    $email = 'hoo2@hoo.com';
    /**
     * bindParam(placeholder name, variable, data type);
     */
    $statement->bindValue(1, $username);
    $statement->bindValue(2, $password);
    $statement->bindValue(3, $email);
    $statement->execute();
    echo $statement->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}
