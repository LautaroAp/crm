<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Provincia
 *
 * This class represents a registered provincia.
 * @ORM\Entity()
 * @ORM\Table(name="PROVINCIA")
 */


class Provincia {
    //put your code here
    
       /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Pais")
     * @ORM\JoinColumn(name="ID_PAIS", referencedColumnName="ID_PAIS")
     */ 
    protected $id_pais;   
  
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_PROVINCIA", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_provincia;
    
     /**
     * @ORM\Column(name="NOMBRE_PROVINCIA", nullable=true, type="string", length=255)
     */
    protected $nombre_provincia;
    
    
    function getId_pais() {
        return $this->id_pais;
    }

    function getId_provincia() {
        return $this->id_provincia;
    }

    function getNombre_provincia() {
        return $this->nombre_provincia;
    }

    function setId_pais($id_pais) {
        $this->id_pais = $id_pais;
    }

    function setId_provincia($id_provincia) {
        $this->id_provincia = $id_provincia;
    }

    function setNombre_provincia($nombre_provincia) {
        $this->nombre_provincia = $nombre_provincia;
    }


    
}