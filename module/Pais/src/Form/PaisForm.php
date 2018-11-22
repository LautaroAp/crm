<?php

namespace Pais\Form;

use Zend\Form\Form;

class PaisForm extends Form {

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
     * Current pais.
     * @var Pais\Entity\Pais 
     */
    private $pais = null;

    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $pais = null) {
        // Define form name
        parent::__construct('pais-form');
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->pais = $pais;
        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() {
        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'nombre_pais',
            'value' => '',
            'options' => [
                'label' => 'Nombre pais',
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

    private function addInputFilter() {
        // Create main input filter
        $inputFilter = $this->getInputFilter();
        // Add input for "nombre_pais" field
        $inputFilter->add([
            'name' => 'nombre_pais',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                ],
            ],
        ]);
    }

}
