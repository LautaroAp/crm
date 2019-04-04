<?php

/**
 * Clase actualmente sin uso
 */

namespace Transaccion\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

abstract class TransaccionController extends HuellaController {

   
    /**
     *El manager puede ser una instancia de cualquiera de las clases que heredan de transaccion
     */
    protected $manager;
    protected $personaManager;

    public function __construct($manager, $personaManager) {
        $this->manager = $manager;
        $this->personaManager= $personaManager;
    }

    public function indexAction(){
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
    
    protected function getItemsArray($items){
        $salida = array();
        foreach ($items as $item){
            array_push($salida, $item->toArray());
        }
        return $salida;
    }


}
