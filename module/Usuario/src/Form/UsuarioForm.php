<?php
namespace Usuario\Form;

use Zend\Form\Form;
use DBAL\Entity\Usuario;

/**
 * This form is used to collect usuario's email, full name, password and status. The form 
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, usuario
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class UsuarioForm extends Form
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
     * Current usuario.
     * @var Usuario\Entity\Usuario 
     */
    private $usuario = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $usuario = null)
    {
        // Define form name
        parent::__construct('usuario-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->usuario = $usuario;
        
        $this->addElements();
        $this->addInputFilter();          
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
              
        // Add "usuario" field
        $this->add([            
            'type'  => 'text',
            'name' => 'nombre',
            'options' => [
                'label' => 'Usuario Adicional',
            ],
        ]);
        
        
        // Add "telefono" field
        $this->add([            
            'type'  => 'text',
            'name' => 'telefono',
            'options' => [
                'label' => 'Teléfono',
            ],
        ]);
        
        // Add "mail" field
        $this->add([            
            'type'  => 'text',
            'name' => 'mail',
            'options' => [
                'label' => 'Mail',
            ],
        ]);
        
        // Add "usuario" field
        $this->add([            
            'type'  => 'text',
            'name' => 'skype',
            'options' => [
                'label' => 'Skype',
            ],
        ]);
        
        // Add "id" field
        $this->add([            
            'type'  => 'text',
            'name' => 'id',
            'options' => [
                'label' => 'id',
            ],
        ]);
        
        
        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ],
        ]);

    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    
   
    private function addInputFilter() 
    {
        // Create main input filter
                   
       //$inputFilter = new InputFilter();        
        
        $inputFilter = $this->getInputFilter();  
        $this->setInputFilter($inputFilter);
               
        // Add input for "usuario" field
        $inputFilter->add([
                'name'     => 'nombre',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
        
        // Add input for "telefono" field
        $inputFilter->add([
                'name'     => 'telefono',
                'required' => false,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
        
        // Add input for "mail" field
        $inputFilter->add([
                'name'     => 'mail',
                'required' => false,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
        
        // Add input for "mail" field
        $inputFilter->add([
                'name'     => 'skype',
                'required' => false,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
              
    }       
}