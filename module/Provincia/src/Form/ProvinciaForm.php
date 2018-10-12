<?php
namespace Provincia\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;



class ProvinciaForm extends Form
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
     * Current provincia.
     * @var Provincia\Entity\Provincia 
     */
    private $provincia = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $provincia = null)
    {
        // Define form name
        parent::__construct('provincia-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->provincia = $provincia;
        
        $this->addElements();
        $this->addInputFilter();          
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
       
        $this->add([
            'type' => 'integer',
            'name' => 'id_pais',
            'value' => '',
            'options' => [
                'label' => 'id_pais',
            ],
        ]);
        $this->add([
            'type' => 'integer',
            'name' => '$id_provincia',
            'value' => '',
            'options' => [
                'label' => 'id_provincia',
            ],
        ]);
        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'nombre_provincia',
            'value' => '',
            'options' => [
                'label' => 'Nombre provincia',
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
                
   
        
        // Add input for "nombre_provincia" field
        $inputFilter->add([
            'name' => 'nombre_provincia',
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