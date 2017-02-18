<?php
class Db_Funcionario {
	
	private $id;
	private $ctps;
	private $serie;
	private $pis;
	private $telefoneRecado;
	private $endereco;
	private $bairro;
	private $idCidade;
	private $mae;
	private $pai;
	private $localNascimento;
	private $conjuge;
	private $ultimoEmprego;
	private $dataUltimoEmprego;
	private $dataAdmissao;
	private $cargo;
	private $salarioBruto;
	private $valeTransporte;
	private $obs;
	private $dataDesligamento;
	private $motivoDesligamento;
	private $manequim;
	private $calcado;
	private $grauInstrucao;
	private $indicacao;
	private $status;
	private $log;
	private $gradeId;
	
	
	const TABLE_NAME = "RH_FUNCIONARIO";	

	public function setCargo($cargo) {
		$this->cargo = $cargo;
	}

	public function setGradeId($gradeId) {
		$this->gradeId = $gradeId;
	}

	public function getABLE_NAME() {
		return $this->TABLE_NAME;
	}

	public function getGradeId() {
		return $this->gradeId;
	}

	public function setBairro($bairro) {
		$this->bairro = $bairro;
	}

	public function setEndereco($endereco) {
		$this->endereco = $endereco;
	}

	public function getBairro() {
		return $this->bairro;
	}

	public function getEndereco() {
		return $this->endereco;
	}

	public function setIndicacao($indicacao) {
		$this->indicacao = $indicacao;
	}

	public function getCargo() {
		return $this->cargo;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setGrauInstrucao($grauInstrucao) {
		$this->grauInstrucao = $grauInstrucao;
	}

	public function setCalcado($calcado) {
		$this->calcado = $calcado;
	}

	public function setManequim($manequim) {
		$this->manequim = $manequim;
	}

	public function setMotivoDesligamento($motivoDesligamento) {
		$this->motivoDesligamento = $motivoDesligamento;
	}

	public function setDataDesligamento($dataDesligamento) {
		$this->dataDesligamento = $dataDesligamento;
	}

	public function setObs($obs) {
		$this->obs = $obs;
	}

	public function setValeTransporte($valeTransporte) {
		$this->valeTransporte = $valeTransporte;
	}

	public function setSalarioBruto($salarioBruto) {
		$this->salarioBruto = $salarioBruto;
	}

	public function setDataAdmissao($dataAdmissao) {
		$this->dataAdmissao = $dataAdmissao;
	}

	public function setDataUltimoEmprego($dataUltimoEmprego) {
		$this->dataUltimoEmprego = $dataUltimoEmprego;
	}

	public function setUltimoEmprego($ultimoEmprego) {
		$this->ultimoEmprego = $ultimoEmprego;
	}

	public function setConjuge($conjuge) {
		$this->conjuge = $conjuge;
	}

	public function setLocalNascimento($localNascimento) {
		$this->localNascimento = $localNascimento;
	}

	public function setPai($pai) {
		$this->pai = $pai;
	}

	public function setMae($mae) {
		$this->mae = $mae;
	}

	public function setIdCidade($idCidade) {
		$this->idCidade = $idCidade;
	}

	public function setTelefoneRecado($telefoneRecado) {
		$this->telefoneRecado = $telefoneRecado;
	}

	public function setPis($pis) {
		$this->pis = $pis;
	}

	public function setSerie($serie) {
		$this->serie = $serie;
	}

	public function setCtps($ctps) {
		$this->ctps = $ctps;
	}

	public function setLog($log) {
		$this->log = $log;
	}

	public function getLog() {
		return $this->log;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getIndicacao() {
		return $this->indicacao;
	}

	public function getGrauInstrucao() {
		return $this->grauInstrucao;
	}

	public function getCalcado() {
		return $this->calcado;
	}

	public function getManequim() {
		return $this->manequim;
	}

	public function getMotivoDesligamento() {
		return $this->motivoDesligamento;
	}

	public function getDataDesligamento() {
		
		$return = $this->dataDesligamento;
		if ($return == '0000-00-00')
			$return = null;

		return $return;
			
	}

	public function getObs() {
		return $this->obs;
	}

	public function getValeTransporte() {
		return $this->valeTransporte;
	}

	public function getSalarioBruto() {
		return $this->salarioBruto;
	}

	public function getDataAdmissao() {

		$return = $this->dataAdmissao;
		if ($return == '0000-00-00')
			$return = null;

		return $return;
	}

	public function getDataUltimoEmprego() {
		$return = $this->dataUltimoEmprego;
		if ($return == '0000-00-00')
			$return = null;

		return $return;
	}

	public function getUltimoEmprego() {
		return $this->ultimoEmprego;
	}

	public function getConjuge() {
		return $this->conjuge;
	}

	public function getLocalNascimento() {
		return $this->localNascimento;
	}

	public function getPai() {
		return $this->pai;
	}

	public function getMae() {
		return $this->mae;
	}

	public function getIdCidade() {
		return $this->idCidade;
	}

	public function getTelefoneRecado() {
		return $this->telefoneRecado;
	}

	public function getPis() {
		return $this->pis;
	}

	public function getSerie() {
		return $this->serie;
	}

	public function getCtps() {
		return $this->ctps;
	}

	public function getId() {
		return $this->id;
	}

	public function getStatus() {
		return $this->status;
	}
	
	public function save(){
		$db = Db::getInstance();
		$insert = "INSERT INTO " . self::TABLE_NAME . " 
				   VALUES (:id, :ctps, :serie, :pis, :telefoneRecado, :endereco, :bairro, :idCidade,
						   :mae, :pai, :localNascimento, :conjuge, :ultimoEmprego, :dataUltimoEmprego,
						   :dataAdmissao, :cargo, :salarioBruto, :valeTransporte, :obs, :dataDesligamento, :manequim, 
						   :motivoDesligamento, :calcado, :grauInstrucao, :indicacao, :status, :log, :gradeId)";
		
		$insert .= " ON DUPLICATE KEY UPDATE   ctps = :ctps, serie = :serie, pis = :pis, telefoneRecado = :telefoneRecado,
											   endereco = :endereco, bairro = :bairro, idCidade = :idCidade, mae = :mae, pai = :pai,
											   localNascimento = :localNascimento, conjuge = :conjuge, ultimoEmprego = :ultimoEmprego,
											   dataUltimoEmprego = :dataUltimoEmprego, dataAdmissao = :dataAdmissao, cargo = :cargo, 
											   salarioBruto = :salarioBruto, valeTransporte = :valeTransporte, obs = :obs, dataDesligamento = :dataDesligamento,
											   manequim = :manequim, calcado = :calcado, grauInstrucao = :grauInstrucao, indicacao = :indicacao,
											   status = :status, log = :log, gradeId = :gradeId";

		
		
		$stmt = $db->prepare($insert);
		
		//atribui nulos para os campos com chave estrangeira
		if($this->getIdCidade()=="")
			$this->setIdCidade(null);

		if($this->getLocalNascimento()=="")
			$this->setLocalNascimento(null);

		if($this->getGradeId()=="")
			$this->setGradeId(null);
			
		
		$param = array(
			"id" => $this->getId(),
			"ctps" => $this->getCtps(),
			"serie" => $this->getSerie(),
			"pis" => $this->getPis(),
			"telefoneRecado" => $this->getTelefoneRecado(),
			"endereco" => $this->getEndereco(),
			"bairro" => $this->getBairro(),
			"idCidade" => $this->getIdCidade(),
			"mae" => $this->getMae(),
			"pai" => $this->getPai(),
			"localNascimento" => $this->getLocalNascimento(),
			"conjuge" => $this->getConjuge(),
			"ultimoEmprego" => $this->getUltimoEmprego(),
			"dataUltimoEmprego" => $this->getDataUltimoEmprego(),
			"dataAdmissao" => $this->getDataAdmissao(),
			"cargo" => $this->getCargo(),
			"salarioBruto" => $this->getSalarioBruto(),
			"valeTransporte" => $this->getValeTransporte(),
			"obs" => $this->getObs(),
			"dataDesligamento" => $this->getDataDesligamento(),
			"manequim" =>$this->getManequim(),
			"motivoDesligamento" => $this->getMotivoDesligamento(),
			"calcado" => $this->getCalcado(),
			"grauInstrucao" => $this->getGrauInstrucao(),
			"indicacao" => $this->getIndicacao(),
			"status" => $this->getStatus(),
			"log" => $this->getLog(),
			"gradeId" => $this->getGradeId());
	
		$stmt->execute($param);
	}

	
	public static function find($id){
		$db = Db::getInstance();
		$filtro = array($id);
		$select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE id = ?";
		$stmt = $db->prepare($select);
		$stmt->execute($filtro);
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetch();
	}
	
	public static function findByField($campo, $valor){
		$db = Db::getInstance();
		$filtro = array($valor);
		$select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE {$campo} = ?";
		$stmt = $db->prepare($select);
		$stmt->execute($filtro);
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetchAll();
	}
		
	public static function fullSearch($termo){
		$result = array();
		$termo = "'%".strtoupper($termo)."%'";
		$db = DB::getInstance();
		$select = "SELECT f.id FROM ".self::TABLE_NAME." f
				   JOIN SC_PESSOA p ON f.id = p.id
				   WHERE CONCAT(f.id,' ',UPPER(p.nome)) LIKE {$termo} and status = '1'";
		$stmt = $db->prepare($select);
		$stmt->execute();
		$ids = $stmt->fetchAll();
		if(is_array($ids)){
			foreach ($ids as $id){
				$result[] = self::find($id["id"]);
			}
		}

		$select = "SELECT f.id FROM ".self::TABLE_NAME." f
				   JOIN SC_TABELA_AGRUPADA t ON t.idCampo = f.cargo
				   WHERE t.descricao LIKE {$termo} and f.status = '1' and idTabela = 6";
		$stmt = $db->prepare($select);
		$stmt->execute();
		$ids2 = $stmt->fetchAll();
		if(is_array($ids2)){
			foreach ($ids2 as $id){
				if(!in_array($id,$ids))
					$result[] = self::find($id["id"]);
			}
		}
		
		
		
		return $result;
	}
	
	public static function delete($id){
		$db = Db::getInstance();
		$delete  = "UPDATE ".self::TABLE_NAME."
					SET status = '0' 
					WHERE id = ?";
		$stmt = $db->prepare($delete);
		return $stmt->execute(array($id));
	}
	
	public static function fetchAll($where = null){
		$db = Db::getInstance();
		$filtro = array();
		$select = "SELECT * FROM " . self::TABLE_NAME;
		if($where) $select .= " WHERE ".$where;
		$stmt = $db->prepare($select);
		$stmt->execute($filtro);
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetchAll();		
	}
	
	public static function fetchAllAdmitidos($grade = null, $data = null){
		//@never used param
		$data = $data;
		$where = "(dataDesligamento = '' OR dataDesligamento = '0000-00-00') AND status = '1'";
		if($grade){
			$grade = (int) $grade;
			$where .= " AND gradeId = {$grade}";
		}
		return self::fetchAll($where);
	}

}