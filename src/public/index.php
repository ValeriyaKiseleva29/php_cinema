<?php
use App\RMVC\App as App;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

require "../vendor/autoload.php";
require "../routes/web.php";
App::run();



