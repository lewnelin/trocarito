<?php
class Menu{
    private $perfil;
    private $list;

    public function __construct($perfil){
        $this->perfil = $perfil;
        $this->list = $this->processar();
    }

    public function getModulos(){
        $modulos = array();
        foreach($this->list as $l){
            if(!in_array(Db_Modulo::find($l["modulo_id"]),$modulos)){
                $modulos[] = Db_Modulo::find($l["modulo_id"]);
            }
        }
        return $modulos;
    }

    public function getControllers(Db_Modulo $modulo){
        $controllers = array();
        foreach($this->list as $l){
            if($l["modulo_id"] == $modulo->getId()){
                if(!in_array(Db_Controller::find($l["controller_id"]),$controllers))
                    $controllers[] = Db_Controller::find($l["controller_id"]);
            }
        }
        return $controllers;
    }

    public function getActions(Db_Controller $controller){
        $actions = array();
        foreach($this->list as $l){
            if($l["controller_id"] == $controller->getId()){
                if(!in_array(Db_Action::find($l["action_id"]),$actions))
                    $actions[] = Db_Action::find($l["action_id"]);
            }
        }
        return $actions;
    }

    //Essa rotina vai mapear de acordo com o perfil os modulos, controllers e actions que deve ser mostrados
    private function processar(){
        $db = Db::getInstance();
        $param = array();
        if(Login::isLogado() && Login::getUsuario()->getSuper() == '1') {
            $select = "SELECT p.id modulo_id, c.id controller_id, a.id action_id
			FROM SC_ACTION a JOIN SC_CONTROLLER c ON c.id = a.controllerId 
			JOIN SC_MODULO p ON p.id = c.moduloId WHERE c.fl_exibir_menu = '1'";
        }else{
            $select = "SELECT p.id modulo_id, c.id controller_id, a.id action_id, pf.id perfil_id
			FROM SC_PERFIL pf
			JOIN SC_ACL acl ON acl.perfilId = pf.id
			JOIN SC_ACTION a ON acl.actionId = a.id
			JOIN SC_CONTROLLER c ON c.id = a.controllerId
			JOIN SC_MODULO p ON p.id = c.moduloId WHERE pf.id = ? AND c.fl_exibir_menu = '1'";
            $param[] = $this->perfil;
        }
        $stmt = $db->prepare($select." AND p.path != 'sc' ORDER BY p.nome, c.rotulo");//sistema central n�o aparece
        $stmt->execute($param);
        $list = $stmt->fetchAll();

        return $list;
    }


}
