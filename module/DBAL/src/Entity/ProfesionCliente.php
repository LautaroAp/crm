<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ProfesionCliente  
 *
 * This class represents a registered categoriacliente.
 * @ORM\Entity()
 * @ORM\Table(name="PROFESION_CLIENTE")
 */
class ProfesionCliente {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_TIPO_CLIENTE", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
   
    
    protected $id_profesion;   
    
    /**
     * @ORM\Column(name="NOMBRE_TIPO_CLIENTE", nullable=true, type="string", length=255)
     */
    protected $nombre;

     
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


}
