<?php

class Controller {
    public function model($model){
        require('../app/models/'.$model.'.php');
        return new $model;
    }

    public function view($url, $data = []){
        if(file_exists('../app/views/'.$url.'.php')){
            require('../app/views/'.$url.'.php');
        }
    }
}