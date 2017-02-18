<?php

class IndexController extends Controller
{

    public function indexAction()
    {

        $this->redir(array('modulo'=>'dashboard', "controller" => "dashboard", "action" => "index"));

    }

}
