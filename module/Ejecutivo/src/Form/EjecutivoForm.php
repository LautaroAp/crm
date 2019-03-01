<?php
namespace Ejecutivo\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use DBAL\Entity\Ejecutivo;
use Zend\InputFilter\InputFilter;
use Ejecutivo\Validator\EjecutivoExistsValidator;

/**
 * This form is used to collect ejecutivo's email, full name, password and status. The form 
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, ejecutivo
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class EjecutivoForm extends Form
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
     * Current ejecutivo.
     * @var Ejecutivo\Entity\Ejecutivo 
     */
    private $ejecutivo = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $ejecutivo = null)
    {
        // Define form name
        parent::__construct('ejecutivo-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->ejecutivo = $ejecutivo;
        
        $this->addElements();
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
              
        // Add "nombre" field
        $this->add([            
            'type'  => 'text',
            'name' => 'nombre',
            'options' => [
                'label' => 'Nombre',
            ],
        ]);
        
        // Add "email" field
        $this->add([            
            'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Mail',
            ],
        ]);
        
        // Add "usuario" field
        $this->add([            
            'type'  => 'text',
            'name' => 'usuario',
            'options' => [
                'label' => 'Usuario',
            ],
        ]);
        
        // Add "clave" field
        $this->add([            
            'type'  => 'Zend\Form\Element\Password',
            'name' => 'clave',
            'options' => [
                'label' => 'Clave',
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
       
        
    }       
}