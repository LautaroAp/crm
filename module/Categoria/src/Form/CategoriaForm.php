<?php
namespace Categoria\Form;

use Zend\Form\Form;


class CategoriaForm extends Form
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
     * Current Categoriaevento.
     * @var Categoria\Entity\Categoria 
     */
    private $categoriaEvento = null;
    
        
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $categoriaEvento = null)
    {
        // Define form name
        parent::__construct('categoriaevento-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->categoriaEvento = $categoriaEvento;
        
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
                'label' => 'Nombre del Tipo de Actividad',
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