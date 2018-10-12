<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of TipoEvento
 *
 * This class represents a registered tipoevento.
 * @ORM\Entity()
 * @ORM\Table(name="TIPO_EVENTO")
 */
class TipoEvento {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_EVENTO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
   
    
    protected $id_tipo_evento;   
    
    /**
     * @ORM\Column(name="NOMBRE_EVENTO", nullable=true, type="string", length=255)
     */
    protected $nombre;

     
    function getId() {
        return $this->id_tipo_evento;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setId_tipoevento($id) {
        $this->id_tipo_evento = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

}
