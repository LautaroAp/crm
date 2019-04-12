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
     * Many BienesTransacciones have One Bien.
     * @ORM\ManyToOne(targetEntity="Bienes")
     * @ORM\JoinColumn(name="ID_BIEN", referencedColumnName="ID")
     */
  
    private $bien;

    /**
     * @ORM\ManyToOne(targetEntity="Transaccion")
     * @ORM\JoinColumn(name="ID_TRANSACCION", referencedColumnName="ID")
     */
    private $transaccion;

    /**
     * @ORM\Column(name="CANTIDAD", nullable=true, type="integer")
     */
    protected $cantidad;

    /**
     * @ORM\Column(name="BONIFICACION", nullable=true, type="decimal")
     */
    protected $descuento;

    /**
     * @ORM\ManyToOne(targetEntity="Iva" , cascade={"persist"})
     * @ORM\JoinColumn(name="IVA", referencedColumnName="ID")
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
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set the value of bonificacion
     *
     * @return  self
     */ 
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

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

    public function getIvaPeso(){
        if (!is_null($this->iva)){
            $salida = $this->getIva()->getValor() * $this->getPrecioDto() / 100;
            $salida = $salida * $this->getCantidad();
            return $salida;
        }
        return "";
    }

    public function getPrecioDto(){
        if (!is_null($this->getDescuento())){
            $salida = $this->getDescuento() * $this->getBien()->getPrecio() /100;
            return $this->getBien()->getPrecio() - $salida;
        }
        return $this->getBien()->getPrecio();
    }
    
    public function getJSON(){
        $output = "";
        $output .= '"Id": "' . $this->getId() .'", ';
        if (!is_null($this->getBien())){
            $output .= '"Bien": ' . $this->getBien()->getJSON() .', ';
        }
        if (!is_null($this->getIva())){
            $output .= '"IVA": ' . $this->getIva()->getJSON() .', ';
        }        
        $output .= '"Iva $": "' . $this->getIvaPeso() .'", ';
        $output .= '"Cantidad": "' . $this->getCantidad() .'", ';
        $output .= '"Descuento": "' . $this->getDescuento() .'", ';
        $output .= '"Dto. $": "' . $this->getPrecioDto() .'", ';

        $output .= '"Subtotal": "' . $this->getSubtotal() .'" ';
        
        return  '{'.$output.'}' ;
    }

    public function toArray(){
        $salida = array();
        if(!is_null($this->id)){
            $salida['Id']= $this->id;
        }
        else{
            $salida['Id'] = null;
        }
        if (!is_null($this->transaccion)){
            $salida['Transaccion'] = $this->transaccion->getId();
        }
        else{
            $salida['Transaccion']= null;
        }
        $salida['Bien'] = $this->bien->getId();
        $salida['Cantidad'] = $this->cantidad;
        $salida['Descuento'] = $this->descuento;
        $salida['IVA'] = $this->iva->getId();
        $salida['Subtotal'] = $this->subtotal;
        return $salida;
    }

}
