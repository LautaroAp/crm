<?php

namespace Empresa\Service;

use DBAL\Entity\Empresa;
use DBAL\Entity\Categoria;
use Empresa\Form\EmpresaForm;

/**
 * This service is responsible for adding/editing empresas
 * and changing empresa password.
 */
class EmpresaManager {

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

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $viewRenderer, $config) {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    public function setVencimientos($vencimientos) {
        $this->vencimientos = $vencimientos;
    }

    public function getEmpresas() {
        $empresas = $this->entityManager->getRepository(Empresa::class)->findAll();
        return $empresas;
    }

    public function getEmpresaFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $empresa = $this->addEmpresa($data);
        }
        return $empresa;
    }

    public function addEmpresa($data) {
        $empresa = new Empresa();
        $empresa->setNombre($data['nombre_empresa']);
        if ($this->tryAddEmpresa($empresa)) {
            $_SESSION['MENSAJES']['empresa'] = 1;
            $_SESSION['MENSAJES']['empresa_msj'] = 'Empresa agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['empresa'] = 0;
            $_SESSION['MENSAJES']['empresa_msj'] = 'Error al agregar empresa';
        }
        return $empresa;
    }

    public function createForm() {
        return new EmpresaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForEmpresa($empresa) {
        if ($empresa == null) {
            return null;
        }
        $form = new EmpresaForm('update', $this->entityManager, $empresa);
        return $form;
    }

    public function getFormEdited($form, $empresa) {
        $form->setData(array(
            'nombre_empresa' => $empresa->getNombre(),
        ));
    }

    public function updateEmpresa($empresa, $data) {
        $empresa->setNombre($data['nombre']);
        $empresa->setDireccion($data['direccion']);
        $empresa->setTelefono($data['telefono']);
        $empresa->setMail($data['mail']);
        $empresa->setFax($data['fax']);
        $empresa->setWeb($data['web']);
        $empresa->setCuit_cuil($data['cuit_cuil']);
        $empresa->setIngresos_brutos($data['ingresos_brutos']);

        $vencimiento_cai = \DateTime::createFromFormat('d/m/Y', $data['vencimiento_cai']);
        $empresa->setVencimiento_cai($vencimiento_cai);
        $inicio_actividades = \DateTime::createFromFormat('d/m/Y', $data['inicio_actividades']);
        $empresa->setFecha_inicio_actividades($inicio_actividades);

        $empresa->setRazon_social($data['razon_social']);
        $empresa->setPunto_venta($data['punto_venta']);
        if (isset($data['condicion_iva'])){
            $condicion_iva = $this->getCategoria($data['condicion_iva']);
            $empresa->setCondicion_iva($condicion_iva);
        }
        // if (isset($data['moneda'])){
        //     $empresa->setMoneda($data['moneda']);
        // }
        $empresa->setLocalidad($data['localidad']);
        $empresa->setProvincia($data['provincia']);
        $empresa->setPais($data['pais']);
        $empresa->setCP($data['CP']);
        $empresa->setParametro_vencimiento($data['parametro_vencimiento']);
        $empresa->setParametro_elementos_pagina($data['elems_pagina']);

        $_SESSION['ELEMSPAG']= $data['elems_pagina'];
        
        if ($this->tryUpdateEmpresa($empresa)) {
            $_SESSION['MENSAJES']['empresa'] = 1;
            $_SESSION['MENSAJES']['empresa_msj'] = 'Datos editados correctamente';
        } else {
            $_SESSION['MENSAJES']['empresa'] = 0;
            $_SESSION['MENSAJES']['empresa_msj'] = 'Error al editar empresa';
        }
        return true;
    }

    public function removeEmpresa($empresa) {
        if ($this->tryRemoveEmpresa($empresa)) {
            $_SESSION['MENSAJES']['empresa'] = 1;
            $_SESSION['MENSAJES']['empresa_msj'] = 'Empresa eliminados correctamente';
        } else {
            $_SESSION['MENSAJES']['empresa'] = 0;
            $_SESSION['MENSAJES']['empresa_msj'] = 'Error al eliminar empresa';
        }
    }

    private function tryAddEmpresa($empresa) {
        try {
            $this->entityManager->persist($empresa);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateEmpresa() {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    private function tryRemoveEmpresa($empresa) {
        try {
            $this->entityManager->remove($empresa);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    public function getEmpresa()
    {
        $empresas = $this->getEmpresas();
        $empresa = $empresas[0];
        return $empresa;
    }

    public function getElemsPag(){
        $empresa = $this->getEmpresa();
        return $empresa->getParametro_elementos_pagina();
    }

    public function getCondicionIva($tipo = null) {
        if (isset($tipo)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findBy(['tipo' => $tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function getCategoria($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findOneBy(['id' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }
}
