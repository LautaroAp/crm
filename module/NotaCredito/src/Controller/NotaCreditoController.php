<?php
namespace NotaCredito\Controller;

use Transaccion\Controller\TransaccionController;
use NotaCredito\Service\NotaCreditoManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class NotaCreditoController extends TransaccionController
{

    /**
     * NotaCredito manager.
     * @var User\Service\NotaCreditoManager 
     */
    protected $notaCreditoManager;
    private $clientesManager;
    private $proveedorManager;
    private $bienesTransaccionesManager;
    private $bienesManager;
    
    public function __construct($notaCreditoManager,$monedaManager, $personaManager, $clientesManager, $proveedorManager, $bienesTransaccionesManager, $bienesManager, $formaPagoManager, $formaEnvioManager, $ivaManager,$empresaManager, $tipoComprobanteManager) {
        parent::__construct($notaCreditoManager, $personaManager,  $monedaManager,$ivaManager, $formaPagoManager, $formaEnvioManager, $empresaManager);
        $this->clientesManager = $clientesManager;
        $this->proveedorManager = $proveedorManager;
        $this->notaCreditoManager = $notaCreditoManager;
        $this->bienesTransaccionesManager = $bienesTransaccionesManager;
        $this->bienesManager = $bienesManager;
        $this->tipoComprobanteManager = $tipoComprobanteManager;
    }

    public function indexAction()
    {

    }

    private function getTipo()
    {
        return "nota de credito";
    }

    private function getIdComprobante(){
        return 4;
    }

    public function addAction()
    {
        $json="[]";
        if (isset($_SESSION['TRANSACCIONES']['NOTA_CREDITO'])) {
            $json = $_SESSION['TRANSACCIONES']['NOTA_CREDITO'];
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
            $data['idComprobante'] = $this->getIdComprobante();
            $this->notaCreditoManager->add($data);
            if ($persona->getTipo() == "CLIENTE") {
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            } else {
                $this->redirect()->toRoute('proveedores/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }

        $numTransacciones = $this->notaCreditoManager->getTotalTransacciones() + 1;
        $numNotaCredito = $this->notaCreditoManager->getTotalNotaCreditos() + 1;
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        ////////////////////////////FACTURAS PREVIAS///////////////////////////
        $facturas = $this->notaCreditoManager->getTransaccionesPersonaTipo($persona->getId(),"Factura");
        $facturasJson = $this->getJsonFromObjectList($facturas);
        ////////////////////////////REMITOS CONFORMADOS PREVIOS/////////////////////////////
        $remitosConformados = $this->getRemitosConformados($persona);
        $remitosConformadosJson = $this->getJsonFromObjectList($remitosConformados);

        $tiposFactura= $this->tipoComprobanteManager->getTipoComprobantes($this->getIdComprobante());
        $tiposFacturaJson =$this->getJsonFromObjectList($tiposFactura);
        $tipoComprobantePersona = $persona->getTipo_comprobante();
        $tipoComprobantePersonaJson ="";
        $empresaJson = $this->empresaManager->getEmpresa()->getJSON();

        $this->reiniciarParams();
        return new ViewModel([
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numNotaCredito' => $numNotaCredito,
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
            'tipoComprobantePersonaJson' => $tipoComprobantePersonaJson,
            'itemsTransaccionJson' =>"[]",
        ]);
    }

    private function getRemitosConformados($persona){
        $salida = [];
        $remitos = $this->notaCreditoManager->getTransaccionesPersonaTipo($persona->getId(),"Remito");
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
        $_SESSION['TRANSACCIONES']['NOTA DE CREDITO'] = $items;
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
        $transaccion = $this->notaCreditoManager->getTransaccionId($idTransaccion);
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

    // PDF TIPO A
    public function pdfAction() {
        $this->layout()->setTemplate('layout/nulo');
        $id_transaccion = $this->params()->fromRoute('id');
        $notaCredito = $this->notaCreditoManager->getNotaCreditoFromTransaccionId($id_transaccion);
        $items = array();
        if (!is_null($notaCredito)) {
            $items = $notaCredito->getTransaccion()->getBienesTransacciones();
        }
        $json = "";
        $json = $this->getJsonFromObjectList($items);
        $persona = $notaCredito->getTransaccion()->getPersona();
        $tipoPersona = null;
        $empresa = $this->empresaManager->getEmpresa();
        $numTransacciones = $notaCredito->getTransaccion()->getNumero();
        $numNotaCredito = $notaCredito->getNumero();
        $monedasJson = $this->getJsonMonedas();
        $formasPagoJson = $this->getJsonFormasPago();
        $formasEnvioJson = $this->getJsonFormasEnvio();
        $ivasJson = $this->getJsonIvas();
        $transaccion = $notaCredito->getTransaccion();
        $transaccionJson = $notaCredito->getTransaccion()->getJSON();
        $facturas = $this->notaCreditoManager->getTransaccionesPersonaTipo($persona->getId(),"Factura");
        $facturasJson = $this->getJsonFromObjectList($facturas);

        $this->reiniciarParams();
        return new ViewModel([
            'persona' => $persona,
            'tipoPersona' => $tipoPersona,
            'numTransacciones' => $numTransacciones,
            'numNotaCredito' => $numNotaCredito,
            'json' => $json,
            'formasPagoJson' => $formasPagoJson,
            'formasEnvioJson' => $formasEnvioJson,
            'monedasJson' => $monedasJson,
            'notaCredito' => $notaCredito,
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
        $presupuesto = $this->notaCreditoManager->getPresupuestoPrevio($numPresupuesto);
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
        $this->notaCreditoManager->cambiarEstadoTransaccion($idTransaccion, $estado);
        
        $view = new ViewModel();
        $view->setTemplate('layout/nulo');
        $view->setTerminal(true);
        return $view;
    }
}