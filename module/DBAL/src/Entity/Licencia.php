<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Licencia
 *
 * This class represents a registered licencia.
 * @ORM\Entity()
 * @ORM\Table(name="LICENCIA")
 */
class Licencia {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_LICENCIA", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */ 
    protected $id_licencia;   
    
    /**
     * @ORM\Column(name="VERSION_LICENCIA", nullable=true, type="decimal")
     */
    protected $version_licencia;

     /**
     * @ORM\Column(name="NOMBRE_LICENCIA", nullable=true, type="string", length=255)
     */
    protected $nombre_licencia;
    
      /**
     * @ORM\Column(name="PRECIO_LOCAL", type="float")
     */
    protected $precio_local;
    
      /**
     * @ORM\Column(name="PRECIO_EXTRANJERO",  type="float")
     */
    protected $precio_extranjero;
      /**
     * @ORM\Column(name="IVA", type="float")
     */
    protected $iva;
      /**
     * @ORM\Column(name="DESCUENTO",  type="float")
     */
    protected $descuento;
    
    
    

    function getId() {
        return $this->id_licencia;
    }

    function getVersion() {
        return $this->version_licencia;
    }

    function getNombre() {
        return $this->nombre_licencia;
    }

    function setId($id_licencia) {
        $this->id_licencia = $id_licencia;
    }

    function setVersion($version_licencia) {
        $this->version_licencia = $version_licencia;
    }

    function setNombre($nombre_licencia) {
        $this->nombre_licencia = $nombre_licencia;
    }

     
    function getPrecio_local() {
        return $this->precio_local;
    }

    function getPrecio_extranjero() {
        return $this->precio_extranjero;
    }

    function getIva() {
        return $this->iva;
    }

    function getDescuento() {
        return $this->descuento;
    }

    function setPrecio_local($precio_local) {
        $this->precio_local = $precio_local;
    }

    function setPrecio_extranjero($precio_extranjero) {
        $this->precio_extranjero = $precio_extranjero;
    }

    function setIva($iva) {
        $this->iva = $iva;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }


    function getTotal_local(){
        $total =0.0;
        $total =$this->precio_local - ($this->precio_local * $this->descuento/100);
        $total = $total + ($total*$this->iva/100);
        return $total;
        
    }
    
    function getTotal_extranjero(){
        $total =0.0;
        $total =$this->precio_extranjero - ($this->precio_extranjero * $this->descuento/100);
        return $total;
        
    }

}
