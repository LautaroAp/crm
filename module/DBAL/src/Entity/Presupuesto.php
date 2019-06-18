<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Presupuesto
 *
 * This class represents a registered presupuesto.
 * @ORM\Entity()
 * @ORM\Table(name="PRESUPUESTO")
 */
class Presupuesto
{

    //================================================================================
    // Properties
    //================================================================================

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_PRESUPUESTO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="NUMERO", nullable=true, type="string", length=255)
     */
    protected $numero;

    /**
     * Many Presupuestos have One Transaccion.
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION", referencedColumnName="ID")
     */
    private $transaccion;

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
     * Get many Presupuestos have One Transaccion.
     */
    public function getTransaccion()
    {
        return $this->transaccion;
    }

    /**
     * Set many Presupuestos have One Transaccion.
     *
     * @return  self
     */
    public function setTransaccion($transaccion)
    {
        $this->transaccion = $transaccion;

        return $this;
    }
}
