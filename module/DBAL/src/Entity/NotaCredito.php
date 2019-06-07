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
     * @ORM\Column(name="CONCEPTO", nullable=true, type="string")
     */
    protected $concepto;

    
    /**
     * @ORM\Column(name="IMPORTE_LETRAS", nullable=true, type="string")
     */
    protected $importe_letras;

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

    /**
     * Get the value of concepto
     */ 
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set the value of concepto
     *
     * @return  self
     */ 
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get the value of importe_letras
     */ 
    public function getImporte_letras()
    {
        return $this->importe_letras;
    }

    /**
     * Set the value of importe_letras
     *
     * @return  self
     */ 
    public function setImporte_letras($importe_letras)
    {
        $this->importe_letras = $importe_letras;

        return $this;
    }

}