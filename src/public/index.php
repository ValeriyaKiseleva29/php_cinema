<?php
use App\RMVC\App as App;

session_start();

require "../vendor/autoload.php";
require "../routes/web.php";
App::run();

//$username = 'palmo';
//$password = 'palmo';
//$dbname = 'palmo';
//$host = 'mysql';
//
//$dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8;';
//
//try {
//    $conn = new PDO($dsn, $username, $password);
//    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    var_dump($conn);
//} catch (PDOException $e) {
//    die("Connection failed: " . $e->getMessage());
//}


