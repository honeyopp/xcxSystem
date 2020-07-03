<?php
namespace app\home\controller;
class Member extends Common{
    protected $footer = 5;
    public function index(){
        return $this->fetch();
    }
}