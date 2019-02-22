<?php

namespace Evento\Controller;

use Zend\View\Model\ViewModel;


class EventoVentaController extends EventoController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Evento manager.
     * @var User\Service\eventoVentaManager 
     */
    protected $eventoVentaManager;

    protected $tipoEventoManager;
    
    public function __construct($entityManager, $eventoVentaManager, $tipoEventoManager){

        // super de java -> usar parent::__construct .... y pasar el tipoEventoManager como parametro
        // parent::__construct(...);

        $this->entityManager = $entityManager;
        $this->eventoVentaManager = $eventoVentaManager;
        $this->tipoEventoManager= $tipoEventoManager;
    }
    
    
    public function indexAction()
    {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $this->prepararBreadcrumbs("Resumen de Eventos", "/eventos");
        $request = $this->getRequest();
       //SE OBTIENE LA PERSONA DE LA RUTA POR SI SE LO LLAMA DE CLIENTE/PROVEEDOR
        $persona= $this->params()->fromRoute('tipo');
        if (isset($persona)){
            //si llego una persona por ruta se la guarda en la sesion para paginator
            $_SESSION['EVENTO']['tipo_persona'] = $persona;
        }
        else{
            $_SESSION['EVENTO']['tipo_persona'] = "empresa";
        }
        if ($request->isPost()) {
            //SI SE COMPLETO EL FORMULARIO DE BUSQUEDA TOMO ESOS PARAMETROS Y LOS GUARDO EN LA SESION 
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_VENTA'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_VENTA'])) {
            //SI HAY PARAMETROS GUARDADOS EN LA SESION TOMAR ESOS PARAMETROS 
            $parametros = $_SESSION['PARAMETROS_VENTA'];
        } else {
            //SI NO HAY PARAMETROS CREAR NUEVOS
            $parametros = array();
        }
        if ($_SESSION['EVENTO']['tipo_persona'] == "empresa"){
            //SI LLEGO DESDE EMPRESA TOMO EL TIPO DE PERSONA DEL FORMULARIO
            $tipoPersona = $parametros['tipo_persona'];       
        }
        else {
            //SI LLEGO DESDE CLIENTE O PROVEEDOR TOMO EL TIPO DE PERSONA DE LA RUTA
            $tipoPersona= $persona;
            $parametros['tipo_persona'] = $persona;
        }
        if (($tipoPersona == '-1') and ($_SESSION['EVENTO']['tipo_persona'] == "empresa")){
            //SI SE SELECCIONO "TODOS" EN PERSONA (MOSTRAR EVENTOS DE CLIENTES Y DE PROVEEDORES SI SE ESTA EN LA EMPRESA)
            $tipoPersona = null;
            unset($parametros['tipo_persona']);
            // $_SESSION['PARAMETROS_VENTA'] = $parametros;
        }
        $accionComercial = $parametros['tipo']; 
        if ((is_null($accionComercial)) or ($accionComercial== '-1')){
            //SI NO SE SELECCIONO ACCION COMERCIAL MOSTRAR TODO TIPO DE EVENTO
            $accionComercial =null;
            unset($parametros['tipo']);
        }
        else {
            $accionComercial= $this->tipoEventoManager->getTipoEventoId($parametros['tipo']);
        }
        $paginator = $this->eventoVentaManager->getEventosFiltrados($parametros);
        $total = $this->eventoVentaManager->getTotalFiltrados($parametros);
        $page = 1;
        if (!is_null($tipoPersona) and ($tipoPersona!="-1")){
            $tipoEventos = $this->tipoEventoManager->getTipoEventos($tipoPersona);
        }
        else {
            $tipoEventos = $this->tipoEventoManager->getTodosTipos();
        }
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        return new ViewModel([
            'eventos' => $paginator,
            'parametros' => $parametros,
            'accionComercial' =>$accionComercial,
            'persona' => $persona,
            'tipoPersona' =>$tipoPersona,
            'personaParametro' => $persona_parametro,
            'total' => $total,
            'tipos' => $tipoEventos,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $evento = $this->eventoVentaManager->getEventoId($id);
        $form = $this->eventoVentaManager->getFormForEvento($evento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->eventoVentaManager->formValid($form, $data)) {
                    $this->eventoVentaManager->updateEvento($evento, $form);
                    return $this->redirect()->toRoute('application', ['action' => 'view']);
                }
            } else {
                $this->eventoVentaManager->getFormEdited($form, $evento);
            }
            return new ViewModel(array(
                'evento' => $evento,
                'form' => $form
            ));
        }
    }

    public function getTiposAction(){ 
        $this->layout()->setTemplate('layout/nulo');
        $tipo = $this->params()->fromRoute('tipo');
        $tipos= Array();
        if ($tipo!="todos"){
            $tipos = $this->tipoEventoManager->getTipoEventos($tipo);
        }
        else{
            $tipos = $this->tipoEventoManager->getTodosTipos();
        }
        $view = new ViewModel(['tipos' => $tipos]);
        return $view;
    }
}
