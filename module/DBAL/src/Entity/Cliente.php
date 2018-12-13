<?php

namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * This class represents a client.
 * @ORM\Entity()
 * @ORM\Table(name="CLIENTE")
 */
class Cliente {

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_CLIENTE", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $Id;


    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Pais")
     * @ORM\JoinColumn(name="ID_PAIS_CLIENTE", referencedColumnName="ID_PAIS")
     */
    private $pais;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumn(name="ID_PROVINCIA_CLIENTE", referencedColumnName="ID_PROVINCIA")
     */
    private $provincia;

    /**
     * @ORM\Column(name="CIUDAD_CLIENTE", nullable=true, type="string", length=255)
     */
    private $ciudad;

   
    /**
     * @ORM\Column(name="CUENTA_SKYPE_CLIENTE", nullable=true, type="string", length=255)
     */
    private $skype;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="ProfesionCliente")
     * @ORM\JoinColumn(name="ID_PROFESION_CLIENTE", referencedColumnName="ID_TIPO_CLIENTE")
     */
    private $profesion;

    /**
     * @ORM\Column(name="EMPRESA_CLIENTE", nullable=true, type="string", length=255)
     */
    private $empresa;

    /**
     * @ORM\Column(name="ACTIVIDAD_CLIENTE", nullable=true, type="string", length=255)
     */
    private $actividad;

    /**
     * @ORM\Column(name="ANIMALES_CLIENTE", nullable=true, type="string", length=255)
     */
    private $animales;

    /**
     * @ORM\Column(name="ESTABLE_CLIENTE", nullable=true, type="string", length=255)
     */
    private $establecimientos;

    /**
     * @ORM\Column(name="RAZA_CLIENTE", nullable=true, type="string", length=255)
     */
    private $raza_manejo;

    /**
     * Many Clientes have One Categoria.
     * @ORM\ManyToOne(targetEntity="CategoriaCliente")
     * @ORM\JoinColumn(name="ID_CATEGORIA_CLIENTE", referencedColumnName="ID_CATEGORIA_CLIENTE")
     */
    private $categoria;

    /**
     * Many Clientes have One Categoria.
     * @ORM\ManyToOne(targetEntity="Licencia")
     * @ORM\JoinColumn(name="ID_LICENCIA", referencedColumnName="ID_LICENCIA")
     */
    private $licencia;

    /**
     * @ORM\Column(name="VERSION_LICENCIA", nullable=true, type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(name="FECHA_COMPRA", type="datetime")
     */
    private $fecha_compra;

    /**
     * @ORM\Column(name="FECHA_VTO_ACT", type="datetime")
     */
    private $vencimiento;

    /**
     * @ORM\Column(name="FECHA_ULTIMO_CONTACTO", type="datetime")
     */
    private $fecha_ultimo_contacto;

    /**
     * @ORM\Column(name="FECHA_ULTIMO_PAGO", type="datetime")
     */
    private $fecha_ultimo_pago;

    /**
     * @ORM\Column(name="LICENCIA_ACTUAL", nullable=true, type="string", length=255)
     */
    private $licencia_actual;

    /**
     * @ORM\Column(name="DNI_CLIENTE", nullable=true, type="string")
     */
    private $dni;

    /**
     * @ORM\Column(name="CUIT_CUIL_CLIENTE", nullable=true, type="string")
     */
    private $cuit_cuil;

    /**
     * @ORM\Column(name="RAZON_SOCIAL_CLIENTE", nullable=true, type="string")
     */
    private $razon_social;

    /**
     * @ORM\Column(name="ID_CONDICION_IVA_CLIENTE", nullable=true, type="string")
     */
    private $condicion_iva;

    /**
     * @ORM\Column(name="DIRECCION_FACTURACION", nullable=true, type="string")
     */
    private $direccion_facturacion;

    /**
     * @ORM\Column(name="BANCO", nullable=true, type="string")
     */
    private $banco;

    /**
     * @ORM\Column(name="CBU", nullable=true, type="string")
     */
    private $cbu;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\Usuario", mappedBy="id_cliente")
     */
    private $usuarios;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\Evento", mappedBy="cliente")
     * @ORM\OrderBy({"fecha" = "desc"})
     */
    private $eventos;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_PERSONA", referencedColumnName="ID")
     */
    private $persona;

    public function getId() {
        return $this->Id;
    }

    
    public function getPais() {
        return $this->pais;
    }

    public function setPais($pais) {
        $this->pais = $pais;
        return $this;
    }

    public function getNombrePaisCliente() {
        if (is_null($this->provincia)) {
            if (!is_null($this->pais)) {
                return $this->pais->getNombre();
            } else {
                return null;
            }
        } else {
            if (is_null($this->provincia->getPais())) {
                return null;
            } else {
                return $this->provincia->getPais()->getNombre();
            }
        }
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function setProvincia($provincia) {
        $this->provincia = $provincia;
        return $this;
    }

    public function getNombreProvinciaCliente() {
        $provincia = $this->getProvincia();
        if (is_null($provincia)) {
            return null;
        } else {
            return $this->getProvincia()->getNombre_provincia();
        }
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
        return $this;
    }

    public function getProfesion() {
        return $this->profesion;
    }

    public function setProfesion($profesion) {
        $this->profesion = $profesion;
        return $this;
    }

    public function getNombreProfesionCliente() {
        $profesion = $this->getProfesion();
        if (is_null($profesion)) {
            return null;
        } else {
            return $this->getProfesion()->getNombre();
        }
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
        return $this;
    }

    public function getActividad() {
        return $this->actividad;
    }

    public function setActividad($actividad) {
        $this->actividad = $actividad;
        return $this;
    }

    public function getAnimales() {
        return $this->animales;
    }

    public function setAnimales($animales) {
        $this->animales = $animales;
        return $this;
    }

    public function getEstablecimientos() {
        return $this->establecimientos;
    }

    public function setEstablecimientos($establecimientos) {
        $this->establecimientos = $establecimientos;
        return $this;
    }

    public function getRazaManejo() {
        return $this->raza_manejo;
    }

    public function setRazaManejo($raza_manejo) {
        $this->raza_manejo = $raza_manejo;
        return $this;
    }

   
    public function getSkype() {
        return $this->skype;
    }

    public function setSkype($skype) {
        $this->skype = $skype;
        return $this;
    }

   
    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
        return $this;
    }

    public function getNombreCategoriaCliente() {
        $categoria = $this->getCategoria();
        if (is_null($categoria)) {
            return null;
        } else {
            return $categoria->getNombre();
        }
    }

    public function getFechaCompra() {
        return $this->fecha_compra;
    }

    public function setFechaCompra($fecha_compra) {
        $this->fecha_compra = $fecha_compra;
        return $this;
    }

    public function getVencimiento() {
        return $this->vencimiento;
    }

    public function setVencimiento($vencimiento) {
        $this->vencimiento = $vencimiento;
        return $this;
    }

    function getFechaUltimoContacto() {
        return $this->fecha_ultimo_contacto;
    }

    function setFechaUltimoContacto($fecha_ultimo_contacto) {
        $this->fecha_ultimo_contacto = $fecha_ultimo_contacto;
        return $this;
    }

    function getFechaUltimoPago() {
        return $this->fecha_ultimo_pago;
    }

    function setFechaUltimoPago($fecha_ultimo_pago) {
        $this->fecha_ultimo_pago = $fecha_ultimo_pago;
        return $this;
    }

    function getNombreLicenciaCliente() {
        if (is_null($this->licencia)) {
            return null;
        } else {
            return $this->licencia->getNombre();
        }
    }

    function getLicencia() {
        return $this->licencia;
    }

    function setLicencia($licencia) {
        $this->licencia = $licencia;
        return $this;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

   
    public function addUsuario($usuario) {
        $this->usuarios[] = $usuario;
    }

    public function getUsuarios() {
        return $this->usuarios;
    }

    public function getEventos() {
        return $this->eventos;
    }

    public function isPrimeraVenta() {
        $array_eventos = $this->getEventos();
        if (is_null($this->fecha_compra)) {
            return true;
        }
        foreach ($array_eventos as $eve) {
            if ($eve->getTipoId() == 11) {
                return false;
                break;
            }
        }
        return true;
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
     * Get the value of dni
     */ 
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set the value of dni
     *
     * @return  self
     */ 
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get the value of cuit_cuil
     */ 
    public function getCuit_cuil()
    {
        return $this->cuit_cuil;
    }

    /**
     * Set the value of cuit_cuil
     *
     * @return  self
     */ 
    public function setCuit_cuil($cuit_cuil)
    {
        $this->cuit_cuil = $cuit_cuil;

        return $this;
    }

    /**
     * Get the value of razon_social
     */ 
    public function getRazon_social()
    {
        return $this->razon_social;
    }

    /**
     * Set the value of razon_social
     *
     * @return  self
     */ 
    public function setRazon_social($razon_social)
    {
        $this->razon_social = $razon_social;

        return $this;
    }

    /**
     * Get the value of condicion_iva
     */ 
    public function getCondicion_iva()
    {
        return $this->condicion_iva;
    }

    /**
     * Set the value of condicion_iva
     *
     * @return  self
     */ 
    public function setCondicion_iva($condicion_iva)
    {
        $this->condicion_iva = $condicion_iva;

        return $this;
    }

    /**
     * Get the value of direccion_facturacion
     */ 
    public function getDireccion_facturacion()
    {
        return $this->direccion_facturacion;
    }

    /**
     * Set the value of direccion_facturacion
     *
     * @return  self
     */ 
    public function setDireccion_facturacion($direccion_facturacion)
    {
        $this->direccion_facturacion = $direccion_facturacion;

        return $this;
    }

    /**
     * Get the value of banco
     */ 
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set the value of banco
     *
     * @return  self
     */ 
    public function setBanco($banco)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get the value of cbu
     */ 
    public function getCbu()
    {
        return $this->cbu;
    }

    /**
     * Set the value of cbu
     *
     * @return  self
     */ 
    public function setCbu($cbu)
    {
        $this->cbu = $cbu;

        return $this;
    }
}
