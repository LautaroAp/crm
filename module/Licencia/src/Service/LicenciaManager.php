<?php

namespace Licencia\Service;

use DBAL\Entity\Licencia;
use DBAL\Entity\Categoria;
use Licencia\Form\LicenciaForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing licencias
 * and changing licencia password.
 */
class LicenciaManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * PHP template renderer.
     * @var type 
     */
    private $viewRenderer;

    /**
     * Application config.
     * @var type 
     */
    private $config;

    private $ivaManager;

    private $categoriaManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $viewRenderer, $config, $ivaManager, $categoriaManager) {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager= $categoriaManager;
    }

    //retorna todas las licencias sin paginator para el backup
    public function getLicencias() {
        $licencias = $this->entityManager->getRepository(Licencia::class)->findAll();
        return $licencias;
    }

    public function getLicenciaId($id) {
        return $this->entityManager->getRepository(Licencia::class)
                        ->find($id);
    }

    public function getLicenciaFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $licencia = $this->addLicencia($data);
        }
        return $licencia;
    }

    public function createForm() {
        return new LicenciaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForLicencia($licencia) {
        if ($licencia == null) {
            return null;
        }
        $form = new LicenciaForm('update', $this->entityManager, $licencia);
        return $form;
    }

    public function getFormEdited($form, $licencia) {
        $form->setData(array(
            'nombre_licencia' => $licencia->getNombre(),
            'version_licencia' => $licencia->getVersion(),
        ));
    }

    /**
     * This method adds a new licencia.
     */
    public function addLicencia($data) {
        $licencia = new Licencia();
        $this->addData($licencia, $data);

        if ($this->tryAddLicencia($licencia)) {
            $_SESSION['MENSAJES']['licencia'] = 1;
            $_SESSION['MENSAJES']['licencia_msj'] = 'Licencia agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['licencia'] = 0;
            $_SESSION['MENSAJES']['licencia_msj'] = 'Error al agregar licencia';
        }

        return $licencia;
    }

    /**
     * This method updates data of an existing licencia.
     */
    public function updateLicencia($licencia, $data) {
        $this->addData($licencia, $data);
        if ($this->tryUpdateLicencia($licencia)) {
            $_SESSION['MENSAJES']['licencia'] = 1;
            $_SESSION['MENSAJES']['licencia_msj'] = 'Licencia editada correctamente';
        } else {
            $_SESSION['MENSAJES']['licencia'] = 0;
            $_SESSION['MENSAJES']['licencia_msj'] = 'Error al editar licencia';
        }
        return true;
    }

    private function addData($licencia, $data){
        $licencia->setNombre($data['nombre']);
        $licencia->setDescripcion($data['descripcion']);
        $licencia->setCodigo_licencia($data['codigo_licencia']);
        $licencia->setCodigo_barras($data['codigo_barras']);
        if($data['categoria'] == "-1"){
            $licencia->setCategoria(null);
        } else {
            $licencia->setCategoria($this->categoriaManager
                                        ->getCategoriaId($data['categoria']));
        }
        if($data['proveedor'] == "-1"){
            $licencia->setProveedor(null);
        } else {
            $licencia->setProveedor($data['proveedor']);
        }
        $licencia->setPrecio($data['precio']);
        $licencia->setPrecio_final_iva($data['precio_final_iva']);
        $licencia->setPrecio_final_dto($data['precio_final_dto']);
        $licencia->setPrecio_final_iva_dto($data['precio_final_iva_dto']);
        $licencia->setIva($this->ivaManager->getIvaPorValor($data['iva']));
        $licencia->setIva_gravado($data['iva_gravado']);
        $licencia->setDescuento($data['descuento']);
    }

    public function removeLicencia($licencia) {
        if ($this->tryRemoveLicencia($licencia)) {
            $_SESSION['MENSAJES']['licencia'] = 1;
            $_SESSION['MENSAJES']['licencia_msj'] = 'Licencia eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['licencia'] = 0;
            $_SESSION['MENSAJES']['licencia_msj'] = 'Error al eliminar licencia';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Licencia::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    private function getClientes() {
        $clientes = $this->entityManager
                        ->getRepository(Cliente::class)
                ->findAll;
        return $clientes;
    }

    private function borrarLicenciaCliente($cliente) {
        foreach ($clientes as $cliente) {
            $cliente->setLicencia(null);
        }
        $this->entityManager->flush();
    }
    
    private function tryAddLicencia($licencia) {
        try {
            $this->entityManager->persist($licencia);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateLicencia($licencia) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    private function tryRemoveLicencia($licencia) {
        try {
            $this->entityManager->remove($licencia);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    public function getCategoriasLicencia($tipo = null) {
        if (isset($tipo)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findBy(['tipo' => $tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function eliminarCategoriaLicencia($id){
        $licencias = $this->entityManager->getRepository(Licencia::class)->findBy(['categoria'=>$id]);
        foreach($licencias as $licencia){
            $licencia->setCategoria(null);
        }
    }
}
