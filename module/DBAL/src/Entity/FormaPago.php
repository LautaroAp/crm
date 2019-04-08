<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of FormaPago
 *
 * This class represents a registered forma de pago.
 * @ORM\Entity()
 * @ORM\Table(name="FORMA_DE_PAGO")
 */
class FormaPago
{
    //put your code here

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */

    protected $id;

    /**
     * @ORM\Column(name="FORMA_DE_PAGO", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

     /**
     * @ORM\Column(name="BONIFICACION", nullable=true, type="decimal")
     */
    private $bonificacion;
      /**
     * @ORM\Column(name="RECARGO", nullable=true, type="decimal")
     */
    private $recargo;

    /**
     * @ORM\Column(name="TIPO", nullable=true, type="string")
     */
    protected $tipo;


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
     * Get the value of bonificacion
     */ 
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    /**
     * Set the value of bonificacion
     *
     * @return  self
     */ 
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;

        return $this;
    }

    /**
     * Get the value of recargo
     */ 
    public function getRecargo()
    {
        return $this->recargo;
    }

    /**
     * Set the value of recargo
     *
     * @return  self
     */ 
    public function setRecargo($recargo)
    {
        $this->recargo = $recargo;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getJSON(){

        $output = "";
        $output .= '"Id": "' . $this->getId() .'", ';
        $output .= '"Nombre": "' . $this->getNombre() .'", ';
        $output .= '"Descripcion": "' . $this->getDescripcion() .'", ';
        $output .= '"Recargo": "' . $this->getRecargo() .'", ';
        $output .= '"Bonificacion": "' . $this->getBonificacion() .'" ';

        return  '{'.$output.'}' ;
    }
}