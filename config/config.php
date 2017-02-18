<?php

require dirname(__FILE__).'/tables.php';
require dirname(__FILE__).'/database.php';

ini_set('memory_limit','256M');

define('IMAGEM_TOPO', 'public/images/topo_lotear.jpg');

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/../library'.PATH_SEPARATOR.dirname(__FILE__).'/../model');
ob_start();

require_once dirname(__FILE__).'/../library/Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$pdoParams = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;');

$db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => DB_HOST,
    'username' => DB_USER,
    'password' => DB_PASSWORD,
    'dbname'   => DB_NAME,
    'driver_options' => $pdoParams
));

Zend_Db_Table::setDefaultAdapter($db);

function __autoload($class){
    $path = explode("_",$class);
    $path = implode("/",$path);
    $path .= ".php";
    require_once $path;
}

date_default_timezone_set('America/Sao_Paulo');
