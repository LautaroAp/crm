<?php

namespace CuentaCorriente\Controller;
use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;
use DBAL\Entity\CuentaCorriente;

class CuentaCorrienteController extends HuellaController
{

   protected $cuentaCorrienteManager;
   private $ivaManager;

    public function __construct($cuentaCorrienteManager,$ivaManager){
        $this->cuentaCorrienteManager = $cuentaCorrienteManager;
        $this->ivaManager= $ivaManager;
    }
        
    private function busqueda($params){
        return ((isset($params['tipo']) and $params['tipo']!="-1") or (isset($params['nombre'])));
    }

    private function agregar($params){
        if ((isset($params['cantidad']) and isset($params['subtotal'])) and isset($params['idbien'])){
            return($params['cantidad']>0);          
        }
        else return false;
    }

    public function indexAction() {
       
        $ventas = $this->cuentaCorrienteManager->getVentas();
        $cobros = $this->cuentaCorrienteManager->getCobros();
        $view = new ViewModel([
            'cobros' => $cobros,
            'ventas'=>$ventas,
            // 'id_persona'=>$id_persona
        ]);
        return $view;
    }

}
