<?php


namespace thinkcms\auth\controller;



class Rbac
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function menu(){
        return VIEW_PATH.'menu.php';
    }

}