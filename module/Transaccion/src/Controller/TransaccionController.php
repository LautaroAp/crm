<?php

/**
 * Clase actualmente sin uso
 */

namespace Transaccion\Controller;
use Transaccion\Service\TransaccionManager;
use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;



abstract class TransaccionController extends HuellaController {

   
    /**
     *El manager puede ser una instancia de cualquiera de las clases que heredan de transaccion
     */
    protected $manager;
    protected $personaManager;
    protected $monedaManager;
    protected $ivaManager;
    protected $formaPagoManager;
    protected $formaEnvioManager;
    protected $empresaManager;
    
    public function __construct($manager, $personaManager, $monedaManager,$ivaManager, $formaPagoManager, $formaEnvioManager, $empresaManager) {
        $this->manager = $manager;
        $this->personaManager= $personaManager;
        $this->monedaManager = $monedaManager;
        $this->ivaManager= $ivaManager;
        $this->formaPagoManager = $formaPagoManager;
        $this->formaEnvioManager = $formaEnvioManager;
        $this->empresaManager = $empresaManager;
    }

    // public function indexAction(){
    // }

    public function indexAction() {
       
        // $ventas = $this->cuentaCorrienteManager->getVentas();
        // $cobros = $this->cuentaCorrienteManager->getCobros();
        $view = new ViewModel([
            // 'cobros' => $cobros,
            // 'ventas'=>$ventas,
            // 'id_persona'=>$id_persona
        ]);
        return $view;
    }

    public abstract function addAction();
    public abstract function editAction();

    protected function reiniciarParams(){
        $_SESSION['BIENES'] = null;
        $_SESSION['PARAMETROS_BIENES']=null;
    }
    //este metodo se llama desde pedido, remito, presupuesto (no esta declarado en las clases hijas)
    public function procesarAddAction($data){
        $this->reiniciarParams();
        $this->manager->add($data);
    }

    public function procesarEditAction($transaccion, $data){
        $this->reiniciarParams();
        $this->manager->edit($transaccion, $data);
    }

    public function getManager(){
        return $this->manager;
    }
    
    // protected function getItemsArray($items){
    //     $salida = array();
    //     foreach ($items as $item){
    //         array_push($salida, $item->toArray());
    //     }
    //     return $salida;
    // }

    public function getJsonMonedas()
    {
        $monedas = $this->monedaManager->getMonedas();
        $json = $this->getJsonFromObjectList($monedas);
        return $json;
    }

    public function getJsonFormasPago()
    {
        $formasPago = $this->formaPagoManager->getFormasPago();
        $json = $this->getJsonFromObjectList($formasPago);
        return $json;
    }

    public function getJsonIvas(){
        $ivas = $this->ivaManager->getIvas();
        $json = $this->getJsonFromObjectList($ivas);
        return $json;
    }

}
