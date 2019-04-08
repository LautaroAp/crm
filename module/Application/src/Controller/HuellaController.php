<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DBAL\Entity\Empresa;


class HuellaController extends AbstractActionController
{

    protected $breadcrumbs;
    protected $entityManager;
    protected $elemsPag;
    protected $bienesTransacciones;
    

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $breadcrumbs = array();
        $bienesTransacciones= array();
    }

    protected function pertenece($arr, $nuevo)
    {
        return (in_array($nuevo, $arr));
    }

    protected function perteneceLabel($arr, $label)
    {
        for ($i = 0; $i < (sizeof($arr)); $i++) {
            if (($arr[$i]['label']) == $label) {
                return true;
            }
        }
         return false;
    }

    protected function esUltimo($arr, $label)
    {
        return ((end(($arr))['label']) == $label);
    }

    public function prepararBreadcrumbs($label, $url, $limite = null)
    {

        $data = $_SESSION['breadcrumb'];
        $data_decoded = json_decode($data, true);
        $agregar = ['label' => $label, 'url' => $url];
        $rutas = $data_decoded['route'];   
        //solo agrega rutas si no estan agregadas
        if ((!$this->pertenece($rutas, $agregar))) {
             //si tiene un limite pasado con el ultimo valor posible eliminar las sig migas   
            if (isset($limite)) {
                $json = $rutas = $this->eliminarUltimos($rutas, $limite);
            }
            //con la ultima miga valida agregar al arreglo la nueva miga
             array_push($rutas, $agregar);
            //reemplazo el arreglo de rutas del archivo json por el nuevo arreglo modificado
            $json = $this->guardarJson($rutas, $data_decoded);

        } else {
            $rutas = $this->eliminarUltimos($rutas, $agregar['label']);
            $json = $this->guardarJson($rutas, $data_decoded);
        }
        $this->layout()->setVariable('rutas', $rutas);
        $this->layout()->setVariable('json', $json);
    }

    private function guardarJson($rutas, $data_decoded)
    {
        $data_decoded['route'] = $rutas;
        $json = json_encode($data_decoded);
       // file_put_contents("public/json/breadcrumbs.json", $json);
        $_SESSION['breadcrumb'] = $json;
        return $json;

    }

    protected function eliminarUltimos($arr, $limite)
    {
        //EXTRAE ELEMENTOS DEL ARREGLO HASTA QUE SE LLEGA AL ULTIMO QUE SE QUIERE DEJAR
        //O EL ARREGLO QUEDA VACIO.  -->sino hacerlo parar en home
        // if ($this->perteneceLabel($arr, $limite)) {
            while (((end(($arr))['label']) != $limite) and (end(($arr))['label']) != "Home") {
                $eliminado = array_pop($arr);
             }
        // } else {
        //     $arr = $this->reiniciarBreadcrumbs("Home", "/");
        // }
        return $arr;
    }


    public function reiniciarBreadcrumbs($label, $url)
    {
        //este metodo borra el arreglo de breadcrums acumulados y genera uno nuevo 
        //con la ruta inicial Home.
        if ($label == "Home") {
            $nuevo = ['route' => [['label' => $label, 'url' => $url]]];
            $json = json_encode($nuevo);
            $_SESSION['breadcrumb'] = $json;
            $this->layout()->setVariable('rutas', $nuevo);
        }
        return $nuevo;
    }

    public function getAnterior()
    {
        $bread = ((array)(json_decode($_SESSION['breadcrumb'])));
        $ultimo = ((array)end($bread['route']));
        $limite = $ultimo['label'];
        return $limite;
    }

    public function getElemsPag()
    {
        return $_SESSION['ELEMSPAG'];
    }

    public function getUltimaUrl()
    {
        $breads = ((array)(json_decode($_SESSION['breadcrumb'])))['route'];
        $ultimo = $this->getAnterior();
        $resultado = "";
        if (COUNT($breads) > 2) {
            for ($i = 1; $i < COUNT($breads) - 1; $i++) {
                $crumb = (array)($breads[$i]);
                if ($crumb['label'] != $ultimo) {
                    $url = $crumb['url'];
                    $resultado = $resultado . $url;
                }
            }
        } else {
            $resultado = "/";
        }
        return $resultado;
    }

    protected function reiniciarParametros($arreglo){
        $_SESSION[$arreglo] = array();
        $bienesTransacciones= array();
    }

    protected function limpiarParametros($param) {
        foreach ($param as $filtro => $valor) {
            if ($filtro != 'busquedaAvanzada'){
                 if (empty($valor)) {
                unset($param[$filtro]);
                 } else {
                trim($param[$filtro]);
                 }
            }
            else{
                $param[$filtro]=true;
            }
        }
        return ($param);
    }

    protected function getJsonFromObjectList($objects){
        $json="";
        foreach ($objects as $obj) {
            $json .= $obj->getJSON() . ',';
        }
        $json = substr($json, 0, -1);
        $json = '[' . $json . ']';
        return $json;
    }

}