<?php

class Arquivo {

  private $_fileName = null;
  private $_conteudo = null;

  /**
   * Construção da classe.
   * @param string $name
   */
  public function __construct($name = null) {
    if(!$name){
      $name = strtotime(date('d-m-Y')).'.txt';
    }
    $this->_fileName = $name;
  }

  /**
   * Metodo responsável por escrever no arquivo.
   * @param string conteudo
   * @return void
   */
  public function append($conteudo) {

    $this->_conteudo .= $conteudo;
  }

  /**
   * Metodo que retorna o conteúdo de um arquivo.
   * @return string $conteudo
   */
  public function getContent() {

    return $this->_conteudo;
  }

  /**
   * Metodo que retorna o caminho do arquivo.
   * @return string $fileName
   */
  public function getFileName() {

    return $this->_fileName;
  }

  /**
   * Metodo responsável pelo salvamento do arquivo.
   * @param string diretorio
   * @return boolean
   */
  public function save($diretorio = null, $fileName = null) {

    if($fileName){
      $this->_fileName = $fileName;
    }
    if($diretorio){
        $path = $diretorio;
    }else{
        $path = DIRETORIO_DOWNLOAD;
    }

    if(!$fp = fopen($path.'/'.$this->_fileName, 'w+')){
      return false;
    }else{
      fwrite($fp, $this->_conteudo);
      fclose($fp);
    }
    return true;
  }

  /**
   * Metodo responsável carregar um arquivo.
   * @param string arquivo origem
   * @return void
   */
  public function load($arquivoOrigem) {

    if(!$fp = fopen(DIRETORIO_UPLOAD.DIRECTORY_SEPARATOR.$arquivoOrigem)){
        return false;
    }else{
      $this->_conteudo = fread ($fp, filesize($arquivoOrigem));
      $this->_fileName = $arquivoOrigem;
      fclose($fp);
    }
    $this->_conteudo .= $_conteudo;
  }
  

}

