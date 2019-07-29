<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of RegistroMovimiento
 *
 * This class represents a registered RegistroMovimiento.
 * @ORM\Entity()
 * @ORM\Table(name="REGISTRO_MOVIMIENTO")
 */
class RegistroMovimiento
{
    //================================================================================
    // Properties
    //================================================================================

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Many BienesTransacciones have One Bien.
     * @ORM\ManyToOne(targetEntity="Bienes")
     * @ORM\JoinColumn(name="ID_BIEN", referencedColumnName="ID")
     */

    private $bien;

    /**
     * @ORM\Column(name="FECHA", type="datetime")
     */
    protected $fecha;

    /**
     * Many Eventos have One Ejecutivo.
     * @ORM\ManyToOne(targetEntity="Ejecutivo")
     * @ORM\JoinColumn(name="EJECUTIVO", referencedColumnName="ID_EJECUTIVO")
     */
    protected $ejecutivo;


    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;


    //================================================================================
    // Methods
    //================================================================================

   

    //================================================================================
    // JSON
    //================================================================================

    public function getJSON()
    {

        $output = "";
        $output .= '"Id": "' . $this->getId() . '", ';
        $output .= '"Nombre": "' . $this->getNombre() . '", ';
        $output .= '"Descripcion": "' . $this->getDescripcion() . '" ';

        return  '{' . $output . '}';
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
     * Get the value of bien
     */ 
    public function getBien()
    {
        return $this->bien;
    }

    /**
     * Set the value of bien
     *
     * @return  self
     */ 
    public function setBien($bien)
    {
        $this->bien = $bien;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get many Eventos have One Ejecutivo.
     */ 
    public function getEjecutivo()
    {
        return $this->ejecutivo;
    }

    /**
     * Set many Eventos have One Ejecutivo.
     *
     * @return  self
     */ 
    public function setEjecutivo($ejecutivo)
    {
        $this->ejecutivo = $ejecutivo;

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
}
