<?php
header('content-type:text/html;charset=utf-8');
try {
    $options = array(PDO::ATTR_AUTOCOMMIT => 1, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    // This method can prevent sql injection
    $sql = 'SELECT username, password, email FROM user';
    $statement = $pdo->prepare($sql);
    $statement->execute();
    echo 'Number of Column: ' . $statement->columnCount() . '<br>';
    echo '<hr>';
    $meta = $statement->getColumnMeta(0);
    print_r($meta);
    echo '<hr>';
    $statement->bindColumn(1, $username);
    $statement->bindColumn(2, $password);
    $statement->bindColumn(3, $email);
    while ($statement->fetch(PDO::FETCH_BOUND)) {
        echo 'usernmae: ' . $username . '<br>';
        echo 'password: ' . $username . '<br>';
        echo 'email: ' . $email . '<br>';
        echo '<hr>';
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
