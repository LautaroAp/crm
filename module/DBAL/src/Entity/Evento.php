<?php

namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Evento
 *
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="EVENTO")
 */
class Evento {
    //put your code here

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_EVENTO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="FECHA_EVE", type="datetime")
     */
    protected $fecha;

    /**
     * Many Eventos have One TipoEvento.
     * @ORM\ManyToOne(targetEntity="TipoEvento")
     * @ORM\JoinColumn(name="TIPO_EVE", referencedColumnName="ID_EVENTO")
     */
    protected $tipo;

    /**
     * Many Eventos have One Persona.
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="PERSONA", referencedColumnName="ID")
     */
    protected $persona;

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

     /**
     * @ORM\Column(name="TIPO_PERSONA", nullable=true, type="string")
     */
    protected $tipo_persona;
    protected $nombre_persona;
    protected $usuario_ejecutivo;

    public function __construct() {
        $persona = $this->persona;
        if (!is_null($persona)) {
            $this->nombre_persona = $persona->getNombre();
        } else {
            $this->nombre_persona= null;
        }

        $ejecutivo = $this->ejecutivo;
        if (!is_null($ejecutivo)) {
            $this->usuario_ejecutivo = $ejecutivo->getUsuario();
        } else {
            $this->usuario_ejecutivo = null;
        }
    }

    function getId() {
        return $this->id;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getTipo() {
        if (!is_null($this->tipo)) {
            return $this->tipo->getNombre();
        } else {
            return null;
        }
    }

    function getTipoId() {
        if (!is_null($this->tipo)) {
            return $this->tipo->getId();
        } else {
            return null;
        }
    }

    function getId_persona() {
        return $this->persona;
    }

    function getId_ejecutivo() {
        return $this->ejecutivo;
    }

    function setId($id_evento) {
        $this->id_evento = $id_evento;
        return $this;
    }

    function setFecha($fecha_evento) {
        $this->fecha = $fecha_evento;
        return $this;
    }

    function setTipo($tipo_evento) {
        $this->tipo = $tipo_evento;
        return $this;
    }

    function setId_persona($id_persona) {
        $this->persona = $id_persona;
        return $this;
    }

    function setId_ejecutivo($id_ejecutivo) {
        $this->ejecutivo = $id_ejecutivo;
        return $this;
    }

    public function getNombrePersona() {
        if (is_null($this->persona)) {
            return null;
        } else {
            return $this->persona->getNombre();      
        }
    }

    public function getUsuarioEjecutivo() {
        if (is_null($this->ejecutivo)) {
            return null;
        } else {
            return $this->ejecutivo->getUsuario();
        }
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Get the value of tipo_persona
     */ 
    public function getTipo_persona()
    {
        return $this->tipo_persona;
    }

    /**
     * Set the value of tipo_persona
     *
     * @return  self
     */ 
    public function setTipo_persona($tipo_persona)
    {
        $this->tipo_persona = $tipo_persona;

        return $this;
    }
}
