<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Empresa
 *
 * This class represents a registered empresa.
 * @ORM\Entity()
 * @ORM\Table(name="DATOS_EMPRESA")
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
     * @ORM\Column(name="VENCIMIENTO_CAI", nullable=true, type="string", length=255)
     */
    protected $vencimiento_cai;/**
     * @ORM\Column(name="RAZON_SOCIAL", nullable=true, type="string", length=255)
     */
    protected $razon_social;
    /**
     * @ORM\Column(name="TIPO_IVA", nullable=true, type="string", length=255)
     */
    protected $tipo_iva;
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


}
