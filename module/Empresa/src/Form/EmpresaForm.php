<?php
namespace Empresa\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;



class EmpresaForm extends Form
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
    private $empresa = null;
    
    
    /**
     * Constructor.     
     */
   
    public function __construct($scenario = 'create', $entityManager = null, $empresa = null)
    {
        // Define form name
        parent::__construct('pais-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->empresa = $empresa;
        
        $this->addElements();
        //$this->addInputFilter();          
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
                'label' => 'Nombre',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'direccion',
            'value' => '',
            'options' => [
                'label' => 'Dirección',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'telefono',
            'value' => '',
            'options' => [
                'label' => 'Teléfono',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'mail',
            'value' => '',
            'options' => [
                'label' => 'E-mail',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'movil',
            'value' => '',
            'options' => [
                'label' => 'Movil',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'fax',
            'value' => '',
            'options' => [
                'label' => 'Fax',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'web',
            'value' => '',
            'options' => [
                'label' => 'Web',
            ],
        ]);
        
            
        $this->add([
            'type' => 'text',
            'name' => 'cuit_cuil',
            'value' => '',
            'options' => [
                'label' => 'CUIT/CUIL',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'vencimiento_cai',
            'value' => '',
            'options' => [
                'label' => 'Vencimiento CAI',
            ],
        ]);
        
         
        $this->add([
            'type' => 'text',
            'name' => 'razon_social',
            'value' => '',
            'options' => [
                'label' => 'Razón social',
            ],
        ]);
        
         
        $this->add([
            'type' => 'text',
            'name' => 'tipo_iva',
            'value' => '',
            'options' => [
                'label' => 'Tipo IVA',
            ],
        ]);
         
        $this->add([
            'type' => 'text',
            'name' => 'localidad',
            'value' => '',
            'options' => [
                'label' => 'Localidad',
            ],
        ]);
        
         $this->add([
            'type' => 'text',
            'name' => 'provincia',
            'value' => '',
            'options' => [
                'label' => 'Provincia',
            ],
        ]);
         
          $this->add([
            'type' => 'text',
            'name' => 'pais',
            'value' => '',
            'options' => [
                'label' => 'Pais',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'CP',
            'value' => '',
            'options' => [
                'label' => 'Codigo Postal',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'parametro_vencimiento',
            'value' => '',
            'options' => [
                'label' => 'Meses de vencimiento',
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
        
         $inputFilter->add([
            'name' => 'direccion',
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
            'name' => 'telefono',
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
            'name' => 'mail',
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
            'name' => 'movil',
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
            'name' => 'fax',
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
            'name' => 'web',
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
            'name' => 'cuit_cuil',
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
            'name' => 'vencimiento_cai',
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
            'name' => 'razon_social',
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
            'name' => 'tipo_iva',
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
            'name' => 'localidad',
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
            'name' => 'provincia',
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
            'name' => 'pais',
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
            'name' => 'CP',
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