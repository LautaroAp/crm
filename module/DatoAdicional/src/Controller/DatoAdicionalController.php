<?php

/**
 * Esta clase es el controlador de la entidad DatoAdicional.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace DatoAdicional\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class DatoAdicionalController extends HuellaController {

    protected $datoAdicionalManager;
    private $personaManager;

    public function __construct($datoAdicionalManager, $personaManager) {
        $this->datoAdicionalManager = $datoAdicionalManager;
        $this->personaManager = $personaManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }


    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        // $datosAdicionales = $this->datoAdicionalManager->getDatosAdicionales();
        $id_persona = (int) $this->params()->fromRoute('id');

        print_r($id_persona); die();

        $persona= $this->personaManager->getPersona($id_persona);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            // $this->limpiarParametros($data);
            $this->datoAdicionalManager->addDatoAdicional($data, $persona);
            return $this->redirect()->toRoute("datoadicional");
        }
        return new ViewModel([
            'persona' => $persona,
        ]);
    }

    // private function procesarAddAction() {
    //     $this->prepararBreadcrumbs("Agregar Servicios", "/add/servicio", "Servicios");
    //     if ($this->getRequest()->isPost()) {
    //         $data = $this->params()->fromPost();
    //         $this->servicioManager->addServicio($data);
    //         $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
    //     }
    //     $ivas = $this->ivaManager->getIvas();
    //     $tipo= $this->params()->fromRoute('tipo');
    //     $categorias = $this->servicioManager->getCategoriasServicio($tipo);
    //     $proveedores = $this->servicioManager->getListaProveedores($tipo);
    //     $volver = $this->getUltimaUrl();
    //     return new ViewModel([
    //         'tipo'=>$tipo,
    //         'ivas'=>$ivas,
    //         'proveedores'=>$proveedores,
    //         'categorias'=>$categorias,
    //         'volver' => $volver,
    //     ]);

    // }

    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id');
        $datoAdicional = $this->datoAdicionalManager->getDatoAdicionalId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->datoAdicionalManager->updateDatoAdicional($datoAdicional, $data);
            return $this->redirect()->toRoute("herramientas/formaspago");
        }
        return new ViewModel(array(
            'datoAdicional' => $datoAdicional,
        ));  
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $datoAdicional = $this->datoAdicionalManager->getDatoAdicionalId($id);
        if ($datoAdicional == null) {
            $this->reportarError();
        } else {
            $this->transaccionManager->eliminarDatosAdicionales($datoAdicional->getId());
            $this->datoAdicionalManager->removeDatoAdicional($datoAdicional);
            return $this->redirect()->toRoute('herramientas/formaspago');
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }
}
