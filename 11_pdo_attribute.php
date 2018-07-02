<?php
header('content-type:text/html;charset=utf-8');
try {
    // set PDO::ATTR value
    $options = array(PDO::ATTR_AUTOCOMMIT => 0, PDO::ATTR_ERRMODE => 2);
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '', $options);
    echo 'ATTR_AUTOCOMMIT: ' . $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT) . '<br>';
    echo 'ATTR_ERRMODE: ' . $pdo->getAttribute(PDO::ATTR_ERRMODE) . '<br>';
} catch (PDOException $e) {
    echo $e->getMessage();
}
