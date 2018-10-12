<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Servicio
 *
 * This class represents a registered servicio.
 * @ORM\Entity()
 * @ORM\Table(name="SERVICIO")
 */
class Servicio {
    //put your code here
    
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_SERVICIO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_servicio;
    
     /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string", length=255)
     */
    protected $descripcion;
    
    /**
     * @ORM\Column(name="COSTO", nullable=true, type="string", length=255)
     */
    protected $costo;
    
    /**
     * @ORM\Column(name="CANTIDAD_ANIMALES", nullable=true, type="string", length=255)
     */
    protected $cant_animales;
    
    function getId() {
        return $this->id_servicio;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getCosto() {
        return $this->costo;
    }

    function getCant_animales() {
        return $this->cant_animales;
    }

    function setId($id_servicio) {
        $this->id_servicio = $id_servicio;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setCosto($costo) {
        $this->costo = $costo;
    }

    function setCant_animales($cant_animales) {
        $this->cant_animales = $cant_animales;
    }



}
