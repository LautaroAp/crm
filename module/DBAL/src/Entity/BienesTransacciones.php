<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BienesTransacciones
 *
 * This class represents a registered servicio.
 * @ORM\Entity()
 * @ORM\Table(name="BIENES_TRANSACCIONES")
 */
class BienesTransacciones
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
     * Many BienesTransacciones have One Bien.
     * @ORM\ManyToOne(targetEntity="Bienes")
     * @ORM\JoinColumn(name="ID_BIEN", referencedColumnName="ID")
     */

    private $bien;

    /**
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION", referencedColumnName="ID")
     */
    private $transaccion;

    /**
     * @ORM\Column(name="CANTIDAD", nullable=true, type="integer")
     */
    protected $cantidad;

    /**
     * @ORM\Column(name="BONIFICACION", nullable=true, type="decimal")
     */
    protected $descuento;

    /**
     * @ORM\ManyToOne(targetEntity="Iva" , cascade={"persist"})
     * @ORM\JoinColumn(name="IVA", referencedColumnName="ID")
     */
    protected $iva;

    /**
     * @ORM\Column(name="SUBTOTAL", nullable=true, type="decimal")
     */
    protected $subtotal;


    /**
     * @ORM\Column(name="ESTADO_ENTREGA", nullable=true, type="string")
     */
    protected $estadoEntrega;

    /**
     * @ORM\Column(name="ESTADO_FACTURA", nullable=true, type="string")
     */
    protected $estadoFactura;

    /**
     * @ORM\Column(name="PRECIO_ORIGINAL", nullable=true, type="decimal")
     */

    protected $precioOriginal;

    /**
     * @ORM\Column(name="DETALLE", nullable=true, type="string")
     */
    protected $detalle;

    /**
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION_PREVIA", referencedColumnName="ID")
     */
    private $transaccionPrevia;

    /**
     * @ORM\Column(name="IMPORTE_TOTAL", nullable=true, type="decimal")
     */
    protected $importeTotal;


    /**
     * @ORM\Column(name="IMPORTE_IVA", nullable=true, type="decimal")
     */
    protected $importeIva;


    /**
     * @ORM\Column(name="IMPORTE_BONIFICACION", nullable=true, type="decimal")
     */
    protected $importeBonificacion;

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
     * Get the value of bien
     */
    public function getBien()
    {
        return $this->bien;
    }

    /**
     * Set the value of bien
     *
     * @return  self
     */
    public function setBien($bien)
    {
        $this->bien = $bien;

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
     * Get the value of cantidad
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get the value of bonificacion
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set the value of bonificacion
     *
     * @return  self
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get the value of iva
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set the value of iva
     *
     * @return  self
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

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

    public function getIvaPeso()
    {
        if (!is_null($this->iva)) {
            $salida = $this->getIva()->getValor() * $this->getPrecioDto() / 100;
            $salida = $salida * $this->getCantidad();
            return $salida;
        }
        return "";
    }

    public function getPrecioDto()
    {
        if ($this->getBien()) {
            if (!is_null($this->getDescuento())) {
                $salida = $this->getDescuento() * $this->getBien()->getPrecio() / 100;
                return $this->getBien()->getPrecio() - $salida;
            }
            return $this->getBien()->getPrecio();
        } else {
            return null;
        }
    }

    /**
     * Get the value of estadoEntrega
     */
    public function getestadoEntrega()
    {
        return $this->estadoEntrega;
    }

    /**
     * Set the value of estadoEntrega
     *
     * @return  self
     */
    public function setestadoEntrega($estadoEntrega)
    {
        $this->estadoEntrega = $estadoEntrega;

        return $this;
    }

    /**
     * Get the value of estadoFactura
     */
    public function getEstadoFactura()
    {
        return $this->estadoFactura;
    }

    /**
     * Set the value of estadoFactura
     *
     * @return  self
     */
    public function setEstadoFactura($estadoFactura)
    {
        $this->estadoFactura = $estadoFactura;

        return $this;
    }

    /**
     * Get the value of precioOriginal
     */
    public function getPrecioOriginal()
    {
        return $this->precioOriginal;
    }

    /**
     * Set the value of precioOriginal
     *
     * @return  self
     */
    public function setPrecioOriginal($precioOriginal)
    {
        $this->precioOriginal = $precioOriginal;

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
     * Get the value of importeTotal
     */
    public function getImporteTotal()
    {
        return $this->importeTotal;
    }

    /**
     * Set the value of importeTotal
     *
     * @return  self
     */
    public function setImporteTotal($importeTotal)
    {
        $this->importeTotal = $importeTotal;

        return $this;
    }

    /**
     * Get the value of importeIva
     */
    public function getImporteIva()
    {
        return $this->importeIva;
    }

    /**
     * Set the value of importeIva
     *
     * @return  self
     */
    public function setImporteIva($importeIva)
    {
        $this->importeIva = $importeIva;

        return $this;
    }

    /**
     * Get the value of importeBonificacion
     */
    public function getImporteBonificacion()
    {
        return $this->importeBonificacion;
    }

    /**
     * Set the value of importeBonificacion
     *
     * @return  self
     */
    public function setImporteBonificacion($importeBonificacion)
    {
        $this->importeBonificacion = $importeBonificacion;

        return $this;
    }

    //================================================================================
    // JSON
    //================================================================================

    public function getJSON()
    {
        $output = "";
        $output .= '"Id": "' . $this->getId() . '", ';
        if (!is_null($this->getBien())) {
            $output .= '"Bien": ' . $this->getBien()->getJSON() . ', ';
        }
        if (!is_null($this->getIva())) {
            $output .= '"IVA": ' . $this->getIva()->getJSON() . ', ';
        } else {
            $output .= '"IVA": "' . "" . '", ';
        }

        $output .= '"ImpIVA": "' . $this->getIvaPeso() . '", ';
        $output .= '"Cantidad": "' . $this->getCantidad() . '", ';
        $output .= '"Dto": "' . $this->getDescuento() . '", ';
        $output .= '"ImpDto": "' . $this->getPrecioDto() . '", ';
        $output .= '"Precio Original": "' . $this->getPrecioOriginal() . '", ';
        if (!is_null($this->getTransaccion())) {
            $output .= '"Numero Transaccion": "' . $this->getTransaccion()->getId() . '", ';
        }
        if (!is_null($this->getestadoEntrega())) {
            $output .= '"Estado Entrega": "' . $this->getestadoEntrega() . '", ';
        }
        if (!is_null($this->getSubtotal())) {
            $output .= '"Subtotal": "' . $this->getSubtotal() . '", ';
        }
        if (!is_null($this->getImporteBonificacion())) {
            $output .= '"Importe Bonificacion": "' . $this->getImporteBonificacion() . '", ';
        }
        if (!is_null($this->getImporteIva())) {
            $output .= '"Importe Iva": "' . $this->getImporteIva() . '", ';
        }
        if (!is_null($this->getEstadoFactura())) {
            $output .= '"Estado Factura": "' . $this->getEstadoFactura() . '", ';
        }
        if (!is_null($this->detalle)) {
            $output .= '"Detalle": "' . $this->getDetalle() . '", ';
        }
        $output .= '"Totales": "' . $this->getImporteTotal() . '" ';

        return  '{' . $output . '}';
    }
}
