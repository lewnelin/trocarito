<?php
class Db_TabelaAgrupada {
    private $idIdioma;
    private $idTabela;
    private $idCampo;
    private $descricao;
    private $complemento;
    private $fixo;
    private $log;

    const TABLE_NAME = "SC_TABELA_AGRUPADA";

    const TABLE_UF = 1;
    const TABLE_SEXO = 2;
    const TABLE_ESTADO_CIVIL = 3;
    const TABLE_MOTIVO_DESLIGAMENTO = 4;
    const TABLE_GRAU_DE_INSTRUCAO = 5;
    const TABLE_CARGO = 6;
    const TABLE_TIPO_ARQUIVO = 7;
    const TABLE_BANCO = 9;
    const TABLE_CLIENTE = 10;
    const TABLE_STATUS_CONTATO = 11;
    const TABLE_INDICE = 12;

    public static $tabelas = array(
            self::TABLE_UF => "UF",
            self::TABLE_SEXO => "Sexo",
            self::TABLE_ESTADO_CIVIL => "Estado Civil",
            self::TABLE_MOTIVO_DESLIGAMENTO => "Motivo Desligamento",
            self::TABLE_GRAU_DE_INSTRUCAO=> "Grau de Instrução",
            self::TABLE_CARGO => "Cargo",
            self::TABLE_BANCO => "Banco",
            self::TABLE_TIPO_ARQUIVO => "Tipo de documento",
            self::TABLE_CLIENTE => "Dados do Cliente",
            self::TABLE_STATUS_CONTATO => "Situação do Contato",
            self::TABLE_INDICE => "Indice de Reajuste",
    );

    /**
     * @return string;
     *
     * @description Retorna a descrição do campo
     */
    public function getDescricao() {
        return $this->descricao;
    }
    /**
     * @param $idCampo the $idCampo to set
     */
    public function setIdCampo($idCampo) {
        $this->idCampo = $idCampo;
    }

    /**
     * @param $idTabela the $idTabela to set
     */
    public function setIdTabela($idTabela) {
        $this->idTabela = $idTabela;
    }

    /**
     * @param $idIdioma the $idIdioma to set
     */
    public function setIdIdioma($idIdioma) {
        $this->idIdioma = $idIdioma;
    }

    /**
     * @return the $idCampo
     */
    public function getIdCampo() {
        return $this->idCampo;
    }

    /**
     * @return the $idTabela
     */
    public function getIdTabela() {
        return $this->idTabela;
    }

    /**
     * @return the $idIdioma
     */
    public function getIdIdioma() {
        return $this->idIdioma;
    }


    /**
     * @return string
     *
     * @description Retorna o complemento do campo
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @return boolean
     *
     * @description Retorna se o campo é fixo
     */
    public function getFixo() {
        return $this->fixo;
    }

    /**
     * @return string
     *
     * @description Retorna o último log de alteração
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @param string $complemento
     */
    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    /**
     * @param boolean $fixo
     */
    public function setFixo($fixo) {
        $this->fixo = $fixo;
    }

    /**
     * @param string $log
     */
    public function setLog($log) {
        $this->log = $log;
    }

    /**
     * @return Db_TabelaAgrupada
     *
     * @param boolean $idioma, string $tabela, string $campo
     */
    public static function find($idioma, $tabela, $campo) {
        $db = DB::getInstance();

        $stmt = $db->prepare("SELECT idIdioma, idTabela, idCampo, descricao, complemento, fixo, log
		                      FROM ".self::TABLE_NAME." 
		                      WHERE idIdioma = ? AND idTabela = ? AND idCampo = ?");

        $param = array($idioma,$tabela,$campo);

        
        $stmt->execute($param);
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        
        return $stmt->fetch();
    }


    public static function fullSearch($termo, $count = false, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();
        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM ".self::TABLE_NAME."
				   WHERE CONCAT(idTabela,' ',UPPER(idCampo),' ',UPPER(descricao),' ',UPPER(complemento),' ',fixo) LIKE {$termo} AND idIdioma = '1'";
        } else {
            $select = "SELECT * FROM ".self::TABLE_NAME."
				   WHERE CONCAT(idTabela,' ',UPPER(idCampo),' ',UPPER(descricao),' ',UPPER(complemento),' ',fixo) LIKE {$termo} AND idIdioma = '1'";
            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";
        }
        $stmt = $db->prepare($select);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        return $stmt->fetchAll();
    }

    /**
     * @return boolean
     *
     * @description Salva TabelaAgrupadaDb no banco de dados
     */
    public function save() {
        $db = DB::getInstance();
        $insert = "INSERT INTO ".self::TABLE_NAME." VALUES (:idIdioma, :idTabela, :idCampo, :descricao, :complemento, :fixo, :log)";
        $insert .=" ON DUPLICATE KEY UPDATE idCampo = :idCampo , descricao = :descricao, complemento = :complemento, fixo = :fixo, log = :log";

        $stmt = $db->prepare($insert);
        $param = array(
                "idIdioma" 		=> $this->getIdIdioma(),
                "idTabela" 		=> $this->getIdTabela(),
                "idCampo" 		=> $this->getIdCampo(),
                "descricao"             => $this->getDescricao(),
                "complemento"           => $this->getComplemento(),
                "fixo" 			=> $this->getFixo(),
                "log"			=> $this->getLog()
        );

        return $stmt->execute($param);

    }

    public static function delete($idioma, $tabela, $campo) {
        $db = Db::getInstance();
        $delete  = "DELETE FROM ".self::TABLE_NAME."
WHERE idIdioma = ? AND idTabela = ? AND idCampo = ?";
        $stmt = $db->prepare($delete);
        $param = array($idioma,$tabela,$campo);

        return $stmt->execute($param);
    }


    /**
     *
     * @param string $campo
     * @param string $valor
     * @param string $order [opcional]
     * @desc
     * Retorna o uma consulta através dos parametros campo e valor
     * Obs.: o campo order tem a seguinte forma: "campo [DESC]"
     */
    public static function findByField($campo,$valor,$order = null,$fetchMode = PDO::FETCH_CLASS) {
        $db = Db::getInstance();
        $select = "SELECT * FROM " . self::TABLE_NAME."
        WHERE {$campo} = ".$valor." " ;
        if($order != null)
            $select .= "ORDER BY ".$order;
        $stmt = $db->prepare($select);
        $stmt->setFetchMode($fetchMode,__CLASS__);
        $stmt->execute();

        return $stmt->fetchAll();

    }

    public static function fetchAll($count = false, $offset = false, $page = false) {
        $db = Db::getInstance();
        if($count && $offset == false && $page == false) {
            $select = "SELECT count(*) AS count FROM " . self::TABLE_NAME;
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME;
            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";
        }
        $stmt = $db->prepare($select);
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $stmt->execute();

        return $stmt->fetchAll();

    }


}

