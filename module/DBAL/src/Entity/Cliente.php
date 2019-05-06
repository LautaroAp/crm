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
     * Many Clientes have One Pais.
     * @ORM\ManyToOne(targetEntity="Pais")
     * @ORM\JoinColumn(name="ID_PAIS_CLIENTE", referencedColumnName="ID_PAIS")
     */
    private $pais;

    /**
     * Many Clientes have One Provincia.
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
     * Many Clientes have One Profesion.
     * @ORM\ManyToOne(targetEntity="Profesion")
     * @ORM\JoinColumn(name="ID_PROFESION_CLIENTE", referencedColumnName="ID")
     */
    private $profesion;

    /**
     * * @ORM\Column(name="CARGO_CLIENTE", nullable=true, type="string", length=255)
     */
    private $cargo;

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
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CATEGORIA_CLIENTE", referencedColumnName="ID")
     */
    private $categoria;

    // /**
    //  * Many Clientes have One Categoria.
    //  * @ORM\ManyToOne(targetEntity="Licencia")
    //  * @ORM\JoinColumn(name="ID_LICENCIA", referencedColumnName="ID_LICENCIA")
    //  */
    // private $licencia;

    /**
     * Many Clientes have One Categoria.
     * @ORM\ManyToOne(targetEntity="Servicio")
     * @ORM\JoinColumn(name="ID_SERVICIO", referencedColumnName="ID_SERVICIO")
     */
    private $servicio;

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

    // /**
    //  * @ORM\Column(name="LICENCIA_ACTUAL", nullable=true, type="string", length=255)
    //  */
    // private $licencia_actual;

    /**
     * @ORM\Column(name="DNI_CLIENTE", nullable=true, type="string")
     */
    private $dni;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\Usuario", mappedBy="id_cliente")
     */
    private $usuarios;

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
        return ucwords(strtolower($this->ciudad));
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
        return $this;
    }

    public function getProfesion() {
        return  $this->profesion;
    }

    public function setProfesion($profesion) {
        $this->profesion = $profesion;
        return $this;
    }

    public function getNombreProfesion() {
        $profesion = $this->getProfesion();
        if (is_null($profesion)) {
            return null;
        } else {
            return  ucwords(strtolower($this->getProfesion()->getNombre()));
        }
    }

    public function getEmpresa() {
        return  ucwords(strtolower($this->empresa));
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
        return $this;
    }

    public function getActividad() {
        return  ucwords(strtolower($this->actividad));
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
        return  strtolower($this->skype);
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
            return  ucwords(strtolower($categoria->getNombre()));
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
     * Get the value of cargo
     */ 
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set the value of cargo
     *
     * @return  self
     */ 
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get many Clientes have One Categoria.
     */ 
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set many Clientes have One Categoria.
     *
     * @return  self
     */ 
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    public function getNombreServicioCliente(){
        if (is_null($this->servicio)) {
            return null;
        } else {
            return  ucwords(strtolower($this->servicio->getBien()->getNombre()));
        }
    }
    
}
