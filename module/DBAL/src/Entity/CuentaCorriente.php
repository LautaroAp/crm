<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of CuentaCorriente
 * This class represents a registered servicio.
 * @ORM\Entity()
 * @ORM\Table(name="CUENTA_CORRIENTE")
 */
class CuentaCorriente
{

    //================================================================================
    // Properties
    //================================================================================

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Many CuentaCorriente have One Persona.
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_PERSONA", referencedColumnName="ID")
     */

    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION", referencedColumnName="ID")
     */
    private $transaccion;

    /**
     * @ORM\Column(name="fecha", nullable=true, type="date")
     */
    protected $fecha;

    /**
     * @ORM\Column(name="TIPO_ACTIVIDAD", nullable=true, type="string")
     */
    protected $tipoActividad;


    /**
     * @ORM\Column(name="MONTO", nullable=true, type="decimal")
     */
    protected $monto;


    /**
     * @ORM\Column(name="NRO_TIPO_TRANSACCION", nullable=true, type="integer")
     */

    protected $nroTipoTransaccion;

    /**
     * @ORM\Column(name="FACTURADO", nullable=true, type="boolean")
     */
    protected $facturado;

    //================================================================================
    // Methods
    //================================================================================

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
     * Get the value of transaccion
     */
    public function getTransaccion()
    {
        return $this->transaccion;
    }

    /**
     * Set the value of transaccion
     *
     * @return  self
     */
    public function setTransaccion($transaccion)
    {
        $this->transaccion = $transaccion;

        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of tipoActividad
     */
    public function getTipoActividad()
    {
        return $this->tipoActividad;
    }

    /**
     * Set the value of tipoActividad
     *
     * @return  self
     */
    public function setTipoActividad($tipoActividad)
    {
        $this->tipoActividad = $tipoActividad;

        return $this;
    }

    /**
     * Get the value of monto
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set the value of monto
     *
     * @return  self
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get the value of nroTipoTransaccion
     */
    public function getNroTipoTransaccion()
    {
        return $this->nroTipoTransaccion;
    }

    /**
     * Set the value of nroTipoTransaccion
     *
     * @return  self
     */
    public function setNroTipoTransaccion($nroTipoTransaccion)
    {
        $this->nroTipoTransaccion = $nroTipoTransaccion;

        return $this;
    }

    /**
     * Get the value of facturadp
     */
    public function getFacturado()
    {
        return $this->facturado;
    }

    /**
     * Set the value of facturadp
     *
     * @return  self
     */
    public function setFacturado($facturado)
    {
        $this->facturado = $facturado;

        return $this;
    }

    //================================================================================
    // JSON
    //================================================================================

    public function getJSON()
    {
        $output = "";
        $output .= '"Id": "' . $this->getId() . '", ';
        if (!is_null($this->getPersona())) {
            $output .= '"Persona": ' . $this->getPersona()->getJSON(false) . ', ';
        }
        if (!is_null($this->getTransaccion())) {
            $output .= '"Transaccion": ' . $this->getTransaccion()->getJSON() . ', ';
        } else {
            $output .= '"Transaccion": "' . "" . '", ';
        }
        $output .= '"Fecha": "' . $this->getFecha() . '", ';
        $output .= '"Tipo Actividad": "' . $this->getTipoActividad() . '", ';
        $output .= '"Nro Actividad": "' . $this->getNroTipoTransaccion() . '", ';
        $output .= '"Monto": "' . $this->getMonto() . '" ';

        return  '{' . $output . '}';
    }
}
