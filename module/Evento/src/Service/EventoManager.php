<?php

namespace Evento\Service;

use DBAL\Entity\Evento;
use DBAL\Entity\TipoEvento;
use Evento\Form\EventoForm;
use DBAL\Entity\Cliente;
use DBAL\Entity\Ejecutivo;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;

/**
 * This service is responsible for adding/editing eventos
 * and changing evento password.
 */
class EventoManager {

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

    public function getEventos() {
        $eventos = $this->entityManager->getRepository(Evento::class)->findAll();
        return $eventos;
    }

    public function getTabla() {

        $adapter = new SelectableAdapter($this->entityManager->getRepository(Evento::class)); // An object repository implements Selectable
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    public function getEventoId($id) {
        return $this->entityManager->getRepository(Evento::class)
                        ->find($id);
    }

    public function getEventoFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $evento = $this->addEvento($data);
        }
        return $evento;
    }

    /**
     * This method adds a new evento.
     */
    public function addEvento($data) {
        $evento = new Evento();
        $fecha_evento = \DateTime::createFromFormat('d/m/Y', $data['fecha_evento']);
        $evento->setFecha($fecha_evento);

        $tipo_evento = $this->entityManager->getRepository(TipoEvento::class)
                ->findOneBy(['id_tipo_evento' => $data['tipo_evento']]);
        $evento->setTipo($tipo_evento);
        $cliente = $this->entityManager->getRepository(Cliente::class)
                ->findOneBy(['Id' => $data['id_cliente']]);
        $evento->setId_cliente($cliente);
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
                ->findOneBy(['usuario' => $data['ejecutivo']]);
        $evento->setId_ejecutivo($ejecutivo);
        $evento->setDescripcion($data['detalle']);
        // Fecha Compra & Ultimo Cobro & Vencimiento
        if (($tipo_evento->getId() == 2) or ( $tipo_evento->getId() == 5)) {
            if ($cliente->isPrimeraVenta()) {
                $cliente->setFechaCompra($fecha_evento);
            }
            $cliente->setFechaUltimoContacto($fecha_evento);
//            $vencimiento = strtotime('+90 day', strtotime($fecha_str));
//            $vencimiento = date('Y-m-d', $vencimiento);
//            $cliente->setVencimiento($vencimiento);



//            Estilo orientado a objetos
//
//            $fecha = new DateTime('2000-01-01');
//            $fecha->add(new DateInterval('P10D'));
//            echo $fecha->format('Y-m-d') . "\n";
//
//
//            Estilo por procedimientos
//
//            $fecha = date_create('2000-01-01');
//            date_add($fecha, date_interval_create_from_date_string('10 days'));
//            echo date_format($fecha, 'Y-m-d');



           
            //NO FUNCIONA
            
            date_add($fecha_evento, date_interval_create_from_date_string('90 days'));
            $cliente->setVencimiento($fecha_evento);
            
        }

        $this->entityManager->persist($evento);
        $this->entityManager->flush();
        return $evento;
    }

    public function getEventosFiltrados($parametros) {

        if (size($parametros) == 0) {
            $eventos = $this->entityManager->getRepository(Evento::class)->findAll();
            return $eventos;
        }
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros($filtros);

        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);

        return $paginator;
    }

    public function busquedaPorFiltros($parametros) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('E')
                ->from(Evento::class, 'E');
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];
            if ($i == 0) {
                $queryBuilder->where("E.$nombreCampo = ?$p");
            } else {
                $queryBuilder->andWhere("E.$nombreCampo = ?$p");
            }
            $queryBuilder->setParameter("$p", $valorCampo);
        }
        return $queryBuilder->getQuery();
    }

    public function limpiarParametros($param) {
        foreach ($param as $filtro => $valor) {
            if (empty($valor)) {
                unset($param[$filtro]);
            } else {
                trim($param[$filtro]);
            }
        }
        return ($param);
    }

    public function createForm($tipos) {
        return new EventoForm('create', $this->entityManager, null, $tipos);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForEvento($evento) {
        $tipos = $this->entityManager->getRepository(TipoEvento::class)->findAll();
        if ($evento == null) {
            return null;
        }
        $form = new EventoForm('update', $this->entityManager, $evento, $tipos);
        return $form;
    }

    public function getFormEdited($form, $evento) {
        $form->setData(array(
            'fecha_evento' => $evento->getFecha(),
            'tipo_evento' => $evento->getTipo(),
            'id_cliente' => $evento->getId_cliente(),
            'id_ejecutivo' => $evento->getId_Ejecutivo(),
        ));
    }

    /**
     * This method updates data of an existing evento.
     */
    public function updateEvento($evento, $form) {
        $data = $form->getData();
        $evento->setFecha($data['fecha_evento']);
        $evento->setTipo($data['tipo_evento']);
        $evento->setId_cliente($data['id_cliente']);
        $evento->setId_ejecutivo($data['id_ejecutivo']);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeEvento($id) {
        $evento = $this->entityManager->getRepository(Evento::class)
                ->findOneById($id);
        $this->entityManager->remove($evento);
        $this->entityManager->flush();
    }

    public function eliminarEventos($eventos_array) {
        foreach ($eventos_array as $eve) {
            removeEvento($eve);
        }
    }

    public function getTipoEventos() {
        
    }

}
