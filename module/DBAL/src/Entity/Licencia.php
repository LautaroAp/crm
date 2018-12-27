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
class Licencia
{
    //put your code here

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_LICENCIA", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * @ORM\Column(name="CODIGO_LICENCIA", nullable=true, type="string")
     */
    protected $codigo_licencia;

    /**
     * @ORM\Column(name="CODIGO_BARRAS", nullable=true, type="string")
     */
    protected $codigo_barras;

     /**
     * Many Licencias have One Categoria.
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CATEGORIA", referencedColumnName="ID")
     */

    protected $categoria;

    /**
     * @ORM\Column(name="ID_PROVEEDOR", nullable=true, type="integer")
     */
    protected $proveedor;

    /**
     * @ORM\Column(name="PRECIO", type="decimal")
     */
    protected $precio;

    /**
     * @ORM\Column(name="PRECIO_FINAL_IVA",  type="decimal")
     */
    protected $precio_final_iva;

    /**
     * @ORM\Column(name="PRECIO_FINAL_DTO",  type="decimal")
     */
    protected $precio_final_dto;

    /**
     * @ORM\Column(name="PRECIO_FINAL_IVA_DTO",  type="decimal")
     */
    protected $precio_final_iva_dto;

    /**
     * Many Products have One Product.
     * @ORM\ManyToOne(targetEntity="Iva")
     * @ORM\JoinColumn(name="ID_IVA", referencedColumnName="ID")
     */
    protected $iva;

    /**
     * @ORM\Column(name="IVA_GRAVADO", type="decimal")
     */
    protected $iva_gravado;

    /**
     * @ORM\Column(name="DESCUENTO",  type="decimal")
     */
    protected $descuento;

    /**
     * @ORM\Column(name="ID_MONEDA", type="integer")
     */
    protected $moneda;



    /**
     * Get the value of id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Get the value of codigo_licencia
     */
    public function getCodigo_licencia() {
        return $this->codigo_licencia;
    }

    /**
     * Set the value of codigo_licencia
     *
     * @return  self
     */
    public function setCodigo_licencia($codigo_licencia) {
        $this->codigo_licencia = $codigo_licencia;
        return $this;
    }

    /**
     * Get the value of codigo_barras
     */
    public function getCodigo_barras() {
        return $this->codigo_barras;
    }

    /**
     * Set the value of codigo_barras
     *
     * @return  self
     */
    public function setCodigo_barras($codigo_barras) {
        $this->codigo_barras = $codigo_barras;
        return $this;
    }

    /**
     * Get the value of categoria
     */
    public function getCategoria() {
        return $this->categoria;
    }

    public function getNombreCategoria(){
        if (is_null($this->categoria)){
            return "No definido";
        }
        return $this->categoria->getNombre();
    }

    /**
     * Set the value of categoria
     *
     * @return  self
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
        return $this;
    }

    /**
     * Get the value of proveedor
     */
    public function getProveedor() {
        return $this->proveedor;
    }

    /**
     * Set the value of proveedor
     *
     * @return  self
     */
    public function setProveedor($proveedor) {
        $this->proveedor = $proveedor;
        return $this;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */
    public function setPrecio($precio) {
        $this->precio = $precio;
        return $this;
    }

    /**
     * Get the value of precio_final_iva
     */
    public function getPrecio_final_iva() {
        return $this->precio_final_iva;
    }

    /**
     * Set the value of precio_final_iva
     *
     * @return  self
     */
    public function setPrecio_final_iva($precio_final_iva) {
        $this->precio_final_iva = $precio_final_iva;
        return $this;
    }

    /**
     * Get the value of precio_final_iva_dto
     */
    public function getPrecio_final_iva_dto() {
        return $this->precio_final_iva_dto;
    }

    /**
     * Set the value of precio_final_iva_dto
     *
     * @return  self
     */
    public function setPrecio_final_iva_dto($precio_final_iva_dto) {
        $this->precio_final_iva_dto = $precio_final_iva_dto;
        return $this;
    }

    /**
     * Get many Products have One Product.
     */
    public function getIva() {
        return $this->iva;
    }

    /**
     * Set many Products have One Product.
     *
     * @return  self
     */
    public function setIva($iva) {
        $this->iva = $iva;
        return $this;
    }

    /**
     * Get the value of iva_gravado
     */
    public function getIva_gravado() {
        return $this->iva_gravado;
    }

    /**
     * Set the value of iva_gravado
     *
     * @return  self
     */
    public function setIva_gravado($iva_gravado) {
        $this->iva_gravado = $iva_gravado;
        return $this;
    }

    /**
     * Get the value of descuento
     */
    public function getDescuento() {
        return $this->descuento;
    }

    /**
     * Set the value of descuento
     *
     * @return  self
     */
    public function setDescuento($descuento) {
        $this->descuento = $descuento;
        return $this;
    }

    /**
     * Get the value of moneda
     */ 
    public function getMoneda() {
        return $this->moneda;
    }

    /**
     * Set the value of moneda
     *
     * @return  self
     */ 
    public function setMoneda($moneda) {
        $this->moneda = $moneda;
        return $this;
    }

    /**
     * Get the value of precio_final_dto
     */ 
    public function getPrecio_final_dto() {
        return $this->precio_final_dto;
    }

    /**
     * Set the value of precio_final_dto
     *
     * @return  self
     */ 
    public function setPrecio_final_dto($precio_final_dto) {
        $this->precio_final_dto = $precio_final_dto;
        return $this;
    }
}
