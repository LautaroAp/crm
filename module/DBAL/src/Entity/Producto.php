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
class Producto
{

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_PRODUCTO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_producto;

    /**
     * @ORM\Column(name="ID_EVENTO", nullable=true, type="integer")
     */
    protected $evento;

    /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * Many Services have One Type.
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CATEGORIA", referencedColumnName="ID")
     */
    protected $categoria;

    /**
     * Many Proveedor have One PRoveedor.
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumn(name="ID_PROVEEDOR", referencedColumnName="ID_PROVEEDOR")
     */

    protected $proveedor;

    /**
     * @ORM\Column(name="PRESENTACION", nullable=true, type="string")
     */
    protected $presentacion;

    // /**
    //  * @ORM\Column(name="STOCK", nullable=true, type="integer")
    //  */
    // protected $stock;

    /**
     * @ORM\Column(name="REPOSICION", nullable=true, type="integer")
     */
    protected $reposicion;

    // /**
    //  * @ORM\Column(name="CODIGO_PRODUCTO", nullable=true, type="string")
    //  */
    // protected $codigo_producto;

    // /**
    //  * @ORM\Column(name="CODIGO_BARRAS", nullable=true, type="string")
    //  */
    // protected $codigo_barras;

    /**
     * @ORM\Column(name="PRECIO_COMPRA", nullable=true, type="decimal")
     */
    protected $precio_compra;

    /**
     * @ORM\Column(name="COSTOS_DIRECTOS", nullable=true, type="decimal")
     */
    protected $costos_directos;

    /**
     * @ORM\Column(name="GASTOS_DIRECTOS", nullable=true, type="decimal")
     */
    protected $gastos_directos;

    /**
     * @ORM\Column(name="PRECIO_COMPRA_TOTAL", nullable=true, type="decimal")
     */
    protected $precio_compra_total;

    /**
     * @ORM\Column(name="CONTRIBUCION_MARGINAL_VALOR", nullable=true, type="decimal")
     */
    protected $contribucion_marginal_valor;

    /**
     * @ORM\Column(name="CONTRIBUCION_MARGINAL_PORCENTUAL", nullable=true, type="decimal")
     */
    protected $contribucion_marginal_porcentual;

    /**
     * @ORM\Column(name="PRECIO_VENTA", nullable=true, type="decimal")
     */
    protected $precio_venta;

    /**
     * @ORM\Column(name="PRECIO_VENTA_DTO", nullable=true, type="decimal")
     */
    protected $precio_venta_dto;

    /**
     * @ORM\Column(name="DESCUENTO", nullable=true, type="decimal")
     */
    protected $descuento;

     /**
     * Many Products have One Product.
     * @ORM\ManyToOne(targetEntity="Iva")
     * @ORM\JoinColumn(name="ID_IVA", referencedColumnName="ID")
     */
    protected $iva;

    /**
     * @ORM\Column(name="IVA_GRAVADO", nullable=true, type="decimal")
     */
    protected $iva_gravado;

    /**
     * @ORM\Column(name="PRECIO_FINAL_IVA", nullable=true, type="decimal")
     */
    protected $precio_final_iva;

    /**
     * @ORM\Column(name="PRECIO_FINAL_IVA_DTO", nullable=true, type="decimal")
     */
    protected $precio_final_iva_dto;

    /**
     * @ORM\Column(name="ID_MONEDA", type="integer")
     */
    protected $moneda;

    /**
     * @ORM\ManyToOne(targetEntity="Bienes")
     * @ORM\JoinColumn(name="ID_BIEN", referencedColumnName="ID")
     */
    private $bien;

    public function getId(){
        return $this->id_producto;
    }

    /**
     * Get the value of id_evento
     */
    public function getEvento(){
        return $this->id_evento;
    }

    /**
     * Set the value of id_evento
     *
     * @return self
     */
    public function setEvento($evento){
        $this->evento = $evento;
        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(){
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return self
     */
    public function setNombre($nombre){
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion(){
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return self
     */
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Get the value of categoria
     */
    public function getCategoria(){
        return $this->categoria;
    }

    /**
     * Set the value of categoria
     *
     * @return self
     */
    public function setCategoria($categoria){
        $this->categoria = $categoria;
        return $this;
    }

    /**
     * Get the value of proveedor
     */
    public function getProveedor(){
        return $this->proveedor;
    }

    /**
     * Set the value of proveddor
     *
     * @return self
     */
    public function setProveedor($proveedor){
        $this->proveedor = $proveedor;
        return $this;
    }


    /**
     * Get the value of presentacion
     */
    public function getPresentacion(){
        return $this->presentacion;
    }

    /**
     * Set the value of presentacion
     *
     * @return self
     */
    public function setPresentacion($presentacion){
        $this->presentacion = $presentacion;
        return $this;
    }

    // /**
    //  * Get the value of stock
    //  */
    // public function getStock(){
    //     return $this->stock;
    // }

    // /**
    //  * Set the value of stock
    //  *
    //  * @return self
    //  */
    // public function setStock($stock){
    //     $this->stock = $stock;
    //     return $this;
    // }

    /**
     * Get the value of reposicion
     */
    public function getReposicion(){
        return $this->reposicion;
    }

    /**
     * Set the value of reposicion
     *
     * @return self
     */
    public function setReposicion($reposicion){
        $this->reposicion = $reposicion;
        return $this;
    }

    // /**
    //  * Get the value of codigo_producto
    //  */
    // public function getCodigo_producto(){
    //     return $this->codigo_producto;
    // }

    // /**
    //  * Set the value of codigo_producto
    //  *
    //  * @return self
    //  */
    // public function setCodigo_producto($codigo_producto){
    //     $this->codigo_producto = $codigo_producto;
    //     return $this;
    // }

    // /**
    //  * Get the value of codigo_barras
    //  */
    // public function getCodigo_barras(){
    //     return $this->codigo_barras;
    // }

    // /**
    //  * Set the value of codigo_barras
    //  *
    //  * @return self
    //  */
    // public function setCodigo_barras($codigo_barras){
    //     $this->codigo_barras = $codigo_barras;
    //     return $this;
    // }

    /**
     * Get the value of precio_compra
     */
    public function getPrecio_compra(){
        return $this->precio_compra;
    }

    /**
     * Set the value of precio_compra
     *
     * @return self
     */
    public function setPrecio_compra($precio_compra){
        $this->precio_compra = $precio_compra;
        return $this;
    }

    /**
     * Get the value of costos_directos
     */
    public function getCostos_directos() {
        return $this->costos_directos;
    }

    /**
     * Set the value of costos_directos
     *
     * @return self
     */
    public function setCostos_directos($costos_directos) {
        $this->costos_directos = $costos_directos;
        return $this;
    }

    /**
     * Get the value of gastos_directos
     */
    public function getGastos_directos() {
        return $this->gastos_directos;
    }

    /**
     * Set the value of gastos_directos
     *
     * @return self
     */
    public function setGastos_directos($gastos_directos) {
        $this->gastos_directos = $gastos_directos;
        return $this;
    }

    /**
     * Get the value of precio_compra_total
     */
    public function getPrecio_compra_total() {
        return $this->precio_compra_total;
    }

    /**
     * Set the value of precio_compra_total
     *
     * @return self
     */
    public function setPrecio_compra_total($precio_compra_total) {
        $this->precio_compra_total = $precio_compra_total;
        return $this;
    }

    /**
     * Get the value of contribucion_marginal_valor
     */
    public function getContribucion_marginal_valor() {
        return $this->contribucion_marginal_valor;
    }

    /**
     * Set the value of contribucion_marginal_valor
     *
     * @return self
     */
    public function setContribucion_marginal_valor($contribucion_marginal_valor) {
        $this->contribucion_marginal_valor = $contribucion_marginal_valor;
        return $this;
    }

    /**
     * Get the value of contribucion_marginal_porcentual
     */
    public function getContribucion_marginal_porcentual() {
        return $this->contribucion_marginal_porcentual;
    }

    /**
     * Set the value of contribucion_marginal_porcentual
     *
     * @return self
     */
    public function setContribucion_marginal_porcentual($contribucion_marginal_porcentual) {
        $this->contribucion_marginal_porcentual = $contribucion_marginal_porcentual;
        return $this;
    }

    /**
     * Get the value of precio_venta
     */ 
    public function getPrecio_venta() {
        return $this->precio_venta;
    }

    /**
     * Set the value of precio_venta
     *
     * @return self
     */
    public function setPrecio_venta($precio_venta){
        $this->precio_venta = $precio_venta;
        return $this;
    }

    /**
     * Get the value of precio_venta_dto
     */ 
    public function getPrecio_venta_dto() {
        return $this->precio_venta_dto;
    }

    /**
     * Set the value of precio_venta_dto
     *
     * @return self
     */ 
    public function setPrecio_venta_dto($precio_venta_dto) {
        $this->precio_venta_dto = $precio_venta_dto;
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
     * Get the value of iva
     */ 
    public function getIva() {
        return $this->iva;
    }

    public function getValorIva(){
        if (is_null($this->iva)){
            return null;
        }
        return $this->iva->getValor();
    }
    /**
     * Set the value of iva
     *
     * @return self
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
     * @return self
     */ 
    public function setIva_gravado($iva_gravado) {
        $this->iva_gravado = $iva_gravado;
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
     * @return self
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
     * @return self
     */ 
    public function setPrecio_final_iva_dto($precio_final_iva_dto) {
        $this->precio_final_iva_dto = $precio_final_iva_dto;
        return $this;
    }

    /**
     * Get the value of moneda
     */ 
    public function getMoneda() {
        return $this->moneda;
    }

    /**
     * Set the value of id_moneda
     *
     * @return self
     */ 
    public function setMoneda($moneda) {
        $this->moneda = $moneda;
        return $this;
    }

    public function getCategoriaNombre(){
        if (is_null($this->categoria)){
            return null;
        }
        else{
            return $this->categoria->getNombre();
        }

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
}
