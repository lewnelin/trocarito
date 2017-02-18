<?php
class Db_reserva_lote {
    private $id;
    private $cod_lote;
    private $reservado;
    private $comprado;
    private $corretor;
    private $data_compra;
    private $data_reserva;
    private $id_pessoa;
    
    static $instance;

    
    
    private static $map = array(
            "id"=> "id",
            "cod_lote"=>"cod_lote",
            "reservado"=>"reservado",
            "comprado"=>"comprado",
            "corretor"=>"corretor",
            "data_compra"=>"data_compra",
            "data_reserva"=>"data_reserva",
   );

    CONST TABLE_NAME = "RESERVA_LOTE";

     /**
     * @return the $id
     */
    public function getIdPessoa(){
         $this->id_pessoa;
        
    }
    public function setIdPessoa($idPessoa){
         $this->id_pessoa = $idPessoa;
            
    }
    
    
    public function getId() {
        return $this->id;
    }

    /**
     * @return the $celular
     */
    public function getCod_lote() {
        return $this->cod_lote;
    }

    /**
     * @return the $telefone
     */
    public function getReservado() {
        return $this->reservado;
    }

    /**
     * @return the $email
     */
    public function getComprado() {
        return $this->comprado;
    }


    /**
     * @return the $nome
     */
    public function getCorretor() {
        return $this->corretor;
    }

    /**
     * @return the $rg
     */
    public function getData_compra() {
        return $this->data_compra;
    }

    /**
     * @return the $cpf
     */
    public function getData_reserva() {
        return $this->data_reserva;
    }

   
  

    /**
     * @param $id the $id to set
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param $celular the $celular to set
     */
    public function setCod_lote($cod_lote) {
        $this->cod_lote = $cod_lote;
    }

    /**
     * @param $telefone the $telefone to set
     */
    public function setReservado($reservado) {
        $this->reservado = $reservado;
    }

     

    /**
     * @param $email the $email to set
     */
    public function setComprado($comprado) {
        $this->comprado = $comprado;
    }

    /**
     * @param $nome the $nome to set
     */
    public function setCorretor($corretor) {
        $this->corretor = $corretor;
    }

    /**
     * @param $rg the $rg to set
     */
    public function setData_compra($data_compra) {
        $this->data_compra = $data_compra;
    }

    /**
     * @param $cpf the $cpf to set
     */
    public function setData_reserva($data_reserva) {
        $this->data_reserva = $data_reserva;
    }

    /**
     * @param $dataNascimento the $dataNascimento to set
     */
   
    public static function find($id = null, $where = null) {
        $select = Zend_Db_Table::getDefaultAdapter()->select();
        $select->from(array('r'=>RESERVA_LOTE))->join(array('l'=>TB_LOTES),'r.cod_lote = l.id','*');
        if($id)
            $select->where('r.id = ?',$id);
        if($where && is_array($where)) {
            foreach($where as $col => $val) {
                $select->where("{$col} = ?", $val);
            }
        }
        $lotes_reservados = $select->query()->fetchAll();
        $objs = array();

        if($lotes_reservados) {
            foreach ($lotes_reservados as $lr) {
                $obj = new Db_reserva_lote;
                foreach(self::$map as $var => $col) {
                    $obj->$var = $lotes_reservados[$col];
                }
                $objs[] = $obj;
            }
            if(count($objs) == 1) return $objs[0];
            else return $objs;
        }else return;
    }

    public static function findByField($campo, $valor) {
        return self::find(null,array($campo => $valor));
    }

    public static function delete($id) {
     
        

    }

    /**
     * @return bool
     */
    
    /*
     1	id	int(11)			Não	None	AUTO_INCREMENT	  Alterar	  Eliminar	 Mais 
	 2	cod_lote	int(11)			Não	None		  Alterar	  Eliminar	 Mais 
	 3	reservado	int(11)			Não	None		  Alterar	  Eliminar	 Mais 
	 4	comprado	int(11)			Não	None		  Alterar	  Eliminar	 Mais 
	 5	corretor	int(111)			Não	None		  Alterar	  Eliminar	 Mais 
	 6	data_compra	date			Não	None		  Alterar	  Eliminar	 Mais 
	 7	data_reserva 
     */
    
    
    public function save() {
        $rl = new Reserva_lote();
        
        $param = array (
                "id" => $this->getId (),
                "cod_lote" => $this->getCod_lote(),
                "reservado" => $this->getReservado(),
                "comprado" => $this->getComprado(),
                "corretor" => $this->getCorretor(),
                "data_compra" => $this->getData_compra(),
                "data_reserva" => $this->getData_reserva(),
       );

        $col_reserva_lote = array('id','cod_lote','reservado','comprado','corretor','data_compra','data_reserva');

       

        foreach($col_reserva_lote as $var)
            $reserva_lotes[self::$map[$var]] = $param[$var];
        
        if($this->id){
            $id = $this->id;
            $rl->update($reserva_lotes, $rl->getAdapter()->quoteInto('id = ?', $id));
        }else{
            $id = $p->insert($reserva_lotes);
            $this->id = $id;
        }

        $obj = $rl->find($id)->current();

        if($obj){
           
            $rl->update($reserva_lotes, $rl->getAdapter()->quoteInto('id = ?',$id));
        }else{
            $reserva_lotes['id'] = $id;
            $reserva_lotes = $rl->createRow($reserva_lotes);
            $reserva_lotes->save();
        }
    }
}