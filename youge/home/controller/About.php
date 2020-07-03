<?php
namespace app\home\controller;
class About extends Common{
    protected $footer = 6;
    public function index(){
        return $this->fetch();
    }
}