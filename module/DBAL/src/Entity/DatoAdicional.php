<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of DatoAdicional
 *
 * This class represents a registered forma de pago.
 * @ORM\Entity()
 * @ORM\Table(name="DATO_ADICIONAL")
 */
class DatoAdicional
{
    //put your code here

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id; 

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_FICHA_PERSONA", referencedColumnName="ID")
     */
    protected $id_ficha_persona;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_REFERENCIA_PERSONA", referencedColumnName="ID")
     */
    protected $id_referencia_persona;

     /**
     * @ORM\Column(name="DATO_ADICIONAL", nullable=true, type="string")
     */
    private $dato_adicional;

      /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    private $descripcion;

    /**
     * @ORM\Column(name="TIPO", type="string")
     */
    protected $tipo;


    public function getJSON(){
        $output = "";
        $output .= '"Id": "' . $this->getId() .'", ';
        $output .= '"Ficha Persona": "' . $this->getId_ficha_persona() .'", ';
        $output .= '"Referencia Persona": "' . $this->getId_referencia_persona() .'", ';
        $output .= '"Dato Adicional": "' . $this->getDato_adicional() .'", ';
        $output .= '"Descripcion": "' . $this->getDescripcion() .'", ';
        $output .= '"Tipo": "' . $this->getTipo() .'" ';

        return  '{'.$output.'}' ;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id_ficha_persona
     */ 
    public function getId_ficha_persona()
    {
        return $this->id_ficha_persona;
    }

    /**
     * Set the value of id_ficha_persona
     *
     * @return  self
     */ 
    public function setId_ficha_persona($id_ficha_persona)
    {
        $this->id_ficha_persona = $id_ficha_persona;

        return $this;
    }

    /**
     * Get the value of id_referencia_persona
     */ 
    public function getId_referencia_persona()
    {
        return $this->id_referencia_persona;
    }

    /**
     * Set the value of id_referencia_persona
     *
     * @return  self
     */ 
    public function setId_referencia_persona($id_referencia_persona)
    {
        $this->id_referencia_persona = $id_referencia_persona;

        return $this;
    }

    /**
     * Get the value of dato_adicional
     */ 
    public function getDato_adicional()
    {
        return $this->dato_adicional;
    }

    /**
     * Set the value of dato_adicional
     *
     * @return  self
     */ 
    public function setDato_adicional($dato_adicional)
    {
        $this->dato_adicional = $dato_adicional;

        return $this;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }
}