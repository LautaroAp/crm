<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Ganaderia
 *
 * This class represents a registered Ganaderia.
 * @ORM\Entity()
 * @ORM\Table(name="CATEGORIA_SERVICIO")
 */
class Ganaderia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="ID_EMPRESA", nullable=true, type="integer")
     */
    protected $empresa;

    /**
     * @ORM\Column(name="ID_PERSONA", nullable=true, type="integer")
     */
    protected $persona;

    /**
     * @ORM\Column(name="ID_PROFESION", nullable=true, type="integer")
     */
    protected $profesion;

    /**
     * @ORM\Column(name="CARGO", nullable=true, type="string")
     */
    protected $cargo;

    /**
     * @ORM\Column(name="ACTIVIDAD", nullable=true, type="string")
     */
    protected $actividad;

    /**
     * @ORM\Column(name="EMPRESA", nullable=true, type="string")
     */
    protected $nombre_empresa;

    /**
     * @ORM\Column(name="RAZA_MANEJO", nullable=true, type="string")
     */
    protected $raza_manejo;

    /**
     * @ORM\Column(name="NRO_ANIMALES", nullable=true, type="integer")
     */
    protected $nro_animales;

    /**
     * @ORM\Column(name="NRO_ESTABLECIMIENTOS", nullable=true, type="integer")
     */
    protected $nro_establecimientos;



    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set the value of empresa
     *
     * @return  self
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get the value of persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set the value of persona
     *
     * @return  self
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get the value of profesion
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set the value of profesion
     *
     * @return  self
     */
    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get the value of cargo
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set the value of cargo
     *
     * @return  self
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get the value of actividad
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set the value of actividad
     *
     * @return  self
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get the value of nombre_empresa
     */
    public function getNombre_empresa()
    {
        return $this->nombre_empresa;
    }

    /**
     * Set the value of nombre_empresa
     *
     * @return  self
     */
    public function setNombre_empresa($nombre_empresa)
    {
        $this->nombre_empresa = $nombre_empresa;

        return $this;
    }

    /**
     * Get the value of raza_manejo
     */
    public function getRaza_manejo()
    {
        return $this->raza_manejo;
    }

    /**
     * Set the value of raza_manejo
     *
     * @return  self
     */
    public function setRaza_manejo($raza_manejo)
    {
        $this->raza_manejo = $raza_manejo;

        return $this;
    }

    /**
     * Get the value of nro_animales
     */
    public function getNro_animales()
    {
        return $this->nro_animales;
    }

    /**
     * Set the value of nro_animales
     *
     * @return  self
     */
    public function setNro_animales($nro_animales)
    {
        $this->nro_animales = $nro_animales;

        return $this;
    }

    /**
     * Get the value of nro_establecimientos
     */
    public function getNro_establecimientos()
    {
        return $this->nro_establecimientos;
    }

    /**
     * Set the value of nro_establecimientos
     *
     * @return  self
     */
    public function setNro_establecimientos($nro_establecimientos)
    {
        $this->nro_establecimientos = $nro_establecimientos;

        return $this;
    }
}
