<?php

class LoginOutOfTimeException extends Exception
{
}

class LoginNotMatchException extends Exception
{
}

class Login
{

    private $usuario;
    private $login;
    private $senha;

    const SESSION_NAME = "trocarito";

    public function autenticar()
    {
        $this->usuario = Db_Usuario::fetchByLogin($this->login, $this->senha);

        if (!$this->usuario) {
            throw new LoginNotMatchException();
        }
    }

    /**
     * Verifica se o usuário está logado e se está dentro do seu horário
     * @return bool
     */
    public static function isLogado()
    {
        $usuario = self::getUsuario();

        if ($usuario && $usuario instanceof Db_Usuario) {
                return true;

            return false;
        } else return false;
    }

    public static function getUsuario()
    {
        return isset($_SESSION[self::SESSION_NAME]) ? $_SESSION[self::SESSION_NAME] : false;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public static function logout()
    {
        unset($_SESSION[self::SESSION_NAME]);
        return;
    }
}