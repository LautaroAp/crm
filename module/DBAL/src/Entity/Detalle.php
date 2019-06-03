<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Detalle
 *
 * This class represents a registered categoriaProducto.
 * @ORM\Entity()
 * @ORM\Table(name="DETALLE")
 */
class Detalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

   
     /**
     * Many Detalles have One Transaccion.
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION", referencedColumnName="ID")
     */

    private $transaccion;
       
    /**
     * @ORM\Column(name="NRO_TIPO_TRANSACCION", nullable=true, type="integer")
     */
    protected $nroTipoTransaccion;

    /**
     * @ORM\Column(name="DETALLE", nullable=true, type="string")
     */
    protected $detalle;

    /**
     * @ORM\Column(name="MONTO", nullable=true, type="decimal")
     */
    protected $monto;

    /**
     * Get the value of id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return self
     */
    public function setId($id) {
        $this->id = $id;
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
     * Get the value of nroTipoTransaccion
     */ 
    public function getNroTipoTransaccion()
    {
        return $this->nroTipoTransaccion;
    }

    /**
     * Set the value of nroTipoTransaccion
     *
     * @return  self
     */ 
    public function setNroTipoTransaccion($nroTipoTransaccion)
    {
        $this->nroTipoTransaccion = $nroTipoTransaccion;

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

    public function getJSON(){
        $output = "";
        $output .= '"Id": "' . $this->getId() .'", ';
        if (!is_null($this->getTransaccion())){
            $output .= '"Transaccion": ' . $this->getTransaccion()->getJSON() .', ';
        }
       
        $output .= '"Nro Tipo Transaccion": "' . $this->getNroTipoTransaccion() .'", ';
        $output .= '"Detalle": "' . $this->getDetalle() .'", ';
        $output .= '"Monto": "' . $this->getMonto() .'" ';
    
        return  '{'.$output.'}' ;
    }
}
