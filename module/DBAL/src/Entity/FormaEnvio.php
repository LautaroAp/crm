<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of FormaEnvio
 *
 * This class represents a registered forma de pago.
 * @ORM\Entity()
 * @ORM\Table(name="FORMA_DE_ENVIO")
 */
class FormaEnvio
{
    //put your code here

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */

    protected $id;

    /**
     * @ORM\Column(name="FORMA_DE_ENVIO", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * @ORM\Column(name="VALOR", nullable=true, type="decimal")
     */
    protected $valor;

    /**
     * @ORM\Column(name="FECHA_ENTREGA", type="integer")
     */
    protected $fecha_entrega;


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
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get the value of fecha_entrega
     */ 
    public function getFecha_entrega()
    {
        return $this->fecha_entrega;
    }

    /**
     * Set the value of fecha_entrega
     *
     * @return  self
     */ 
    public function setFecha_entrega($fecha_entrega)
    {
        $this->fecha_entrega = $fecha_entrega;

        return $this;
    }

    public function getJSON(){

        $output = "";
        $output .= '"Id": "' . $this->getId() .'", ';
        $output .= '"Nombre": "' . $this->getNombre() .'", ';
        $output .= '"Descripcion": "' . $this->getDescripcion() .'", ';
        $output .= '"Valor": "' . $this->getValor() .'", ';
        $output .= '"Fecha de Entrega": "' . $this->getFecha_entrega() .'" ';

        return  '{'.$output.'}' ;
    }

}