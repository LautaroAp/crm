<?php
namespace NotaDebito\Controller;

use Transaccion\Controller\TransaccionController;
use NotaDebito\Service\NotaDebitoManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class NotaDebitoController extends TransaccionController
{

    /**
     * NotaDebito manager.
     * @var User\Service\NotaDebitoManager 
     */
    protected $notaDebitoManager;
    private $clientesManager;
    private $proveedorManager;
    private $bienesTransaccionesManager;
    private $bienesManager;
    
    public function __construct($notaDebitoManager,$monedaManager, $personaManager, $clientesManager, $proveedorManager, $bienesTransaccionesManager, $bienesManager, $formaPagoManager, $formaEnvioManager, $ivaManager,$empresaManager, $tipoFacturaManager) {
        parent::__construct($notaDebitoManager, $personaManager,  $monedaManager,$ivaManager, $formaPagoManager, $formaEnvioManager, $empresaManager);
        $this->clientesManager = $clientesManager;
        $this->proveedorManager = $proveedorManager;
        $this->notaDebitoManager = $notaDebitoManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->bienesManager = $bienesManager;
        $this->tipoFacturaManager = $tipoFacturaManager;
    }

    public function indexAction()
    {

    }

    private function getTipo()
    {
        return "nota de debito";
    }

    public function addAction()
    {
        $json="[]";
        if (isset($_SESSION['TRANSACCIONES']['NOTA_DEBITO'])) {
            $json = $_SESSION['TRANSACCIONES']['NOTA_DEBITO'];
        }
     
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
            $this->notaDebitoManager->add($data);
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedores/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }

        $numTransacciones = $this->notaDebitoManager->getTotalTransacciones() + 1;
        $numNotaDebito = $this->notaDebitoManager->getTotalNotaDebitos() + 1;
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        ////////////////////////////FACTURAS PREVIAS///////////////////////////
        $facturas = $this->notaDebitoManager->getTransaccionesPersonaTipo($persona->getId(),"Factura");
        $facturasJson = $this->getJsonFromObjectList($facturas);
        ////////////////////////////REMITOS CONFORMADOS PREVIOS/////////////////////////////
        $remitosConformados = $this->getRemitosConformados($persona);
        $remitosConformadosJson = $this->getJsonFromObjectList($remitosConformados);

        $tiposFactura= $this->tipoFacturaManager->getTipoFacturas();
        $tiposFacturaJson =$this->getJsonFromObjectList($tiposFactura);
        $tipoFacturaPersona = $persona->getTipo_factura();
        $tipoFacturaPersonaJson ="";
        $empresaJson = $this->empresaManager->getEmpresa()->getJSON();

        $empresaJson = $this->empresaManager->getEmpresa()->getJSON();
        // var_dump(json_decode($tiposFacturaJson), true); die();

        $this->reiniciarParams();
        return new ViewModel([
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numNotaDebito' => $numNotaDebito,
            'json' => $json,
            'formasPagoJson' => $formasPagoJson,
            'formasEnvioJson' => $formasEnvioJson,
            'monedasJson' => $monedasJson,
            'ivasJson' => $ivasJson,
            'transaccionJson'=>"[]",
            'facturasJson' => $facturasJson,
            'remitosConformadosJson'=>$remitosConformadosJson,
            'empresaJson' => $empresaJson,
            'tiposFacturaJson'=>$tiposFacturaJson,
            'tipoFacturaPersonaJson' => $tipoFacturaPersonaJson,
            'itemsTransaccionJson' =>"[]",
        ]);
    }

    private function getRemitosConformados($persona){
        $salida = [];
        $remitos = $this->notaDebitoManager->getTransaccionesPersonaTipo($persona->getId(),"Remito");
        foreach ($remitos as $remito){
            if (strtoupper($remito->getEstado())=="CONFORMADO"){
                array_push($salida, $remito);
            }
        }
        return $salida;
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
        // Similar a Remito
    }

    public function setItemsAction(){
        $items = $_POST['json'];
        $_SESSION['TRANSACCIONES']['NOTA DE DEBITO'] = $items;
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
        $transaccion = $this->notaDebitoManager->getTransaccionId($idTransaccion);
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
        $notaDebito = $this->notaDebitoManager->getNotaDebitoFromTransaccionId($id_transaccion);
        $items = array();

        if (!is_null($notaDebito)) {
            $items = $notaDebito->getTransaccion()->getBienesTransacciones();
        }
       

        $json = "";
        $json = $this->getJsonFromObjectList($items);
       
        $persona = $notaDebito->getTransaccion()->getPersona();
        $tipoPersona = null;

        $empresa = $this->empresaManager->getEmpresa();

        $numTransacciones = $notaDebito->getTransaccion()->getNumero();
        $numNotaDebito = $notaDebito->getNumero();
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        $transaccion = $notaDebito->getTransaccion();
        $transaccionJson = $notaDebito->getTransaccion()->getJSON();
        $facturas = $this->notaDebitoManager->getTransaccionesPersonaTipo($persona->getId(),"Factura");
        $facturasJson = $this->getJsonFromObjectList($facturas);

        $this->reiniciarParams();
        return new ViewModel([
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numNotaDebito' => $numNotaDebito,
            'json' => $json,
            'formasPagoJson' => $formasPagoJson,
            'formasEnvioJson' => $formasEnvioJson,
            'monedasJson' => $monedasJson,
            'notaDebito' => $notaDebito,
            'transaccion' => $transaccion,
            'transaccionJson' => $transaccionJson,
            'ivasJson' => $ivasJson,
            'facturasJson' => $facturasJson,
            'itemsTransaccionJson' =>"[]",
            'empresa' => $empresa,
        ]);
    }

   
   public function getItemsPreviosAction(){
        $this->layout()->setTemplate('layout/nulo');
        $numPresupuesto = $this->params()->fromRoute('id');
        $presupuesto = $this->notaDebitoManager->getPresupuestoPrevio($numPresupuesto);
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
        $this->notaDebitoManager->cambiarEstadoTransaccion($idTransaccion, $estado);
        
        $view = new ViewModel();
        $view->setTemplate('layout/nulo');
        $view->setTerminal(true);
        return $view;
    }
}