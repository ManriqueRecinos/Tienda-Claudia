<?php
namespace app\controllers;
use app\models\viewsModel;

class viewsController extends viewsModel{
    public function obtenerVistasController($vista){
        if($vista != ""){
            $respuesta = $this->obtenerVistasModelo($vista);
        }else{
            $respuesta = "login";
        }
        return $respuesta;
    }
}
?>