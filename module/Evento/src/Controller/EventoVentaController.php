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
     * @var User\Service\EventoManager 
     */
    protected $eventoManager;

    protected $tipoEventoManager;
    
    public function __construct($entityManager, $eventoManager, $tipoEventoManager){

        // super de java -> usar parent::__construct .... y pasar el tipoEventoManager como parametro
        // parent::__construct(...);

        $this->entityManager = $entityManager;
        $this->eventoManager = $eventoManager;
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
        $paginator = $this->eventoManager->getEventosFiltrados($parametros);
        $total = $this->eventoManager->getTotalFiltrados($parametros);
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
        $evento = $this->eventoManager->getEventoId($id);
        $form = $this->eventoManager->getFormForEvento($evento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->eventoManager->formValid($form, $data)) {
                    $this->eventoManager->updateEvento($evento, $form);
                    return $this->redirect()->toRoute('application', ['action' => 'view']);
                }
            } else {
                $this->eventoManager->getFormEdited($form, $evento);
            }
            return new ViewModel(array(
                'evento' => $evento,
                'form' => $form
            ));
        }
    }
}
