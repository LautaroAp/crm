<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Empresa
 *
 * This class represents a registered empresa.
 * @ORM\Entity()
 * @ORM\Table(name="EMPRESA")
 */
class Empresa {
    //put your code here
    
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_EMPRESA", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_empresa;
    
     /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string", length=255)
     */
    protected $nombre;
    
    /**
     * @ORM\Column(name="DIRECCION", nullable=true, type="string", length=255)
     */
    protected $direccion;
    
    /**
     * @ORM\Column(name="TELEFONO", nullable=true, type="string", length=255)
     */
    protected $telefono;
    
    /**
     * @ORM\Column(name="MAIL", nullable=true, type="string", length=255)
     */
    protected $mail;
    
    /**
     * @ORM\Column(name="MOVIL", nullable=true, type="string", length=255)
     */
    protected $movil;
    
    /**
     * @ORM\Column(name="FAX", nullable=true, type="string", length=255)
     */
    protected $fax;
    
    /**
     * @ORM\Column(name="WEB", nullable=true, type="string", length=255)
     */
    protected $web;

    /**
     * @ORM\Column(name="CUIT_CUIL", nullable=true, type="string", length=255)
     */
    protected $cuit_cuil;

    /**
     * @ORM\Column(name="INGRESOS_BRUTOS", nullable=true, type="string", length=255)
     */
    protected $ingresos_brutos;

    /**
     * @ORM\Column(name="VENCIMIENTO_CAI", nullable=true, type="datetime")
     */
    protected $vencimiento_cai;

    /**
     * @ORM\Column(name="RAZON_SOCIAL", nullable=true, type="string", length=255)
     */
    protected $razon_social;

    /**
     * Many Personas have One Categoria.
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CONDICION_IVA", referencedColumnName="ID")
     */
    private $condicion_iva;

    /**
     * @ORM\Column(name="LOCALIDAD", nullable=true, type="string", length=255)
     */
    protected $localidad;/**
     * @ORM\Column(name="PROVINCIA", nullable=true, type="string", length=255)
     */
    protected $provincia;
    
    /**
     * @ORM\Column(name="PAIS", nullable=true, type="string", length=255)
     */
    protected $pais;
    /**
     * @ORM\Column(name="CP", nullable=true, type="string", length=255)
     */
    protected $CP;
    
    /**
     * @ORM\Column(name="PARAMETRO_VENCIMIENTO", nullable=true, type="integer")
     */
    protected $parametro_vencimiento;

    /**
     * @ORM\Column(name="PARAMETRO_ELEMENTOS_PAGINA", nullable=true, type="integer")
     */
    protected $parametro_elementos_pagina;

    /**
     * @ORM\Column(name="PUNTO_VENTA", nullable=true, type="integer")
     */
    protected $punto_venta;

    /**
     * Many Empresas have One Moneda.
     * @ORM\ManyToOne(targetEntity="Moneda")
     * @ORM\JoinColumn(name="MONEDA", referencedColumnName="ID")
     */
    private $moneda;
    
    function getNombre() {
        return $this->nombre;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getMail() {
        return $this->mail;
    }

    function getMovil() {
        return $this->movil;
    }

    function getFax() {
        return $this->fax;
    }

    function getWeb() {
        return $this->web;
    }

    function getCuit_cuil() {
        return $this->cuit_cuil;
    }

    function getVencimiento_cai() {
        return $this->vencimiento_cai;
    }

    function getRazon_social() {
        return $this->razon_social;
    }

    function getTipo_iva() {
        return $this->tipo_iva;
    }

    function getLocalidad() {
        return $this->localidad;
    }

    function getProvincia() {
        return $this->provincia;
    }

    function getPais() {
        return $this->pais;
    }

    function getCP() {
        return $this->CP;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setMovil($movil) {
        $this->movil = $movil;
    }

    function setFax($fax) {
        $this->fax = $fax;
    }

    function setWeb($web) {
        $this->web = $web;
    }

    function setCuit_cuil($cuit_cuil) {
        $this->cuit_cuil = $cuit_cuil;
    }

    function setVencimiento_cai($vencimiento_cai) {
        $this->vencimiento_cai = $vencimiento_cai;
    }

    function setRazon_social($razon_social) {
        $this->razon_social = $razon_social;
    }

    function setTipo_iva($tipo_iva) {
        $this->tipo_iva = $tipo_iva;
    }

    function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    function setPais($pais) {
        $this->pais = $pais;
    }

    function setCP($CP) {
        $this->CP = $CP;
    }


    function getId() {
        return $this->id_empresa;
    }

    function setId($id_empresa) {
        $this->id_empresa = $id_empresa;
    }
    
    function getId_empresa() {
        return $this->id_empresa;
    }

    function getParametro_vencimiento() {
        return $this->parametro_vencimiento;
    }

    function setId_empresa($id_empresa) {
        $this->id_empresa = $id_empresa;
    }

    function setParametro_vencimiento($parametro_vencimiento) {
        $this->parametro_vencimiento = $parametro_vencimiento;
    }

    /**
     * Get the value of parametro_elementos_pagina
     */ 
    public function getParametro_elementos_pagina() {
        return $this->parametro_elementos_pagina;
    }

    /**
     * Set the value of parametro_elementos_pagina
     *
     * @return self
     */ 
    public function setParametro_elementos_pagina($parametro_elementos_pagina) {
        $this->parametro_elementos_pagina = $parametro_elementos_pagina;
        return $this;
    }



    /**
     * Get many Empresas have One Moneda.
     */ 
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set many Empresas have One Moneda.
     *
     * @return  self
     */ 
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    public function getNombreMoneda(){
        if(is_null($this->moneda)){
            return null;
        }
        return $this->moneda->getNombre();
    }

    /**
     * Get many Personas have One Categoria.
     */ 
    public function getCondicion_iva()
    {
        return $this->condicion_iva;
    }

    /**
     * Set many Personas have One Categoria.
     *
     * @return  self
     */ 
    public function setCondicion_iva($condicion_iva)
    {
        $this->condicion_iva = $condicion_iva;

        return $this;
    }

    public function getNombreCondicionIva() {
        if (is_null($this->condicion_iva)) {
            return null;
        } else {
            return $this->condicion_iva->getNombre();
        }
    }

    /**
     * Get the value of punto_venta
     */ 
    public function getPunto_venta()
    {
        return $this->punto_venta;
    }

    /**
     * Set the value of punto_venta
     *
     * @return  self
     */ 
    public function setPunto_venta($punto_venta)
    {
        $this->punto_venta = $punto_venta;

        return $this;
    }

    /**
     * Get the value of ingresos_brutos
     */ 
    public function getIngresos_brutos()
    {
        return $this->ingresos_brutos;
    }

    /**
     * Set the value of ingresos_brutos
     *
     * @return  self
     */ 
    public function setIngresos_brutos($ingresos_brutos)
    {
        $this->ingresos_brutos = $ingresos_brutos;

        return $this;
    }
}
