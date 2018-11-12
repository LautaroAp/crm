<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Producto
 *
 * This class represents a registered licencia.
 * @ORM\Entity()
 * @ORM\Table(name="PRODUCTO")
 */
class Producto {
    
     /**
     * @ORM\Id
     * @ORM\Column(name="ID_PRODUCTO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_producto;   
           /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumn(name="ID_EVENTO", referencedColumnName="ID_EVENTO")
     */ 
    protected $evento;   
    
    /**
     * @ORM\Column(name="CANTIDAD", nullable=true, type="integer")
     */
    protected $cantidad;

     /**
     * @ORM\Column(name="PRECIO", nullable=true, type="float")
     */
    protected $precio;
    
        /**
     * @ORM\Column(name="PRECIO_TOTAL", nullable=true, type="float")
     */
    protected $precio_total;
    
        /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string", length=255)
     */
    protected $nombre;
    
        /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string", length=255)
     */
    protected $descripcion;
    
        /**
     * @ORM\Column(name="IVA", nullable=true, type="float")
     */
    protected $iva;
    
        /**
     * @ORM\Column(name="DESCUENTO", nullable=true, type="float")
     */
    protected $descuento;
    
        /**
     * @ORM\Column(name="TIPO_PROD", nullable=true, type="integer")
     */
    protected $tipo_prod;
    
        /**
     * @ORM\Column(name="USUARIOS_ADICIONALES", nullable=true, type="integer")
     */
    protected $usuarios_adicionales;
    
        /**
     * @ORM\Column(name="VERSION_TERMINAL", nullable=true, type="string", length=255)
     */
    protected $version_terminal;
        /**
     * @ORM\Column(name="TERMINALES", nullable=true, type="integer")
     */
    protected $terminales;
    
         /**
     * @ORM\Column(name="TIPO_LICENCIA", nullable=true, type="string", length=255)
     */
    protected $tipo_licencia;
    
         /**
     * @ORM\Column(name="VERSION", nullable=true, type="string", length=255)
     */
    protected $version;
    
         /**
     * @ORM\Column(name="MONEDA", nullable=true, type="string", length=1)
     */
    protected $moneda;
    
    
    function getId() {
        return $this->id_producto;
    }

    function setId($id_producto) {
        $this->id_producto = $id_producto;
    }

        
    function getId_Evento() {
        if ($this->evento != NULL){
            return $this->evento->getId();
        }
        else{
            return "No definido";
        }
    }
    
    function getCliente() {
         if ($this->evento != NULL){
            return $this->evento->getNombreCliente();
        }
        else{
            return "No definido";
        }
       
    }
    
    
     function getFecha() {
         if ($this->evento != NULL){
            return $this->evento->getFecha();
        }
        else{
            return "No definido";
        } 
    }
 
    function getCantidad() {
        return $this->cantidad;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getPrecio_total() {
        return $this->precio_total;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getIva() {
        return $this->iva;
    }

    function getDescuento() {
        return $this->descuento;
    }

    function getTipo_prod() {
        return $this->tipo_prod;
    }

    function getUsuarios_adicionales() {
        return $this->usuarios_adicionales;
    }

    function getVersion_terminal() {
        return $this->version_terminal;
    }

    function getTerminales() {
        return $this->terminales;
    }

    function getTipo_licencia() {
        return $this->tipo_licencia;
    }

    function getVersion() {
        return $this->version;
    }

    function getMoneda() {
        return $this->moneda;
    }

    function setId_evento($id_evento) {
        $this->id_evento = $id_evento;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setPrecio($precio) {
        $this->precio = $precio;
    }

    function setPrecio_total($precio_total) {
        $this->precio_total = $precio_total;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setIva($iva) {
        $this->iva = $iva;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    function setTipo_prod($tipo_prod) {
        $this->tipo_prod = $tipo_prod;
    }

    function setUsuarios_adicionales($usuarios_adicionales) {
        $this->usuarios_adicionales = $usuarios_adicionales;
    }

    function setVersion_terminal($version_terminal) {
        $this->version_terminal = $version_terminal;
    }

    function setTerminales($terminales) {
        $this->terminales = $terminales;
    }

    function setTipo_licencia($tipo_licencia) {
        $this->tipo_licencia = $tipo_licencia;
    }

    function setVersion($version) {
        $this->version = $version;
    }

    function setMoneda($moneda) {
        $this->moneda = $moneda;
    }




}
