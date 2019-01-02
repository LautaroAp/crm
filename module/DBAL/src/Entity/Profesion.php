<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ProfesionCliente  
 *
 * This class represents a registered categoriacliente.
 * @ORM\Entity()
 * @ORM\Table(name="PROFESION")
 */
class Profesion {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
   
    
    protected $id_profesion;   
    
    /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string", length=255)
     */
    protected $nombre;
 
    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string", length=255)
     */
    protected $descripcion;

     
    function getId() {
        return $this->id_profesion;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setId($id_profesion) {
        $this->id_profesion = $id_profesion;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
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
