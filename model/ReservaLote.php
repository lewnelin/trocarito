<?php

class ReservaLote extends Zend_Db_Table_Abstract{

    protected $_name = TB_RESERVA_LOTE;
    static $reservaLoteInstancia;

    //retorna uma instancia da classe
    public static function getInstance()
    {
        if (!self::$reservaLoteInstancia) {
            self::$reservaLoteInstancia = new Lote();
        }
        return self::$reservaLoteInstancia;
    }

    public function getReservaLote($id){
        return $this->fetchRow("id = " . $id);
    }

    /**
     * Função responsável por alterar a flag de reservado para 0
     *
     * @param $idLote
     */
    public function alterarReservaLote($idLote)
    {
        $reservaLote = $this->fetchRow('cod_lote = '.$idLote . ' AND reservado = "1"');
        $reservaLote->reservado = 0;
        $reservaLote->save();
    }

    //procura as reservas de lote por empreendimento
    public function findReservaEmpreendimento($id) {
        $reservasLote = $this::getInstance();
        $reservasLote = $reservasLote->getDefaultAdapter()->select()
            ->from(array('rl' => TB_RESERVA_LOTE))
            ->join(array('l' => TB_LOTES), 'rl.cod_lote = l.id AND rl.reservado = "1"', array('l.id', 'l.id_empreendimento'))
            ->where('l.id_empreendimento = ?', $id);

        $reservasLote = $reservasLote->query()->fetchAll();

        if($reservasLote) {
            return $reservasLote;
        } else return null;
    }
}

