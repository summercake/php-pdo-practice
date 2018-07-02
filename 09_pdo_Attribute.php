<?php
header('content-type:text/html;charset=utf-8');
try {
    // get and set PDO::ATTR
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    // get attribute
    echo 'ATTR_AUTOCOMMIT: ' . $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT) . '<br>';
    echo 'ATTR_ERRMODE: ' . $pdo->getAttribute(PDO::ATTR_ERRMODE) . '<br>';
    // set attribute
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0) . '<br>';
    echo 'ATTR_AUTOCOMMIT: ' . $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT) . '<br>';
} catch (PDOException $e) {
    echo $e->getMessage();
}
