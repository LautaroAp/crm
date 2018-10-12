<?php
namespace Producto\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;



class ProductoForm extends Form
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
     * Current producto.
     * @var Producto\Entity\Producto 
     */
    private $producto = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $producto = null)
    {
        // Define form name
        parent::__construct('producto-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->producto = $producto;
        
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
            'name' => 'id_evento',
            'value' => '',
            'options' => [
                'label' => 'ID evento',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'cantidad',
            'value' => '',
            'options' => [
                'label' => 'Cantidad',
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
            'name' => 'precio_total',
            'value' => '',
            'options' => [
                'label' => 'Cantidad',
            ],
        ]);

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
            'name' => 'iva',
            'value' => '',
            'options' => [
                'label' => 'IVA',
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
            'type' => 'text',
            'name' => 'tipo_prod',
            'value' => '',
            'options' => [
                'label' => 'Tipo de producto',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'usuarios_adicionales',
            'value' => '',
            'options' => [
                'label' => 'Usuarios adicionales',
            ],
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'version_terminal',
            'value' => '',
            'options' => [
                'label' => 'Version terminal',
            ],
        ]);
            
        
        $this->add([
            'type' => 'text',
            'name' => 'version_terminal',
            'value' => '',
            'options' => [
                'label' => 'Version terminal',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'terminales',
            'value' => '',
            'options' => [
                'label' => 'Terminales',
            ],
        ]);


        
        $this->add([
            'type' => 'text',
            'name' => 'version_terminal',
            'value' => '',
            'options' => [
                'label' => 'Version terminal',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'tipo_licencia',
            'value' => '',
            'options' => [
                'label' => 'Tipo de licencia',
            ],
        ]);
        
        
        $this->add([
            'type' => 'text',
            'name' => 'version',
            'value' => '',
            'options' => [
                'label' => 'Version',
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
                
   
    }       

    
}