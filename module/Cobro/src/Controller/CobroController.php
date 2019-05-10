<?php
namespace Cobro\Controller;

use Transaccion\Controller\TransaccionController;
use Cobro\Service\CobroManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class CobroController extends TransaccionController
{

    /**
     * Cobro manager.
     * @var User\Service\CobroManager 
     */
    protected $cobroManager;
    private $clientesManager;
    private $proveedorManager;
    private $bienesTransaccionesManager;
    private $bienesManager;
    
    public function __construct($cobroManager,$monedaManager, $personaManager, $clientesManager, $proveedorManager, $bienesTransaccionesManager, $bienesManager, $formaPagoManager, $formaEnvioManager, $ivaManager,$empresaManager) {
        parent::__construct($cobroManager, $personaManager,  $monedaManager,$ivaManager, $formaPagoManager, $formaEnvioManager, $empresaManager);
        $this->clientesManager = $clientesManager;
        $this->proveedorManager = $proveedorManager;
        $this->cobroManager = $cobroManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->bienesManager = $bienesManager;
    }

    public function indexAction()
    {

    }

    private function getTipo()
    {
        return "cobro";
    }

    public function addAction()
    {
        $json="[]";
        if (isset($_SESSION['TRANSACCIONES']['COBRO'])) {
            $json = $_SESSION['TRANSACCIONES']['COBRO'];
        }
        // Obtengo todos los Bienes
        $bienes = $this->bienesManager->getBienes();

        // Creo JSON con Nombres de todos los Productos y Servicios
        $json_bienes = "";

        // $response[] = array("value"=>"1","label"=>"Soporte");
        foreach ($bienes as $bien) {
            $json_bienes .= $bien->getJsonBien() . ',';
        }

        $json_bienes = substr($json_bienes, 0, -1);
        $json_bienes = '[' . $json_bienes . ']';

        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $tipoPersona = null;

        if ($persona->getTipo() == "CLIENTE") {
            $tipoPersona = $this->clientesManager->getClienteIdPersona($id_persona);
        } elseif ($persona->getTipo() == "PROVEEDOR") {
            $tipoPersona = $this->proveedorManager->getProveedorIdPersona($id_persona);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            $data['tipo_persona'] = $tipoPersona;
            
            $this->cobroManager->add($data);
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }

        $numTransacciones = $this->cobroManager->getTotalTransacciones() + 1;
        $numCobro = $this->cobroManager->getTotalCobros() + 1;
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        $presupuestos = $this->cobroManager->getTransaccionesPersonaTipo($persona->getId(),"PRESUPUESTO");
        $jsonPrespuestos = $this->getJsonFromObjectList($presupuestos);
        $pedidos = $this->cobroManager->getTransaccionesPersonaTipo($persona->getId(),"PEDIDO");
        $jsonPedidos = $this->getJsonFromObjectList($pedidos);
        $transacciones = $this->cobroManager->getTransacciones();
        $jsonTransacciones = $this->getJsonFromObjectList($transacciones);
        $this->reiniciarParams();

        return new ViewModel([
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numCobro' => $numCobro,
            'json' => $json,
            'json_bienes' => $json_bienes,
            'formasPagoJson' => $formasPagoJson,
            'formasEnvioJson' => $formasEnvioJson,
            'monedasJson' => $monedasJson,
            'ivasJson' => $ivasJson,
            'transaccionJson'=>"[]",
            'presupuestosJson' => $jsonPrespuestos,
            'pedidosJson' => $jsonPedidos,
            'transaccionesJson' => $jsonTransacciones,
            'itemsTransaccionJson' =>"[]",

        ]);
    }

    public function addItemAction()
   {
       if ($this->getRequest()->isPost()) {
           $data = $this->params()->fromPost();
           $data['tipo'] = $this->getTipo();
           $this->procesarAddAction($data);
           $this->redirect()->toRoute('home');
       }
       return new ViewModel([]);
   }

    public function editAction() {
        $id_transaccion = $this->params()->fromRoute('id');
        $cobro = $this->cobroManager->getCobroFromTransaccionId($id_transaccion);
        $items = array();

        if (!is_null($cobro)) {
            $items = $cobro->getTransaccion()->getBienesTransacciones();
        }
       
        $json = "";
        //SI HAY ITEMS CARGADOS EN LA SESION LOS TOMO DE AHI 
        if ((isset($_SESSION['TRANSACCIONES']['COBRO']))){
            $json = $_SESSION['TRANSACCIONES']['COBRO'];
        }
        //SINO LOS TOMO DEL COBRO Y GUARDO ESO EN LA SESION PARA CONTINUAR TRABAJANDO CON LA SESION
        else{
            $json = $this->getJsonFromObjectList($items);
            $_SESSION['TRANSACCIONES']['COBRO'] = $json;
        }
       
        $persona = $cobro->getTransaccion()->getPersona();
        $tipoPersona = null;
        // Obtengo todos los Bienes
        $bienes = $this->bienesManager->getBienes();

        // Creo JSON con Nombres de todos los Productos y Servicios
        $json_bienes = "";

        // $response[] = array("value"=>"1","label"=>"Soporte");
        foreach ($bienes as $bien) {
            $json_bienes .= $bien->getJsonBien() . ',';
        }
        $json_bienes = substr($json_bienes, 0, -1);
        $json_bienes = '[' . $json_bienes . ']';

        if ($persona->getTipo() == "CLIENTE") {
            $tipoPersona = $this->clientesManager->getClienteIdPersona($persona->getId());
        } elseif ($persona->getTipo() == "PROVEEDOR") {
            $tipoPersona = $this->proveedorManager->getProveedorIdPersona($persona->getId());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            // $data['items'] = $_SESSION['TRANSACCIONES']['COBRO'];
            $this->cobroManager->edit($cobro, $data);
            $url = $data['url'];
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones = $cobro->getTransaccion()->getNumero();
        $numCobro = $cobro->getNumero();
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        $transaccion = $cobro->getTransaccion();
        $transaccionJson = $cobro->getTransaccion()->getJSON();
        $presupuestos = $this->cobroManager->getTransaccionesPersonaTipo($persona->getId(),"PRESUPUESTO");
        $jsonPrespuestos = $this->getJsonFromObjectList($presupuestos);
        $this->reiniciarParams();
        return new ViewModel([
            // 'items' => $items,
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numCobro' => $numCobro,
            'json' => $json,
            'json_bienes' => $json_bienes,
            'formasPagoJson' => $formasPagoJson,
            'formasEnvioJson' => $formasEnvioJson,
            'monedasJson' => $monedasJson,
            'cobro' => $cobro,
            'transaccion' => $transaccion,
            'transaccionJson' => $transaccionJson,
            'ivasJson' => $ivasJson,
            'presupuestosJson' => $jsonPrespuestos,
            'itemsTransaccionJson' =>"[]",
        ]);
    }

    public function setItemsAction(){
        $items = $_POST['json'];
        $_SESSION['TRANSACCIONES']['COBRO'] = $items;
    }

    public function eliminarItemAction(){
        $items = $_POST['json'];
        $index = $_POST['index'];
       
        $itemsArray = json_decode($items, true);
        $itemsArray = array_splice($itemsArray, $index, 1);
        $json = json_encode($itemsArray);
        
        $view = new ViewModel(['json'=>$json]);
        $view->setTemplate('layout/nulo');
        $view->setTerminal(true);
        return $view;
    }

    public function getJsonFormasEnvio()
   {
       $formasEnvio = $this->formaEnvioManager->getFormasEnvio();
       $json = $this->getJsonFromObjectList($formasEnvio);
       return $json;
   }

   public function getItemsTransaccionAction(){
        $this->layout()->setTemplate('layout/nulo');
        $idTransaccion = $this->params()->fromRoute('id');
        $transaccion = $this->cobroManager->getTransaccionId($idTransaccion);
        $transaccionJson = $transaccion->getJSON();
        $items = $transaccion->getBienesTransacciones();
        $itemsTransaccionJson = $this->getJsonFromObjectList($items);
        $view = new ViewModel([
            'itemsTransaccionJson'=>$itemsTransaccionJson,
            'transaccionJson' => $transaccionJson,
        ]);
        $view->setTerminal(true);
        return $view;
   }

    // PDF
    public function pdfAction() {
        $this->layout()->setTemplate('layout/nulo');
        $id_transaccion = $this->params()->fromRoute('id');
        $cobro = $this->cobroManager->getCobroFromTransaccionId($id_transaccion);
        $items = array();

        if (!is_null($cobro)) {
            $items = $cobro->getTransaccion()->getBienesTransacciones();
        }
       
        $json = "";
        $json = $this->getJsonFromObjectList($items);
       
        $persona = $cobro->getTransaccion()->getPersona();
        $tipoPersona = null;

        $empresa = $this->empresaManager->getEmpresa();

        $numTransacciones = $cobro->getTransaccion()->getNumero();
        $numCobro = $cobro->getNumero();
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        $transaccion = $cobro->getTransaccion();
        $transaccionJson = $cobro->getTransaccion()->getJSON();
        $presupuestos = $this->cobroManager->getTransaccionesPersonaTipo($persona->getId(),"PRESUPUESTO");
        $jsonPrespuestos = $this->getJsonFromObjectList($presupuestos);
        $this->reiniciarParams();
        return new ViewModel([
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numCobro' => $numCobro,
            'json' => $json,
            'formasPagoJson' => $formasPagoJson,
            'formasEnvioJson' => $formasEnvioJson,
            'monedasJson' => $monedasJson,
            'cobro' => $cobro,
            'transaccion' => $transaccion,
            'transaccionJson' => $transaccionJson,
            'ivasJson' => $ivasJson,
            'presupuestosJson' => $jsonPrespuestos,
            'itemsTransaccionJson' =>"[]",
            'empresa' => $empresa,
        ]);
    }

   
   public function getItemsPreviosAction(){
        $this->layout()->setTemplate('layout/nulo');
        $numPresupuesto = $this->params()->fromRoute('id');
        $presupuesto = $this->cobroManager->getPresupuestoPrevio($numPresupuesto);
        $itemsTransaccionJson="[]";
        if ($presupuesto!=null){
            $transaccion = $presupuesto->getTransaccion();
            $items = $transaccion->getBienesTransacciones();
            $itemsTransaccionJson = $this->getJsonFromObjectList($items);
        }
        $view = new ViewModel(['itemsTransaccionJson'=>$itemsTransaccionJson]);
        $view->setTerminal(true);
        return $view;
    }   

    public function cambiarEstadoAction(){
        // $this->layout()->setTemplate('layout/nulo');
        $idTransaccion = $this->params()->fromRoute('id');
        $estado= $this->params()->fromRoute('id2');
        $this->cobroManager->cambiarEstadoTransaccion($idTransaccion, $estado);
        
        $view = new ViewModel();
        $view->setTemplate('layout/nulo');
        $view->setTerminal(true);
        return $view;
    }
}