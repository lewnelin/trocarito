<?php

require dirname(__FILE__).'/tables.php';
require dirname(__FILE__).'/database.php';

//ini_set('memory_limit','64M');
ini_set('memory_limit','256M');
//ini_set('memory_limit','-1');


define("BOLETO_PATH",'/acadelotear/boletophp/');
define("BOLETO_LOGO",'/acadelotear/public/images/logo_acadeone.png');
define("DIRETORIO_DOWNLOAD","upload/");
define('LOGOMARCA', 'public/images/logo_relatorio.png');
define('LOGOMARCA_SERVIDORA', 'public/images/logomarcaDefault.jpg');
define('LOGOMARCA_CLIENTE', 'public/images/logo_relatorio.png');
define('LOGOMARCA_DEFAULT', 'public/images/logomarcaDefault.jpg');
define('IMAGEM_TOPO', 'public/images/topo_lotear.jpg');
define('LOGO_RELATORIO','public/images/logo_relatorio.png');


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

//require dirname(__FILE__).'/../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');
