<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Pedido
 *
 * This class represents a registered pedido.
 * @ORM\Entity()
 * @ORM\Table(name="PEDIDO")
 */
class Pedido {

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_PEDIDO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_pedido;
    
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
     * @ORM\ManyToOne(targetEntity="Moneda")
     * @ORM\JoinColumn(name="ID_MONEDA", referencedColumnName="ID")
     */
    private $moneda;

    /**
     * @ORM\Column(name="FECHA_ENTREGA", nullable=true, type="date")
     */
    protected $fecha_entrega;

    /**
     * @ORM\Column(name="FORMA_ENVIO", nullable=true, type="string")
     */
    protected $forma_envio;

    /**
     * @ORM\Column(name="LUGAR_ENTREGA", nullable=true, type="string")
     */
    protected $lugar_entrega;
    
    /**
     * @ORM\Column(name="ID_FACTURA", nullable=true, type="int", length=255)
     */
    protected $factura;

    /**
     * @ORM\Column(name="INGRESOS_BRUTOS", nullable=true, type="decimal")
     */
    protected $ingresos_brutos;


    /**
     * Get the value of id_pedido
     */ 
    public function getId_pedido()
    {
        return $this->id_pedido;
    }

    /**
     * Set the value of id_pedido
     *
     * @return  self
     */ 
    public function setId_pedido($id_pedido)
    {
        $this->id_pedido = $id_pedido;

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
     * Get the value of fecha_entrega
     */ 
    public function getFecha_entrega()
    {
        return $this->fecha_entrega;
    }

    /**
     * Set the value of fecha_entrega
     *
     * @return  self
     */ 
    public function setFecha_entrega($fecha_entrega)
    {
        $this->fecha_entrega = $fecha_entrega;

        return $this;
    }


    /**
     * Get the value of lugar_entrega
     */ 
    public function getLugar_entrega()
    {
        return $this->lugar_entrega;
    }

    /**
     * Set the value of lugar_entrega
     *
     * @return  self
     */ 
    public function setLugar_entrega($lugar_entrega)
    {
        $this->lugar_entrega = $lugar_entrega;

        return $this;
    }

    /**
     * Get the value of factura
     */ 
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set the value of factura
     *
     * @return  self
     */ 
    public function setFactura($factura)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get the value of ingresos_brutos
     */ 
    public function getIngresos_brutos()
    {
        return $this->ingresos_brutos;
    }

    /**
     * Set the value of ingresos_brutos
     *
     * @return  self
     */ 
    public function setIngresos_brutos($ingresos_brutos)
    {
        $this->ingresos_brutos = $ingresos_brutos;

        return $this;
    }

    /**
     * Get the value of forma_envio
     */ 
    public function getForma_envio()
    {
        return $this->forma_envio;
    }

    /**
     * Set the value of forma_envio
     *
     * @return  self
     */ 
    public function setForma_envio($forma_envio)
    {
        $this->forma_envio = $forma_envio;

        return $this;
    }
}