<?php
header('content-type:text/html;charset=utf-8');
try {
    // list some PDO::ATTR value
    $sql = 'SELECT * FROM user';
    $attrArr = array(
        'AUTOCOMMIT', 'ERRMODE', 'CASE', 'PERSISTENT', 'TIMEOUT', 'SERVER_INFO', 'SERVER_VERSION', 'CLIENT_VERSION', 'CONNECTION_STATUS',
    );
    foreach ($attrArr as $attr) {
        echo "PDO::ATTR_$attr: ";
        echo $pdo->getAttribute(constant("PDO::ATTR_$attr"));
        echo '<br>';
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
