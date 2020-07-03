<?php
namespace app\home\controller;
class Faq extends Common{
    protected $footer = 4;
    public function index(){
        return $this->fetch();
    }
}