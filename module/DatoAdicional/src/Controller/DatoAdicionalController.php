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
        $id_persona = (int) $this->params()->fromRoute('id');
        $persona= $this->personaManager->getPersona($id_persona);

        // Obtengo Json de Clientes
        $clientes = $this->personaManager->getPersonasTipo("CLIENTE");
        $json_clientes = "";
        foreach ($clientes as $cliente) {
            $json_clientes .= $cliente->getJson() . ',';
        }
        $json_clientes = substr($json_clientes, 0, -1);
        $json_clientes = '[' . $json_clientes . ']';

        // Obtengo Json de Proveedores
        $proveedores = $this->personaManager->getPersonasTipo("PROVEEDOR");
        $json_proveedores = "";
        foreach ($proveedores as $proveedor) {
            $json_proveedores .= $proveedor->getJson() . ',';
        }
        $json_proveedores = substr($json_proveedores, 0, -1);
        $json_proveedores = '[' . $json_proveedores . ']';

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $referencia_persona = $this->personaManager->getPersona($data["id_referencia_persona"]);
            $this->datoAdicionalManager->addDatoAdicional($data, $persona, $referencia_persona);
            return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $id_persona]);
        }
        return new ViewModel([
            'id_persona' => $id_persona,
            'persona' => $persona,
            'json_clientes' => $json_clientes,
            'json_proveedores' => $json_proveedores,
        ]);
    }
    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id_dato = (int) $this->params()->fromRoute('id');
        $datoAdicional = $this->datoAdicionalManager->getDatoAdicionalId($id_dato);
        $persona = $datoAdicional->getId_ficha_persona();

        // Obtengo Json de Clientes
        $clientes = $this->personaManager->getPersonasTipo("CLIENTE");
        $json_clientes = "";
        foreach ($clientes as $cliente) {
            $json_clientes .= $cliente->getJson() . ',';
        }
        $json_clientes = substr($json_clientes, 0, -1);
        $json_clientes = '[' . $json_clientes . ']';

        // Obtengo Json de Proveedores
        $proveedores = $this->personaManager->getPersonasTipo("PROVEEDOR");
        $json_proveedores = "";
        foreach ($proveedores as $proveedor) {
            $json_proveedores .= $proveedor->getJson() . ',';
        }
        $json_proveedores = substr($json_proveedores, 0, -1);
        $json_proveedores = '[' . $json_proveedores . ']';

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $referencia_persona = $this->personaManager->getPersona($data["id_referencia_persona"]);
            $this->datoAdicionalManager->updateDatoAdicional($datoAdicional, $data, $persona, $referencia_persona);
            return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
        }
        
        return new ViewModel([
            'persona' => $persona,
            'datoAdicional' => $datoAdicional,
            'json_clientes' => $json_clientes,
            'json_proveedores' => $json_proveedores,
        ]);  
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id_dato = (int) $this->params()->fromRoute('id');
        $datoAdicional = $this->datoAdicionalManager->getDatoAdicionalId($id_dato);
        $persona= $datoAdicional->getId_ficha_persona();

        if ($datoAdicional == null) {
            $this->reportarError();
        } else {
            $this->datoAdicionalManager->removeDatoAdicional($datoAdicional);
            return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
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
