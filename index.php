<?php
require_once "config/config.php";
session_start();

$front = new FrontController();
$front->dispatch();