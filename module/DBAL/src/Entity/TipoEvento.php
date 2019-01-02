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
class TipoEvento
{
    //put your code here

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_EVENTO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */

    protected $id_tipo_evento;

    /**
     * @ORM\Column(name="NOMBRE_EVENTO", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * Many TipoEvento have One Categoria.
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CATEGORIA_EVENTO", referencedColumnName="ID")
     */
    private $categoria_evento;


    function getId()
    {
        return $this->id_tipo_evento;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function setId_tipoevento($id)
    {
        $this->id_tipo_evento = $id;
    }

    function setNombre($nombre)
    {
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

    /**
     * Get the value of categoria_evento
     */
    public function getCategoria_evento()
    {
        return $this->categoria_evento;
    }

    /**
     * Set the value of categoria_evento
     *
     * @return  self
     */
    public function setCategoria_evento($categoria_evento)
    {
        $this->categoria_evento = $categoria_evento;

        return $this;
    }

    public function getNombreCategoriaEvento()
    {
        $categoria = $this->getCategoria_evento();
        if (is_null($categoria)) {
            return null;
        } else {
            return $this->getCategoria_evento()->getNombre();
        }
    }

    function getCategoriaId() {
        if (!is_null($this->categoria_evento)) {
            return $this->categoria_evento->getId();
        } else {
            return null;
        }
    }

}
