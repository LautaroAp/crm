<?php
namespace Servicio\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element;
use Element\Number;



class ServicioForm extends Form
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
     * Current pais.
     * @var Pais\Entity\Pais 
     */
    private $servicio = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $servicio = null)
    {
        // Define form name
        parent::__construct('pais-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->servicio = $servicio;
        
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
            'name' => 'descripcion',
            'value' => '',
            'options' => [
                'label' => 'Descripcion',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'costo',
            'value' => '',
            'options' => [
                'label' => 'Costo',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'cant_animales',
            'value' => '',
            'options' => [
                'label' => 'Cantidad de animales',
            ],
        ]);
        
        

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Guardar'
            ],
        ]);
    }
    
    
    
  private function addInputFilter() 
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();        
                
   
       
        $inputFilter->add([
            'name' => 'descripcion',
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
        
          $inputFilter->add([
            'name' => 'costo',
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
         
          $inputFilter->add([
            'name' => 'cant_animales',
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