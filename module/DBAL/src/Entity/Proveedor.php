<?php

namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * This class represents a proveedor.
 * @ORM\Entity()
 * @ORM\Table(name="PROVEEDOR")
 */
class Proveedor {

    /**
     * @ORM\Id
     * @ORM\Column(name="ID_PROVEEDOR", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $Id;


    /**
     * Many Proveedors have One Pais.
     * @ORM\ManyToOne(targetEntity="Pais")
     * @ORM\JoinColumn(name="ID_PAIS", referencedColumnName="ID_PAIS")
     */
    private $pais;

    /**
     * Many Proveedors have One Provincia.
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumn(name="ID_PROVINCIA", referencedColumnName="ID_PROVINCIA")
     */
    private $provincia;

    /**
     * @ORM\Column(name="CIUDAD", nullable=true, type="string", length=255)
     */
    private $ciudad;

    /**
     * @ORM\Column(name="EMPRESA", nullable=true, type="string", length=255)
     */
    private $empresa;

    /**
     * @ORM\Column(name="ACTIVIDAD", nullable=true, type="string", length=255)
     */
    private $actividad;
    /**
     * @ORM\Column(name="CARGO", nullable=true, type="string", length=255)
     */
    private $cargo;


    /**
     * Many Proveedors have One Categoria.
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CATEGORIA", referencedColumnName="ID")
     */
    private $categoria;

    /**
     * @ORM\Column(name="FECHA_ULTIMO_CONTACTO", type="datetime")
     */
    private $fecha_ultimo_contacto;

    /**
     * @ORM\Column(name="FECHA_ULTIMO_PAGO", type="datetime")
     */
    private $fecha_ultimo_pago;

       /**
     * @ORM\Column(name="FECHA_COMPRA", type="datetime")
     */
    private $fecha_compra;

    /**
     * @ORM\Column(name="DNI", nullable=true, type="string")
     */
    private $dni;
   
    /**
     * @ORM\Column(name="SKYPE", nullable=true, type="string")
     */
    private $skype;

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

    public function getNombrePaisProveedor() {
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

    public function getNombreProvinciaProveedor() {
        $provincia = $this->getProvincia();
        if (is_null($provincia)) {
            return null;
        } else {
            return $this->getProvincia()->getNombre_provincia();
        }
    }

    public function getCiudad() {
        return (($this->ciudad));
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
        return $this;
    }

    public function getEmpresa() {
        return (($this->empresa));
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
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
   
    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
        return $this;
    }

    public function getNombreCategoriaProveedor() {
        $categoria = $this->getCategoria();
        if (is_null($categoria)) {
            return null;
        } else {
            return (($categoria->getNombre()));
        }
    }

    public function getFechaCompra() {
        return $this->fecha_compra;
    }

    public function setFechaCompra($fecha_compra) {
        $this->fecha_compra = $fecha_compra;
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

       
    public function addUsuario($usuario) {
        $this->usuarios[] = $usuario;
    }

    public function getUsuarios() {
        return $this->usuarios;
    }

    public function getEventos() {
        return null;
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
     * Get the value of actividad
     */ 
    public function getActividad()
    {
        return (($this->actividad));
    }

    /**
     * Set the value of actividad
     *
     * @return  self
     */ 
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get the value of cargo
     */ 
    public function getCargo()
    {
        return (($this->cargo));
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
     * Get the value of skype
     */ 
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set the value of skype
     *
     * @return  self
     */ 
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }
}
