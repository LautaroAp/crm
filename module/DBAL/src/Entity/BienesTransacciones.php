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
class BienesTransacciones {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bienes")
     * @ORM\JoinColumn(name="BIEN", referencedColumnName="ID")
     */
    private $bien;

    /**
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="Transaccion", referencedColumnName="ID")
     */
    private $transaccion;

    /**
     * @ORM\Column(name="CANTIDAD", nullable=true, type="integer")
     */
    protected $cantidad;

    /**
     * @ORM\Column(name="BONIFICACION", nullable=true, type="decimal")
     */
    protected $bonificacion;

    /**
     * @ORM\Column(name="IVA", nullable=true, type="decimal")
     */
    protected $iva;

    /**
     * @ORM\Column(name="SUBTOTAL", nullable=true, type="decimal")
     */
    protected $subtotal;


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
}
