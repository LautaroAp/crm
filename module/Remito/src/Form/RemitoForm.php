<?php
namespace Remito\Form;

use Zend\Form\Form;

class RemitoForm extends Form
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
    private $Remito = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $Remito = null)
    {
        // Define form name
        parent::__construct('pais-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->Remito = $Remito;
        
        $this->addElements();
        $this->addInputFilter();          
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
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
            'name' => 'descripcion',
            'value' => '',
            'options' => [
                'label' => 'Descripcion',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'precio',
            'value' => '',
            'options' => [
                'label' => 'Precio',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'categoria',
            'value' => '',
            'options' => [
                'label' => 'Categoria',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'moneda',
            'value' => '',
            'options' => [
                'label' => 'Moneda',
            ],
        ]);
     
        $this->add([
            'type' => 'text',
            'name' => 'descuento',
            'value' => '',
            'options' => [
                'label' => 'Descuento',
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
    //     // Create main input filter
    //     $inputFilter = $this->getInputFilter();        
    //     $inputFilter->add([
    //         'name' => 'descripcion',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => 'StringTrim'],
    //         ],
    //         'validators' => [
    //             [
    //                 'name' => 'StringLength',
    //             ],
    //         ],
    //     ]);
        
    //       $inputFilter->add([
    //         'name' => 'costo',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => 'StringTrim'],
    //         ],
    //         'validators' => [
    //             [
    //                 'name' => 'StringLength',
    //             ],
    //         ],
    //     ]);
         
    //       $inputFilter->add([
    //         'name' => 'cant_animales',
    //         'required' => true,
    //         'filters' => [
    //             ['name' => 'StringTrim'],
    //         ],
    //         'validators' => [
    //             [
    //                 'name' => 'StringLength',
    //             ],
    //         ],
    //     ]);
     }       

    
}