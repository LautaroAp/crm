<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HuellaController extends AbstractActionController {

    protected $breadcrumbs;
    private $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
        $breadcrumbs= array();
    }

    protected function pertenece ($arr, $nuevo){
        return (in_array($nuevo, $arr));
    }

    protected function esUltimo($arr, $label){
        return ((end(($arr))['label'])== $label);
    }

    public function prepararBreadcrumbs($label, $url, $limite = null){
        $data = file_get_contents("public/json/breadcrumbs.json");
        $data_decoded =  json_decode($data, true);
        // print_r("<br>");
        // print_r("<br>");
        // print_r("<br>");
        // print_r("<br>");
        // // print_r($data_decoded);
        // print_r("<br>");
        $agregar = array('label' => $label, 'url' => $url);
        $rutas=$data_decoded['route'];   
        //solo agrega rutas si no estan agregadas
        if ((!$this->pertenece($rutas, $agregar))){
             //si tiene un limite pasado con el ultimo valor posible eliminar las sig migas   
            if (isset($limite)){
                $rutas=$this->eliminarUltimos($rutas, $limite);
            }
            //con la ultima miga valida agregar al arreglo la nueva miga
            array_push($rutas, $agregar);
            //reemplazo el arreglo de rutas del archivo json por el nuevo arreglo modificado
            $this->guardarJson($rutas, $data_decoded);
            
        }
        else{
            $rutas = $this->eliminarUltimos($rutas, $agregar['label']);
            $this->guardarJson($rutas, $data_decoded);
        }  
        $this->layout()->rutas = $rutas;
    }
    
    private function guardarJson($rutas,$data_decoded){
        $data_decoded['route'] = $rutas;
        $json = json_encode($data_decoded);
        file_put_contents("public/json/breadcrumbs.json", $json);
        // $data = file_get_contents("public/json/breadcrumbs.json");
        // $data_decoded =  json_decode($data, true);
        // // print_r($data_decoded);
        // // print_r("<br>");

    }

    protected function eliminarUltimos($arr, $limite){
        //EXTRAE ELEMENTOS DEL ARREGLO HASTA QUE SE LLEGA AL ULTIMO QUE SE QUIERE DEJAR
        //O EL ARREGLO QUEDA VACIO.  -->sino hacerlo parar en home
        while (((end(($arr))['label'])!= $limite)){
            $eliminado = array_pop($arr);
        }
        return $arr;
    }

    public function reiniciarBreadcrumbs($label, $url){
        //este metodo borra el arreglo de breadcrums acumulados y genera uno nuevo 
        //con la ruta inicial Home.
        if ($label=="Home"){
            $data = file_get_contents("public/json/breadcrumbs.json");
            $data_decoded =  json_decode($data, true);
            //lo vuelvo un nuevo arreglo vacio
            $data_decoded['route']= Array();
            $arr = array('label' => $label, 'url' => $url);
            //le agrego la ruta y label de home
            array_push($data_decoded['route'], $arr);
            $json = json_encode($data_decoded);
            file_put_contents("public/json/breadcrumbs.json", $json);
            $data = file_get_contents("public/json/breadcrumbs.json");
            $data_decoded =  json_decode($data, true);
        }
    }
}