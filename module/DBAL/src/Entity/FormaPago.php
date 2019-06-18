<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of FormaPago
 *
 * This class represents a registered forma de pago.
 * @ORM\Entity()
 * @ORM\Table(name="FORMA_DE_PAGO")
 */
class FormaPago
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
     * @ORM\Column(name="FORMA_DE_PAGO", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * @ORM\Column(name="BONIFICACION", nullable=true, type="decimal")
     */
    private $bonificacion;
    /**
     * @ORM\Column(name="RECARGO", nullable=true, type="decimal")
     */
    private $recargo;

    /**
     * @ORM\Column(name="TIPO", nullable=true, type="string")
     */
    protected $tipo;

    /**
     * @ORM\Column(name="FECHA_EMISION", nullable=true, type="date")
     */
    protected $fechaEmision;

    /**
     * @ORM\Column(name="FECHA_ACREDITACION", nullable=true, type="date")
     */
    protected $fechaAcreditacion;

    /**
     * @ORM\Column(name="NRO", nullable=true, type="string")
     */
    protected $numero;


    /**
     * @ORM\Column(name="MONTO", nullable=true, type="decimal")
     */
    protected $monto;

    /**
     * @ORM\Column(name="DEBITO_CREDITO", nullable=true, type="string")
     */
    protected $debitoCredito;


    /**
     * Many Services have One Proveedor.
     * @ORM\ManyToOne(targetEntity="Banco")
     * @ORM\JoinColumn(name="ID_BANCO", referencedColumnName="ID")
     */
    private $banco;

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
     * Get the value of bonificacion
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    /**
     * Set the value of bonificacion
     *
     * @return  self
     */
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;

        return $this;
    }

    /**
     * Get the value of recargo
     */
    public function getRecargo()
    {
        return $this->recargo;
    }

    /**
     * Set the value of recargo
     *
     * @return  self
     */
    public function setRecargo($recargo)
    {
        $this->recargo = $recargo;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return ($this->nombre);
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }



    /**
     * Get the value of fechaEmision
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * Set the value of fechaEmision
     *
     * @return  self
     */
    public function setFechaEmision($fechaEmision)
    {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    /**
     * Get the value of fechaAcreditacion
     */
    public function getFechaAcreditacion()
    {
        return $this->fechaAcreditacion;
    }

    /**
     * Set the value of fechaAcreditacion
     *
     * @return  self
     */
    public function setFechaAcreditacion($fechaAcreditacion)
    {
        $this->fechaAcreditacion = $fechaAcreditacion;

        return $this;
    }

    /**
     * Get the value of numero
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

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
     * Get the value of debitoCredito
     */
    public function getDebitoCredito()
    {
        return $this->debitoCredito;
    }

    /**
     * Set the value of debitoCredito
     *
     * @return  self
     */
    public function setDebitoCredito($debitoCredito)
    {
        $this->debitoCredito = $debitoCredito;

        return $this;
    }

    /**
     * Get many Services have One Proveedor.
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set many Services have One Proveedor.
     *
     * @return  self
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;

        return $this;
    }

    //================================================================================
    // JSON
    //================================================================================

    public function getJSON()
    {

        $output = "";
        $output .= '"Id": "' . $this->getId() . '", ';
        $output .= '"Nombre": "' . $this->getNombre() . '", ';
        $output .= '"Descripcion": "' . $this->getDescripcion() . '", ';
        $output .= '"Recargo": "' . $this->getRecargo() . '", ';
        $output .= '"Bonificacion": "' . $this->getBonificacion() . '" ';

        return  '{' . $output . '}';
    }
}
