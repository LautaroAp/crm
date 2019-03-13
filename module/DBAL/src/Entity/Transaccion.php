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
class Transaccion {
    //put your code here
    
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
     * @ORM\Column(name="FECHA", type="datetime")
     */
    protected $fecha_transaccion;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_PERSONA", referencedColumnName="ID")
     */
    protected $persona;

    /**
     * @ORM\Column(name="TIPO", nullable=true, type="string", length=255)
     */
    protected $tipo_trasaccion;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\BienesTransacciones", mappedBy="bien")
     */
    private $transacciones;


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
    public function getNumero_transaccion()
    {
        return $this->numero_transaccion;
    }

    /**
     * Set the value of numero_transaccion
     *
     * @return  self
     */ 
    public function setNumero_transaccion($numero_transaccion)
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
     * Get the value of tipo_trasaccion
     */ 
    public function getTipo_trasaccion()
    {
        return $this->tipo_trasaccion;
    }

    /**
     * Set the value of tipo_trasaccion
     *
     * @return  self
     */ 
    public function setTipo_trasaccion($tipo_trasaccion)
    {
        $this->tipo_trasaccion = $tipo_trasaccion;

        return $this;
    }

    /**
     * Get the value of transacciones
     */ 
    public function getTransacciones()
    {
        return $this->transacciones;
    }

    /**
     * Set the value of transacciones
     *
     * @return  self
     */ 
    public function setTransacciones($transacciones)
    {
        $this->transacciones = $transacciones;

        return $this;
    }
}
