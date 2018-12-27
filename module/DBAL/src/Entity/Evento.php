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
     * Many Eventos have One Cliente.
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumn(name="CLIENTE", referencedColumnName="ID_CLIENTE")
     */
    protected $cliente;

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
    protected $nombre_cliente;
    protected $apellido_cliente;
    protected $usuario_ejecutivo;

    public function __construct() {
        $cliente = $this->cliente;
        if (!is_null($cliente)) {
            $this->nombre_cliente = $cliente->getNombre();
            $this->apellido_cliente = $cliente->getApellido();
        } else {
            $this->nombre_cliente = null;
            $this->apellido_cliente = null;
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

    function getId_cliente() {
        return $this->cliente;
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

    function setId_cliente($id_cliente) {
        $this->cliente = $id_cliente;
        return $this;
    }

    function setId_ejecutivo($id_ejecutivo) {
        $this->ejecutivo = $id_ejecutivo;
        return $this;
    }

    public function getNombreCliente() {
        if (is_null($this->cliente)) {
            return null;
        } else {
            return $this->cliente->getPersona()->getNombre();      
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
}
