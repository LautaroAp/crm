<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Transaccion
 *
 * This class represents a registered servicio.
 * @ORM\Entity()
 * @ORM\Table(name="TRANSACCION")
 */
class Transaccion
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
     * @ORM\Column(name="NUMERO", nullable=true, type="integer")
     */
    protected $numero_transaccion;

    /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DETALLE", nullable=true, type="string")
     */
    protected $detalle;

    /**
     * @ORM\Column(name="FECHA_CREACION", type="datetime")
     */
    protected $fecha_transaccion;

    /**
     * @ORM\Column(name="FECHA_VENCIMIENTO", type="datetime")
     */
    protected $fecha_vencimiento;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_PERSONA", referencedColumnName="ID")
     */
    protected $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Ejecutivo")
     * @ORM\JoinColumn(name="ID_EJECUTIVO", referencedColumnName="ID_EJECUTIVO")
     */
    protected $responsable;

    /**
     * @ORM\ManyToOne(targetEntity="Moneda")
     * @ORM\JoinColumn(name="ID_MONEDA", referencedColumnName="ID")
     */
    private $moneda;

    /**
     * @ORM\Column(name="TIPO", nullable=true, type="string", length=255)
     */
    protected $tipo_transaccion;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\BienesTransacciones", mappedBy="transaccion")
     */
    private $bienesTransacciones;

    /**
     * @ORM\Column(name="ESTADO", type="string")
     */
    protected $estado;

    /**
     * @ORM\Column(name="IMPORTE_TOTAL", nullable=true, type="decimal")
     */
    protected $importe_total;

    /**
     * @ORM\Column(name="SUBTOTAL", nullable=true, type="decimal")
     */
    protected $subtotal;

    /**
     * @ORM\Column(name="BONIFICACION_GENERAL", nullable=true, type="decimal")
     */
    protected $bonificacionGeneral;

    /**
     * @ORM\Column(name="BONIFICACION_IMPORTE", nullable=true, type="decimal")
     */
    protected $bonificacionImporte;

    /**
     * @ORM\Column(name="RECARGO_GENERAL", nullable=true, type="decimal")
     */
    protected $recargoGeneral;

    /**
     * @ORM\Column(name="RECARGO_IMPORTE", nullable=true, type="decimal")
     */
    protected $recargoImporte;

    /**
     * @ORM\ManyToOne(targetEntity="Iva")
     * @ORM\JoinColumn(name="IVA_GENERAL", referencedColumnName="ID")
     */
    protected $ivaGeneral;

    /**
     * @ORM\ManyToOne(targetEntity="FormaPago")
     * @ORM\JoinColumn(name="ID_FORMA_DE_PAGO", referencedColumnName="ID")
     */
    protected $formaPago;

    /**
     * @ORM\ManyToOne(targetEntity="FormaEnvio")
     * @ORM\JoinColumn(name="ID_FORMA_DE_ENVIO", referencedColumnName="ID")
     */
    protected $formaEnvio;

    /**
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION_PREVIA", referencedColumnName="ID")
     */
    protected $transaccionPrevia;

    /**
     * @ORM\Column(name="NUMERO_TIPO_TRANSACCION", nullable=true, type="integer")
     */
    protected $numeroTransaccionTipo;

    /**
     * @ORM\Column(name="FACTURADO", nullable=true, type="boolean")
     */
    protected $facturado;

    /**
     * @ORM\Column(name="OFICIAL", nullable=true, type="boolean")
     */
    protected $oficial;

    /**
     * @ORM\ManyToOne(targetEntity="Comprobante")
     * @ORM\JoinColumn(name="ID_COMPROBANTE", referencedColumnName="ID")
     */
    protected $comprobante;

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
     * Get the value of numero_transaccion
     */
    public function getNumero()
    {
        return $this->numero_transaccion;
    }

    /**
     * Set the value of numero_transaccion
     *
     * @return  self
     */
    public function setNumero($numero_transaccion)
    {
        $this->numero_transaccion = $numero_transaccion;

        return $this;
    }

    /**
     * Get the value of fecha_transaccion
     */
    public function getFecha_transaccion()
    {
        return $this->fecha_transaccion;
    }

    /**
     * Set the value of fecha_transaccion
     *
     * @return  self
     */
    public function setFecha_transaccion($fecha_transaccion)
    {
        $this->fecha_transaccion = $fecha_transaccion;

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
     * Get the value of tipo_transaccion
     */
    public function getTipo()
    {
        return $this->tipo_transaccion;
    }

    /**
     * Set the value of tipo_transaccion
     *
     * @return  self
     */
    public function setTipo($tipo_transaccion)
    {
        $this->tipo_transaccion = $tipo_transaccion;

        return $this;
    }



    /**
     * Get the value of fecha_vencimiento
     */
    public function getFecha_vencimiento()
    {
        return $this->fecha_vencimiento;
    }

    /**
     * Set the value of fecha_vencimiento
     *
     * @return  self
     */
    public function setFecha_vencimiento($fecha_vencimiento)
    {
        $this->fecha_vencimiento = $fecha_vencimiento;

        return $this;
    }

    /**
     * Get the value of bienes_transacciones
     */
    public function getBienesTransacciones()
    {
        return $this->bienesTransacciones;
    }

    public function addBienesTransacciones($bienesTransacciones)
    {
        $this->bienesTransacciones[] = $bienesTransacciones;
    }



    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
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
     * Get the value of responsable
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set the value of responsable
     *
     * @return  self
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get the value of estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of importe_total
     */
    public function getMonto()
    {
        return $this->importe_total;
    }

    /**
     * Set the value of importe_total
     *
     * @return  self
     */
    public function setMonto($importe_total)
    {
        $this->importe_total = $importe_total;

        return $this;
    }

    /**
     * Get the value of detalle
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set the value of detalle
     *
     * @return  self
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }


    /**
     * Get the value of bonificacionGeneral
     */
    public function getBonificacionGeneral()
    {
        if (!is_null($this->bonificacionGeneral)) {
            return $this->bonificacionGeneral;
        }
        return "0.00";
    }

    /**
     * Set the value of bonificacionGeneral
     *
     * @return  self
     */
    public function setBonificacionGeneral($bonificacionGeneral)
    {
        $this->bonificacionGeneral = $bonificacionGeneral;

        return $this;
    }

    /**
     * Get the value of bonificacionImporte
     */
    public function getBonificacionImporte()
    {
        return $this->bonificacionImporte;
    }

    /**
     * Set the value of bonificacionImporte
     *
     * @return  self
     */
    public function setBonificacionImporte($bonificacionImporte)
    {
        $this->bonificacionImporte = $bonificacionImporte;

        return $this;
    }

    /**
     * Get the value of ivaGeneral
     */
    public function getIvaGeneral()
    {
        return $this->ivaGeneral;
    }

    /**
     * Set the value of ivaGeneral
     *
     * @return  self
     */
    public function setIvaGeneral($ivaGeneral)
    {
        $this->ivaGeneral = $ivaGeneral;

        return $this;
    }

    /**
     * Get the value of formaPago
     */
    public function getFormaPago()
    {
        return $this->formaPago;
    }

    public function getNombreFormaPago()
    {
        if (is_null($this->formaPago)) {
            return "No definido";
        } else {
            return $this->formaPago->getNombre();
        }
    }

    /**
     * Set the value of formaPago
     *
     * @return  self
     */
    public function setFormaPago($formaPago)
    {
        $this->formaPago = $formaPago;

        return $this;
    }
    /**
     * Get the value of moneda
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set the value of moneda
     *
     * @return  self
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }


    /**
     * Get the value of recargoGeneral
     */
    public function getRecargoGeneral()
    {
        if (!is_null($this->recargoGeneral)) {
            return $this->recargoGeneral;
        } else return "0.00";
    }

    /**
     * Set the value of recargoGeneral
     *
     * @return  self
     */
    public function setRecargoGeneral($recargoGeneral)
    {

        $this->recargoGeneral = $recargoGeneral;

        return $this;
    }

    /**
     * Get the value of recargoImporte
     */
    public function getRecargoImporte()
    {
        return $this->recargoImporte;
    }

    /**
     * Set the value of recargoImporte
     *
     * @return  self
     */
    public function setRecargoImporte($recargoImporte)
    {
        $this->recargoImporte = $recargoImporte;

        return $this;
    }

    /**
     * Get the value of formaEnvio
     */
    public function getFormaEnvio()
    {
        return $this->formaEnvio;
    }

    /**
     * Set the value of formaEnvio
     *
     * @return  self
     */
    public function setFormaEnvio($formaEnvio)
    {
        $this->formaEnvio = $formaEnvio;

        return $this;
    }

    public function getNombreFormaEnvio()
    {
        if (is_null($this->formaEnvio)) {
            return "NO DEFINIDO";
        } else {
            return $this->formaEnvio->getNombre();
        }
    }

    /**
     * Get the value of transaccionPrevia
     */
    public function getTransaccionPrevia()
    {
        return $this->transaccionPrevia;
    }

    /**
     * Set the value of transaccionPrevia
     *
     * @return  self
     */
    public function setTransaccionPrevia($transaccionPrevia)
    {
        $this->transaccionPrevia = $transaccionPrevia;

        return $this;
    }

    /**
     * Get the value of numeroTransaccionTipo
     */
    public function getNumeroTransaccionTipo()
    {
        return $this->numeroTransaccionTipo;
    }

    /**
     * Set the value of numeroTransaccionTipo
     *
     * @return  self
     */
    public function setNumeroTransaccionTipo($numeroTransaccionTipo)
    {
        $this->numeroTransaccionTipo = $numeroTransaccionTipo;

        return $this;
    }

    /**
     * Get the value of subtotal
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set the value of subtotal
     *
     * @return  self
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get the value of facturado
     */
    public function getFacturado()
    {
        return $this->facturado;
    }

    /**
     * Set the value of facturado
     *
     * @return  self
     */
    public function setFacturado($facturado)
    {
        $this->facturado = $facturado;
        if (($facturado == true) && (strtoupper($this->tipo_transaccion) != "FACTURA")) {
            $this->estado = "FACTURADO";
        }
        return $this;
    }

    public function getDescripcion()
    {

        $descripcion = "";
        if (!is_null($this->nombre)) {
            $descripcion .= $this->nombre;
        }
        if (!is_null($this->bienesTransacciones)) {
            $descripcion .= " por " . COUNT($this->bienesTransacciones) . " items ";
        }
        if (!is_null($this->importe_total)) {
            $descripcion .= " por un monto de $ " . $this->importe_total;
        }
        if (!is_null($this->detalle)) {
            $descripcion .= " en concepto de: " . $this->detalle;
        }
        return $descripcion;
    }


    /**
     * Get the value of oficial
     */
    public function getOficial()
    {
        return $this->oficial;
    }

    /**
     * Set the value of oficial
     *
     * @return  self
     */
    public function setOficial($oficial)
    {
        $this->oficial = $oficial;

        return $this;
    }

    /**
     * Get the value of comprobante
     */
    public function getComprobante()
    {
        return $this->comprobante;
    }

    /**
     * Set the value of comprobante
     *
     * @return  self
     */
    public function setComprobante($comprobante)
    {
        $this->comprobante = $comprobante;

        return $this;
    }

    //================================================================================
    // JSON
    //================================================================================

    public function getJSON()
    {

        $output = "";
        $output .= '"Id": "' . $this->getId() . '", ';
        $output .= '"Numero": "' . $this->getNumero() . '", ';
        $output .= '"Detalle": "' . $this->getDetalle() . '", ';
        if (!(is_null($this->fecha_transaccion))) {
            $output .= '"Fecha Transaccion": "' . $this->getFecha_transaccion()->format('d/m/Y') . '", ';
        }
        if (!(is_null($this->fecha_vencimiento))) {
            $output .= '"Fecha Vencimiento": "' . $this->getFecha_vencimiento()->format('d/m/Y') . '", ';
        }
        $output .= '"Persona": "' . $this->getPersona()->getId() . '", ';
        $output .= '"Responsable": "' . $this->getResponsable()->getId() . '", ';
        $output .= '"Tipo Transaccion": "' . $this->getTipo() . '", ';
        if (!(is_null($this->ivaGeneral))) {
            $output .= '"IVA General": ' . $this->getIvaGeneral()->getJSON() . ', ';
        }
        if (!(is_null($this->formaPago))) {
            $output .= '"Forma de Pago": ' . $this->getFormaPago()->getJSON() . ', ';
        }
        if (!(is_null($this->formaEnvio))) {
            $output .= '"Forma de Envio": ' . $this->getFormaEnvio()->getJSON() . ', ';
        }
        $output .= '"Recargo general": ' . $this->getRecargoGeneral() . ', ';
        $output .= '"Bonificacion general": ' . $this->getBonificacionGeneral() . ', ';
        if (!(is_null($this->moneda))) {
            $output .= '"Moneda": ' . $this->getMoneda()->getJSON() . ', ';
        }
        $output .= '"Estado": "' . $this->getEstado() . '", ';
        $output .= '"Monto": "' . $this->getMonto() . '", ';
        $output .= '"Oficial": "' . $this->getOficial() . '", ';
        if (!(is_null($this->transaccionPrevia))) {
            $output .= '"Transaccion Previa": ' . $this->getTransaccionPrevia()->getId() . ', ';
        }
        if (!(is_null($this->numeroTransaccionTipo))) {
            $output .= '"Numero Tipo Transaccion": ' . $this->getNumeroTransaccionTipo() . ', ';
        }
        if (!(is_null($this->comprobante))) {
            $output .= '"Comprobante": ' . $this->getComprobante()->getJSON() . ', ';
        }
        $output .= '"Importe Total": ' . $this->getMonto() . ', ';
        $output .= '"Importe Bonificacion": ' . $this->getBonificacionImporte() . ', ';
        $output .= '"Importe Recargo": ' . $this->getRecargoImporte() . ', ';
        $output .= '"Subtotal": "' . $this->getSubtotal() . '" ';



        return  '{' . $output . '}';
    }
}
