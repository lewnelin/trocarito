<?php
class Db_Cidade {

    private $id;
    private $nome;
    private $uf;
    private $log;

    const TABLE_NAME = "SC_CIDADE";

    public function setPrivate($private) {
        $this->private = $private;
    }
    /**
     * @param $log the $log to set
     */
    public function setLog($log) {
        $this->log = $log;
    }

    /**
     * @return the $log
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * @param string $uf
     */
    public function setUf($uf) {
        $this->uf = $uf;
    }

    /**
     * @return string $uf
     */
    public function getUf() {
        return $this->uf;
    }


    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }


    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function save() {
        $db = Db::getInstance();
        $insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (:id, :cod_dimob, :nome, :uf, :log)";
        $insert .=" ON DUPLICATE KEY UPDATE cod_dimob = :cod_dimob, nome = :nome , uf = :uf, log = :log";


        $stmt = $db->prepare($insert);

        $param = array(
                "id" => $this->getId(),
        		"cod_dimob" => null,
                "nome" => $this->getNome(),
                "uf" => $this->getUf(),
                "log" => $this->getLog(),
        );
        $save = $stmt->execute($param);
        if(!$this->getId()) $this->setId($db->lastInsertId());
        return $save;
    }

    /**
     *
     * @param int $id
     * @return Db_Cidade
     */
    public static function find($id) {
        $db = Db::getInstance();
        $filtro = array($id);
        $select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE id = ?";
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    /**
     *
     * @param string $nome
     * @return Db_Cidade
     */
    public static function findByNome($nome) {
        $db = Db::getInstance();
        $filtro = array($nome);
        $select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE nome = ?";
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function fullSearch($termo, $count = false, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();

        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM ".self::TABLE_NAME."
				   WHERE CONCAT(UPPER(nome),' ',UPPER(uf)) LIKE {$termo}";
        } else {
            $select = "SELECT * FROM ".self::TABLE_NAME."
				   WHERE CONCAT(UPPER(nome),' ',UPPER(uf)) LIKE {$termo} ";

            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";
        }

        $stmt = $db->prepare($select);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);

        return $stmt->fetchAll();
    }

    public static function delete($id) {
        $db = Db::getInstance();
        $delete  = "DELETE FROM ".self::TABLE_NAME."
					WHERE id = ?";
        $stmt = $db->prepare($delete);
        return $stmt->execute(array($id));
    }

    public static function fetchAll($where = null,$orderBy = null, $count = false, $offset = false, $page = false) {
        $db = Db::getInstance();
        $filtro = array();

        if($count && $offset == false && $page == false) {
            $select = "SELECT count(*) AS count FROM " . self::TABLE_NAME;
            if($where)
                $select .= " WHERE {$where}";
            if($orderBy)
                $select .= " ORDER BY {$orderBy}";
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME;
            if($where)
                $select .= " WHERE {$where}";

            if($orderBy)
                $select .= " ORDER BY {$orderBy}";

            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";


        }

        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    public function toArray() {
        $array = array();
        $vars = get_class_vars(__CLASS__);
        foreach($vars as $k => $v)
            $array[$k] = $this->$k;
        return $array;
    }

    public static function getEstados($uf){
        $estados = array('AC'=>'Acre',
                         'AL'=>'Alagoas',
                         'AP'=>'Amapá',
                         'AM'=>'Amazonas',
                         'BA'=>'Bahia',
                         'CE'=>'Ceará',
                         'DF'=>'Distrito Federal',
                         'ES'=>'Espírito Santo',
                         'GO'=>'Goiás',
                         'MA'=>'Maranhão',
                         'MT'=>'Mato Grosso',
                         'MS'=>'Mato Grosso do Sul',
                         'MG'=>'Minas Gerais',
                         'PA'=>'Pará',
                         'PB'=>'Paraíba',
                         'PR'=>'Paraná',
                         'PE'=>'Pernambuco',
                         'PI'=>'Piauí',
                         'RJ'=>'Rio de Janeiro',
                         'RN'=>'Rio Grande do Norte',
                         'RS'=>'Rio Grande do Sul',
                         'RO'=>'Rondônia',
                         'RR'=>'Roraima',
                         'SC'=>'Santa Catarina',
                         'SP'=>'São Paulo',
                         'SE'=>'Sergipe',
                         'TO'=>'Tocantins'
                        );

        return $estados[$uf];
    }

}
