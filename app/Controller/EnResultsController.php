<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/26
 * Time: 午後1:29
 */

class EnResultsController extends AppController{
    public $scaffold;

    public function index(){
        //view指定
        $this->ext = '.html';
        $this->render('index');

    }

    public function basic(){

//        $this->set('')
        $this->EnResult->getResult('basic');
        $this->ext = '.html';
        $this->render('basic');
    }
}