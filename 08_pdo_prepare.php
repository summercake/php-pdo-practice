<?php
header('content-type:text/html;charset=utf-8');
try {
    $pdo = new PDO('mysql:host=localhost;dbname=study', 'root', '');
    $sql = 'SELECT * FROM user';
    $statement = $pdo->prepare($sql);
    $res = $statement->execute();
    // get one row by one time
    // if ($res) {
    //     while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    //         print_r($row);
    //         echo '<hr>';
    //     }
    // }
    // get all rows by one time
    // $rows = $statement->fetchAll(PDO::FETCH_OBJ);
    // $rows = $statement->fetchAll(PDO::FETCH_BOTH);
    // $rows = $statement->fetchAll(PDO::FETCH_NUM);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    print_r($rows);
} catch (PDOException $e) {
    echo $e->getMessage();
}
