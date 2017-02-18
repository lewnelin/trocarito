<?php
class AjaxController extends Controller
{
    public function actionsAction()
    {
        //@todo checar permissao aqui
        if($_GET["controller"]){
            $actions = array();
            $controller = $_GET["controller"];
            FrontController::loadClass("Db_Action");
            $actions = Db_Action::fetchAll($controller);
            $this->set("actions",$actions);
        }
        $this->display();
    }
}