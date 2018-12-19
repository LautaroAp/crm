<?php

namespace Ganaderia\Form;

use Zend\Form\Form;

class GanaderiaForm extends Form {

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
     * Current Ganaderia.
     * @var Ganaderia\Entity\Ganaderia 
     */
    private $ganaderia = null;

    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $ganaderia = null) {
        // Define form name
        parent::__construct('Ganaderia-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->ganaderia = $ganaderia;

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
}
