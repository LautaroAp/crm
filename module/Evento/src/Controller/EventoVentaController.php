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
        $this->prepararBreadcrumbs("Movimientos", "/ventas");
        $request = $this->getRequest();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_VENTA'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_VENTA'])) {
            $parametros = $_SESSION['PARAMETROS_VENTA'];
        } else {
            $parametros = array();
        }
        $tipo= $_SESSION['TIPOEVENTO']['TIPO']; 
        $tipoEventos = $this->tipoEventoManager->getTipoEventos($tipo);
        $tipoPersona = $parametros['tipo_persona'];
        if ($tipoPersona == '-1'){
            $tipoPersona = null;
            unset($parametros['tipo_persona']);
        }
        $accionComercial = $parametros['accion_comercial']; 
        if ((is_null($accionComercial)) or ($accionComercial== '-1')){
            $accionComercial =null;
            unset($parametros['accion_comercial']);
        }
        else {
            $accionComercial= $this->tipoEventoManager->getTipoEventoId($parametros['accion_comercial']);
        }
        $paginator = $this->eventoVentaManager->getEventosFiltrados($parametros);
        $total = $this->eventoVentaManager->getTotalFiltrados($parametros);
        $mensaje = "";
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        return new ViewModel([
            'eventos' => $paginator,
            'mensaje' => $mensaje,
            'parametros' => $parametros,
            'accionComercial' =>$accionComercial,
            'tipoPersona' =>$tipoPersona,
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
}
