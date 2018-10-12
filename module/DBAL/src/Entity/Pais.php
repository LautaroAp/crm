<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Pais
 *
 * This class represents a registered pais.
 * @ORM\Entity()
 * @ORM\Table(name="PAIS")
 */
class Pais {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_PAIS", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
   protected $id_pais;   
  
     /**
     * @ORM\Column(name="NOMBRE_PAIS", nullable=true, type="string", length=255)
     */
    protected $nombre;
    
    
    function getId() {
        return $this->id_pais;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setId($id_pais) {
        $this->id_pais = $id_pais;
    }

    function setNombre($nombre_pais) {
        $this->nombre = $nombre_pais;
    }
    
}