<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of NotaCredito
 *
 * This class represents a registered notaCredito.
 * @ORM\Entity()
 * @ORM\Table(name="NOTA_CREDITO")
 */
class NotaCredito {

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_NOTA_CREDITO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="NUMERO", nullable=true, type="integer", length=255)
     */
    protected $numero;
    
    /**
     * Many Services have One Transaccion.
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION", referencedColumnName="ID")
     */
    private $transaccion;

    /**
     * Many Services have One Transaccion.
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION_NOTA_CREDITO", referencedColumnName="ID")
     */
    private $transaccion_notaCredito;

    /**
     * Many Services have One Transaccion.
     * @ORM\ManyToOne(targetEntity="TipoFactura")
     * @ORM\JoinColumn(name="ID_TIPO_NOTA_CREDITO", referencedColumnName="ID")
     */
    private $tipo_factura;
   

    /**
     * Get the value of id_cobro
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
     * Get many Services have One Transaccion.
     */ 
    public function getTransaccion()
    {
        return $this->transaccion;
    }

    /**
     * Set many Services have One Transaccion.
     *
     * @return  self
     */ 
    public function setTransaccion($transaccion)
    {
        $this->transaccion = $transaccion;

        return $this;
    }

    /**
     * Get many Services have One Transaccion.
     */ 
    public function getTransaccion_notaCredito()
    {
        return $this->transaccion_notaCredito;
    }

    /**
     * Set many Services have One Transaccion.
     *
     * @return  self
     */ 
    public function setTransaccion_notaCredito($transaccion_notaCredito)
    {
        $this->transaccion_notaCredito = $transaccion_notaCredito;

        return $this;
    }

    /**
     * Get many Services have One Transaccion.
     */ 
    public function getTipo_factura()
    {
        return $this->tipo_factura;
    }

    /**
     * Set many Services have One Transaccion.
     *
     * @return  self
     */ 
    public function setTipo_factura($tipo_factura)
    {
        $this->tipo_factura = $tipo_factura;

        return $this;
    }
}