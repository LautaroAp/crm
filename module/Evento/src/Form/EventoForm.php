<?php

namespace Evento\Form;

use Zend\Form\Form;

class EventoForm extends Form {

    /**
     * Scenario ('create' or 'update').
     * @var string 
     */
    private $scenario;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager = null;

    /**
     * Current evento.
     * @var Evento\Entity\Evento 
     */
    private $evento = null;
    private $tipos = null;

    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $evento = null, $tipos) {
        // Define form name
        parent::__construct('evento-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->evento = $evento;
        $this->tipos = $tipos;
        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    function setId($id_evento) {
        $this->id_evento = $id_evento;
    }

    function setFecha($fecha_evento) {
        $this->fecha_evento = $fecha_evento;
    }

    function setTipo($tipo_evento) {
        $this->tipo_evento = $tipo_evento;
    }

    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }

    function setId_ejecutivo($id_ejecutivo) {
        $this->id_ejecutivo = $id_ejecutivo;
    }

    protected function addElements() {
        $this->add([
            'type' => 'text',
            'name' => 'fecha_evento',
            'options' => [
                'label' => 'Fecha',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'tipo_evento',
            'value' => '',
            'options' => [
                'label' => 'Tipo',
                'value_options' => $this->tipos,
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'id_cliente',
            'value' => '',
            'options' => [
                'label' => 'Cliente',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'id_ejecutivo',
            'value' => '',
            'options' => [
                'label' => 'Responsable',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'ejecutivo',
            'value' => '',
            'options' => [
                'label' => 'Ejecutivo',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'class' => 'btn btn-default',
            'attributes' => [
                'value' => 'Guardar'
            ],
        ]);
    }

    private function addInputFilter() {
        // Create main input filter
        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'fecha_evento',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'format' => 'd-m-Y',
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'tipo_evento',
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'id_cliente',
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name' => 'id_ejecutivo',
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                ],
            ],
        ]);
    }

}
