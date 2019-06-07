<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Factura
 *
 * This class represents a registered factura.
 * @ORM\Entity()
 * @ORM\Table(name="FACTURA")
 */
class Factura {

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_FACTURA", type="integer")
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
     * @ORM\JoinColumn(name="ID_TRANSACCION_FACTURA", referencedColumnName="ID")
     */
    private $transaccion_factura;

    /**
     * Many Services have One Transaccion.
     * @ORM\ManyToOne(targetEntity="TipoComprobante")
     * @ORM\JoinColumn(name="ID_TIPO_COMPROBANTE", referencedColumnName="ID")
     */
    private $tipo_comprobante;
   

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
    public function getTransaccion_factura()
    {
        return $this->transaccion_factura;
    }

    /**
     * Set many Services have One Transaccion.
     *
     * @return  self
     */ 
    public function setTransaccion_factura($transaccion_factura)
    {
        $this->transaccion_factura = $transaccion_factura;

        return $this;
    }

    /**
     * Get many Services have One Transaccion.
     */ 
    public function getTipo_comprobante()
    {
        return $this->tipo_comprobante;
    }

    /**
     * Set many Services have One Transaccion.
     *
     * @return  self
     */ 
    public function setTipo_comprobante($tipo_comprobante)
    {
        $this->tipo_comprobante = $tipo_comprobante;

        return $this;
    }
}