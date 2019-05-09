<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Cobro
 *
 * This class represents a registered cobro.
 * @ORM\Entity()
 * @ORM\Table(name="COBRO")
 */
class Cobro {

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_COBRO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_cobro;
    
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
     * @ORM\JoinColumn(name="ID_TRANSACCION_COBRO", referencedColumnName="ID")
     */
    private $transaccion_cobro;

   

    /**
     * Get the value of id_cobro
     */ 
    public function getId_cobro()
    {
        return $this->id_cobro;
    }

    /**
     * Set the value of id_cobro
     *
     * @return  self
     */ 
    public function setId_cobro($id_cobro)
    {
        $this->id_cobro = $id_cobro;

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
    public function getTransaccion_cobro()
    {
        return $this->transaccion_cobro;
    }

    /**
     * Set many Services have One Transaccion.
     *
     * @return  self
     */ 
    public function setTransaccion_cobro($transaccion_cobro)
    {
        $this->transaccion_cobro = $transaccion_cobro;

        return $this;
    }
}