<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of CategoriaCliente
 *
 * This class represents a registered categoriacliente.
 * @ORM\Entity()
 * @ORM\Table(name="CATEGORIA_CLIENTE")
 */
class CategoriaCliente {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_CATEGORIA_CLIENTE", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
   
    
    protected $id_categoria_cliente;   
    
    /**
     * @ORM\Column(name="NOMBRE_CATEGORIA", nullable=true, type="string", length=255)
     */
    protected $nombre;

     
    function getId() {
        return $this->id_categoria_cliente;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setId($id) {
        $this->id_categoria_cliente = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

}
