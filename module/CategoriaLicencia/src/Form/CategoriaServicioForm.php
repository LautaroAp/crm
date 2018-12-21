<?php

namespace CategoriaLicencia\Form;

use Zend\Form\Form;

class CategoriaLicenciaForm extends Form {

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
     * Current categoriaLicencia.
     * @var CategoriaLicencia\Entity\CategoriaLicencia 
     */
    private $categoriaLicencia = null;

    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $categoriaLicencia = null) {
        // Define form name
        parent::__construct('categoriaLicencia-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->categoriaLicencia = $categoriaLicencia;

        $this->addElements();
        // $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() {
        $this->add([
            'type' => 'text',
            'name' => 'nombre',
            'value' => '',
            'options' => [
                'label' => 'Nombre de Categoría',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'descripcion',
            'value' => '',
            'options' => [
                'label' => 'Descripcion de Categoría',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ],
        ]);
    }

    // private function addInputFilter() {
    //     $inputFilter = $this->getInputFilter();
    //     $inputFilter->add([
    //         'name' => 'nombre',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => 'StringTrim'],
    //         ],
    //         'validators' => [
    //             [
    //                 'name' => 'StringLength',
    //             ],
    //         ],
    //     ]);
    // }

}
