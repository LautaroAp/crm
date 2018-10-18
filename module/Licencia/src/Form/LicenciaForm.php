<?php
namespace Licencia\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;



class LicenciaForm extends Form
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
     * Current licencia.
     * @var Licencia\Entity\Licencia 
     */
    private $licencia = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $licencia = null)
    {
        // Define form name
        parent::__construct('licencia-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->licencia = $licencia;
        
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
            'name' => 'nombre_licencia',
            'value' => '',
            'options' => [
                'label' => 'Nombre licencia',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'version_licencia',
            'value' => '',
            'options' => [
                'label' => 'Version licencia',
            ],
        ]);


         $this->add([
            'type' => 'text',
            'name' => 'precio_local',
            'value' => '',
            'options' => [
                'label' => 'Precio local',
            ],
        ]);
         
          $this->add([
            'type' => 'text',
            'name' => 'precio_extranjero',
            'value' => '',
            'options' => [
                'label' => 'Precio Extranjero',
            ],
        ]);
          
           $this->add([
            'type' => 'text',
            'name' => 'iva',
            'value' => '',
            'options' => [
                'label' => '% IVA',
            ],
        ]);
           
           $this->add([
            'type' => 'text',
            'name' => 'descuento',
            'value' => '',
            'options' => [
                'label' => '% Dto.',
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
                
   
        
        // Add input for "nombre_licencia" field
        $inputFilter->add([
            'name' => 'nombre_licencia',
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


        // Add input for "version_licencia" field
       $inputFilter->add([
            'name' => 'version_licencia',
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
            'name' => 'precio_local',
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
            'name' => 'descuento',
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
            'name' => 'iva',
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
            'name' => 'precio_extranjero',
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