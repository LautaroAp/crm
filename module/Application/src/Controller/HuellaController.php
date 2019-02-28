<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

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
        $data = $_SESSION['breadcrumb'];
        $data_decoded =  json_decode($data, true);
        $agregar = ['label' => $label, 'url' => $url];
        $rutas=$data_decoded['route'];   
      
        //solo agrega rutas si no estan agregadas
        if ((!$this->pertenece($rutas, $agregar))){
             //si tiene un limite pasado con el ultimo valor posible eliminar las sig migas   
            if (isset($limite)){  
                
                $json= $rutas=$this->eliminarUltimos($rutas, $limite);
            }
            //con la ultima miga valida agregar al arreglo la nueva miga
            array_push($rutas, $agregar);
            //reemplazo el arreglo de rutas del archivo json por el nuevo arreglo modificado
            $json = $this->guardarJson($rutas, $data_decoded);
            
        }
        else{
            $rutas = $this->eliminarUltimos($rutas, $agregar['label']);
            $json = $this->guardarJson($rutas, $data_decoded);
        }  
        // print_r("<br>");
        // print_r("<br>");
        // print_r("<br>");
        // print_r($rutas);
        $this->layout()->setVariable('rutas', $rutas);
        $this->layout()->setVariable('json', $json); 
    }
    
    private function guardarJson($rutas,$data_decoded){
        $data_decoded['route'] = $rutas;
        $json = json_encode($data_decoded);
        // file_put_contents("public/json/breadcrumbs.json", $json);
        $_SESSION['breadcrumb']=$json;
        return $json;

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
            $nuevo = ['route'=>[['label'=>$label, 'url'=>$url]]];
            $json= json_encode($nuevo);
            $_SESSION['breadcrumb']= $json;  
            $this->layout()->setVariable('rutas', $nuevo);
        }
    }

    public function getAnterior(){
        $bread= ((array)(json_decode($_SESSION['breadcrumb'])));
        $ultimo = ((array)end($bread['route']));
        $limite= $ultimo['label'];
        return $limite;
    }
}