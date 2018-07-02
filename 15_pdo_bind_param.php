<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    // This method can prevent sql injection
    $sql = 'INSERT user (username, password, email) values(:username, :password,:email)';
    $statement = $pdo->prepare($sql);
    $username = 'Hoo';
    $password = '1234';
    $email = 'hoo@hoo.com';
    /**
     * bindParam(placeholder name, variable, data type);
     */
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->bindParam(":password", $password, PDO::PARAM_STR);
    $statement->bindParam(":email", $email, PDO::PARAM_STR);
    $statement->execute();
    echo $statement->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
}
