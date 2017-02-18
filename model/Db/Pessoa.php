<?php
class Db_Pessoa {
    private $id;
    private $nome;
    private $rg;
    private $cpf;
    private $dataNascimento;
    private $sexo;
    private $estadoCivil;
    private $email;
    private $telefone;
    private $celular;

    static $instance;
    private static $map = array(
            "id"=> "id",
            "nome"=>"nm_pessoa",
            "rg"=>"nr_rg",
            "cpf"=>"nr_cpf",
            "dataNascimento"=>"dt_nascimento",
            "sexo"=>"sexo",
            "estadoCivil"=>"est_civil",
            "email"=>"email",
            "telefone"=>"nr_telefone",
            "celular"=>"nr_celular"
    );

    CONST TABLE_NAME = "SC_PESSOA";

    /**
     * @return Pessoa_Fisica, Pessoa
     */
    private static function getInstance($tipo) {
        if(!self::$instance[$tipo]) {
            switch ($tipo) {
                case 'PF' :
                    self::$instance[$tipo] = new Pessoa_Fisica();
                    break;
                case 'P' :
                    self::$instance[$tipo] = new Pessoa();
                    break;
            }
        }
        return self::$instance[$tipo];
    }


    /**
     * @return the $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return the $celular
     */
    public function getCelular() {
        return $this->celular;
    }

    /**
     * @return the $telefone
     */
    public function getTelefone() {
        return $this->telefone;
    }

    /**
     * @return the $email
     */
    public function getEmail() {
        return $this->email;
    }


    /**
     * @return the $nome
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @return the $rg
     */
    public function getRg() {
        return $this->rg;
    }

    /**
     * @return the $cpf
     */
    public function getCpf() {
        return $this->cpf;
    }

    /**
     * @return the $dataNascimento
     */
    public function getDataNascimento() {
        $return = $this->dataNascimento;
        if ($return == '0000-00-00')
            $return = null;

        return $return;
    }

    /**
     * @return the $sexo
     */
    public function getSexo() {
        return $this->sexo;
    }

    /**
     * @return the $estadoCivil
     */
    public function getEstadoCivil() {
        return $this->estadoCivil;
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
    public function setCelular($celular) {
        $this->celular = $celular;
    }

    /**
     * @param $telefone the $telefone to set
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * @param $email the $email to set
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param $nome the $nome to set
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @param $rg the $rg to set
     */
    public function setRg($rg) {
        $this->rg = $rg;
    }

    /**
     * @param $cpf the $cpf to set
     */
    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    /**
     * @param $dataNascimento the $dataNascimento to set
     */
    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    /**
     * @param $sexo the $sexo to set
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    /**
     * @param $estadoCivil the $estadoCivil to set
     */
    public function setEstadoCivil($estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public static function find($id = null, $where = null) {
        $select = Zend_Db_Table::getDefaultAdapter()->select();
        $select->from(array('p'=>TB_PESSOA))->join(array('pf'=>TB_PESSOA_FISICA),'p.id=pf.id_pessoa','*');
        if($id)
            $select->where('p.id = ?',$id);
        if($where && is_array($where)) {
            foreach($where as $col => $val) {
                $select->where("{$col} = ?", $val);
            }
        }
        $pessoas = $select->query()->fetchAll();
        $objs = array();

        if($pessoas) {
            foreach ($pessoas as $pessoa) {
                $obj = new Db_Pessoa;
                foreach(self::$map as $var => $col) {
                    $obj->$var = $pessoa[$col];
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
        $pf = self::getInstance('PF');
        $p = self::getInstance('P');
        try {
            $pf->delete($pf->select()->where('id_pessoa = ?',$id));
            $p->delete($pf->select()->where('id = ?',$id));
            return true;
        }catch(Exception $e) {
            return false;
        }

    }

    /**
     * @return bool
     */
    public function save() {
        $p = new Pessoa();
        $pf = new Pessoa_Fisica();

        $param = array (
                "id" => $this->getId (),
                "nome" => $this->getNome(),
                "rg" => $this->getRg(),
                "cpf" => $this->getCpf(),
                "dataNascimento" => $this->getDataNascimento(),
                "sexo" => $this->getSexo(),
                "estadoCivil" => $this->getEstadoCivil(),
                "email" => $this->getEmail(),
                "telefone" => $this->getTelefone(),
                "celular" => $this->getCelular(),
        );

        $col_pessoas = array('id','nome','telefone','celular','email');
        $col_pessoas_fisicas = array('rg','cpf','dataNascimento','sexo','estadoCivil');

        $pessoa = array();
        $pessoa_fisica = array();

        foreach($col_pessoas as $var)
            $pessoa[self::$map[$var]] = $param[$var];
        $pessoa['tp_pessoa'] = 'F';
        $pessoa['dt_cadastro'] = date('Y-m-d H:i:s');
        unset($pessoa['id']);

        foreach($col_pessoas_fisicas as $var)
            $pessoa_fisica[self::$map[$var]] = $param[$var];

        if($this->id){
            $id = $this->id;
            $p->update($pessoa, $p->getAdapter()->quoteInto('id = ?', $id));
        }else{
            $id = $p->insert($pessoa);
            $this->id = $id;
        }

        $obj = $pf->find($id)->current();

        if($obj){
            unset ($pessoa_fisica['id']);
            $pf->update($pessoa_fisica, $pf->getAdapter()->quoteInto('id_pessoa = ?',$id));
        }else{
            $pessoa_fisica['id_pessoa'] = $id;
            $rs_pessoa_fisica = $pf->createRow($pessoa_fisica);
            $rs_pessoa_fisica->save();
        }
    }
}