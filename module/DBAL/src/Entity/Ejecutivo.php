<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Ejecutivo
 *
 * This class represents a registered executive
 * @ORM\Entity()
 * @ORM\Table(name="EJECUTIVO")
 */
class Ejecutivo
{

    //================================================================================
    // Properties
    //================================================================================

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_EJECUTIVO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="NOMUSR", nullable=true, type="string", length=255)
     */
    protected $usuario;

    /**
     * @ORM\Column(name="PASSUSR", nullable=true, type="string", length=255)
     */
    protected $clave;

    /**
     * @ORM\Column(name="ACTIVO", nullable=true, type="string", length=1)
     */
    protected $activo;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_PERSONA", referencedColumnName="ID")
     */
    private $persona;

    //================================================================================
    // Methods
    //================================================================================

    function getId()
    {
        return $this->id;
    }

    function getUsuario()
    {
        return $this->usuario;
    }

    function getClave()
    {
        return $this->clave;
    }

    function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    function setClave($clave)
    {
        $this->clave = $clave;
    }

    function setActivo($activo)
    {
        $this->activo = $activo;
    }
    function inactivar()
    {
        $this->activo = "N";
    }
    function activar()
    {
        $this->activo = "S";
    }
    function isActivo()
    {
        return $this->activo == "S";
    }

    public function getPersona()
    {
        return $this->persona;
    }

    public function setPersona($persona)
    {
        $this->persona = $persona;

        return $this;
    }
}
