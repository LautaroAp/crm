<?php
namespace ProfesionCliente\Form;

use Zend\Form\Form;

class ProfesionClienteForm extends Form
{
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
     * Current profesioncliente.
     * @var ProfesionCliente\Entity\ProfesionCliente 
     */
    private $profesioncliente = null;
    
    
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $profesioncliente = null)
    {
        // Define form name
        parent::__construct('profesioncliente-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->profesioncliente = $profesioncliente;
        
        $this->addElements();
        $this->addInputFilter();          
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
       

        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'nombre',
            'value' => '',
            'options' => [
                'label' => 'Nombre de Profesión',
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
    
    
    
  private function addInputFilter() 
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();        
                
   
        
        // Add input for "nombre" field
        $inputFilter->add([
            'name' => 'nombre',
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