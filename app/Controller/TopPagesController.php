<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/04/02
 * Time: 午前8:52
 */

class TopPagesController extends AppController{
    public function index(){
        $this->ext = '.html';
        $this->render('index');
    }


}