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
     * @ORM\Column(name="TIPO", nullable=true, type="string", length=255)
     */
    protected $tipo_trasaccion;

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
     * @ORM\Column(name="MONTO", nullable=true, type="decimal")
     */
    protected $monto;


    /**
     * @ORM\Column(name="BONIFICACION_GENERAL", nullable=true, type="decimal")
     */
    protected $bonificacionGeneral;


    /**
     * @ORM\ManyToOne(targetEntity="Iva")
     * @ORM\JoinColumn(name="IVA_GENERAL", referencedColumnName="ID")
     */
    protected $ivaGeneral;

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
     * Get the value of tipo_trasaccion
     */ 
    public function getTipo()
    {
        return $this->tipo_trasaccion;
    }

    /**
     * Set the value of tipo_trasaccion
     *
     * @return  self
     */ 
    public function setTipo($tipo_trasaccion)
    {
        $this->tipo_trasaccion = $tipo_trasaccion;

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
        return $this->bienes_transacciones;
    }

    public function addBienesTransacciones($bienesTransacciones) {
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
     * Get the value of monto
     */ 
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set the value of monto
     *
     * @return  self
     */ 
    public function setMonto($monto)
    {
        $this->monto = $monto;

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

    public function getDescripcion(){
        $descripcion = "";
        if (!is_null($this->nombre)){
            $descripcion .= $this->nombre;
        }
        if (!is_null($this->bienesTransacciones)){
            $descripcion.= " por ". COUNT($this->bienesTransacciones) ." items ";
        }
        if (!is_null($this->monto)){
            $descripcion.= " por un monto de $ ". $this->monto;
        }
        if (!is_null($this->detalle)){
            $descripcion.= " en concepto de: ".$this->detalle;
        }
        return $descripcion;
    }

    /**
     * Get the value of bonificacionGeneral
     */ 
    public function getBonificacionGeneral()
    {
        return $this->bonificacionGeneral;
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
}
