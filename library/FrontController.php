<?php
class FrontController{
	private $request;

        const MODULO_DEFAULT = 'dashboard';
        const CONTROLLER_DEFAULT = 'login';
        const ACTION_DEFAULT = 'index';
	
	public function __construct(){
		$this->request["modulo"] = (isset($_GET["m"]) && !empty($_GET["m"])) ? $_GET["m"] : self::MODULO_DEFAULT;
		$this->request["controller"] = (isset($_GET["c"]) && !empty($_GET["c"])) ? $_GET["c"] : self::CONTROLLER_DEFAULT;
		$this->request["action"] = (isset($_GET["a"]) && !empty($_GET["a"])) ? $_GET["a"] : self::ACTION_DEFAULT;
	}
	
	public function dispatch(){
		$controller  = new Controller($this->request);
		$controller->processRequest(); 
	}

}