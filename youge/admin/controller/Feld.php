<?php
namespace app\admin\controller;


use think\Config;
use think\Db;

class Feld extends Common{


    /*
     * 数据库 字段 注释 生成；
     */

    public function feld(){
            if($this->request->method() == "POST"){
                $table_name = (string) $this->request->param('table_name');
                $_table_name = $table_name;
            $table_name=Config::get('database.prefix').$table_name;

            $query = Db::connect(Config::get('database'));

            $sql = "select * from information_schema.columns where TABLE_SCHEMA = '" . Config::get('database.database') . "' AND TABLE_NAME = '{$table_name}'";
            $data = $query->query($sql);
            $this->assign('data',$data);
            $this->assign('table_name',$_table_name);
                return $this->fetch();
       // var_dump($data[0]['COLUMN_NAME']);die;
        //var_dump($data[3]['COLUMN_COMMENT']);die;
        }else{
              $data = [];
                $this->assign('table_name','');
              $this->assign('data',$data);
            return $this->fetch();
        }
    }


    public function file(){
            $table_name = (string) $this->request->param('table_name');

             if (empty($table_name)) {
                $this->error('请输入数据表名称再载入');
            }
            $table_name=Config::get('database.prefix').$table_name;

            $query = Db::connect(Config::get('database'));
            $dir =  $table_name . '.txt';
            $sql = "select * from information_schema.columns where TABLE_SCHEMA = '" . Config::get('database.database') . "' AND TABLE_NAME = '{$table_name}'";
            $data = $query->query($sql);
             foreach ($data as $val){
                 file_put_contents($dir,"字段名:{$val['COLUMN_NAME']}".PHP_EOL . "注释:{$val['COLUMN_COMMENT']}" .PHP_EOL.PHP_EOL.PHP_EOL,FILE_APPEND);
             }
            $this->error("生成成功根目录下/{$table_name}/文件");
            // var_dump($data[0]['COLUMN_NAME']);die;
            //var_dump($data[3]['COLUMN_COMMENT']);die;

    }

}