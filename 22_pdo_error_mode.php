<?php
header('content-type:text/html;charset=utf-8');
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /**
     * PDO::ERRMODE_SLIENT: DEFAULT MODE, SLIENT MODE
     * PDO::ERRMODE_WARNING: WARNING
     * PDO::ERRMODE_EXCEPTION: EXCEPTION
     */
    $sql = 'SELECT * FROM noneTable';
    $pdo->query($sql);
    echo $pdo->errorCode();
    echo '<br>';
    print_r($pdo->errorInfo());
} catch (PDOException $e) {
    echo $e->getMessage();
}
