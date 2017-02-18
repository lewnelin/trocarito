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

    const SESSION_NAME = "painelDeVenda";

    public function autenticar()
    {
        $this->usuario = Db_Usuario::fetchByLogin($this->login, $this->senha);

        if ($this->usuario) {
            if ($this->checarLimiteHorario() && $this->checarLimiteHorario()) {
                $_SESSION[self::SESSION_NAME] = $this->usuario;
            } else {
                throw new LoginOutOfTimeException();
            }
        } else {
            throw new LoginNotMatchException();
        }
    }

    public function checarDataLimite()
    {
        $user = $this->usuario;
        return ($user->getDataExpiracao() && $user->getDataExpiracao() >= date("Y-m-d H:i:s"));
    }

    /**
     * Renora se o usu�rio est� em um hor�rio v�lido
     * @return boolean
     */
    public function checarLimiteHorario()
    {
        $user = $this->usuario;
        $hora = date("H:i:s");
        if ($user->getHoraInicio() && $user->getHoraTermino()) {
            if ($hora >= $user->getHoraInicio() && $hora <= $user->getHoraTermino()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Verifica se o usuário está logado e se está dentro do seu horário
     * @return bool
     */
    public static function isLogado()
    {
        $usuario = self::getUsuario();
        $hora = date("H:i:s");
        if ($usuario && $usuario instanceof Db_Usuario) {
            if ($hora >= $usuario->getHoraInicio() && $hora <= $usuario->getHoraTermino()) {
                return true;
            }
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