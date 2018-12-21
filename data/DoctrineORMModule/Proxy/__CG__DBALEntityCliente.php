<?php

namespace DoctrineORMModule\Proxy\__CG__\DBAL\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Cliente extends \DBAL\Entity\Cliente implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'Id', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'pais', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'provincia', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'ciudad', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'skype', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'profesion', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'cargo', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'empresa', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'actividad', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'animales', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'establecimientos', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'raza_manejo', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'categoria', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'licencia', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'version', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'fecha_compra', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'vencimiento', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'fecha_ultimo_contacto', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'fecha_ultimo_pago', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'licencia_actual', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'dni', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'cuit_cuil', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'razon_social', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'condicion_iva', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'direccion_facturacion', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'banco', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'cbu', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'usuarios', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'eventos', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'persona'];
        }

        return ['__isInitialized__', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'Id', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'pais', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'provincia', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'ciudad', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'skype', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'profesion', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'cargo', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'empresa', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'actividad', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'animales', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'establecimientos', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'raza_manejo', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'categoria', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'licencia', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'version', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'fecha_compra', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'vencimiento', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'fecha_ultimo_contacto', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'fecha_ultimo_pago', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'licencia_actual', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'dni', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'cuit_cuil', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'razon_social', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'condicion_iva', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'direccion_facturacion', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'banco', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'cbu', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'usuarios', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'eventos', '' . "\0" . 'DBAL\\Entity\\Cliente' . "\0" . 'persona'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Cliente $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getPais()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPais', []);

        return parent::getPais();
    }

    /**
     * {@inheritDoc}
     */
    public function setPais($pais)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPais', [$pais]);

        return parent::setPais($pais);
    }

    /**
     * {@inheritDoc}
     */
    public function getNombrePaisCliente()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNombrePaisCliente', []);

        return parent::getNombrePaisCliente();
    }

    /**
     * {@inheritDoc}
     */
    public function getProvincia()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProvincia', []);

        return parent::getProvincia();
    }

    /**
     * {@inheritDoc}
     */
    public function setProvincia($provincia)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setProvincia', [$provincia]);

        return parent::setProvincia($provincia);
    }

    /**
     * {@inheritDoc}
     */
    public function getNombreProvinciaCliente()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNombreProvinciaCliente', []);

        return parent::getNombreProvinciaCliente();
    }

    /**
     * {@inheritDoc}
     */
    public function getCiudad()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCiudad', []);

        return parent::getCiudad();
    }

    /**
     * {@inheritDoc}
     */
    public function setCiudad($ciudad)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCiudad', [$ciudad]);

        return parent::setCiudad($ciudad);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfesion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProfesion', []);

        return parent::getProfesion();
    }

    /**
     * {@inheritDoc}
     */
    public function setProfesion($profesion)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setProfesion', [$profesion]);

        return parent::setProfesion($profesion);
    }

    /**
     * {@inheritDoc}
     */
    public function getNombreProfesionCliente()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNombreProfesionCliente', []);

        return parent::getNombreProfesionCliente();
    }

    /**
     * {@inheritDoc}
     */
    public function getEmpresa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmpresa', []);

        return parent::getEmpresa();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmpresa($empresa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmpresa', [$empresa]);

        return parent::setEmpresa($empresa);
    }

    /**
     * {@inheritDoc}
     */
    public function getActividad()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getActividad', []);

        return parent::getActividad();
    }

    /**
     * {@inheritDoc}
     */
    public function setActividad($actividad)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setActividad', [$actividad]);

        return parent::setActividad($actividad);
    }

    /**
     * {@inheritDoc}
     */
    public function getAnimales()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAnimales', []);

        return parent::getAnimales();
    }

    /**
     * {@inheritDoc}
     */
    public function setAnimales($animales)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAnimales', [$animales]);

        return parent::setAnimales($animales);
    }

    /**
     * {@inheritDoc}
     */
    public function getEstablecimientos()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEstablecimientos', []);

        return parent::getEstablecimientos();
    }

    /**
     * {@inheritDoc}
     */
    public function setEstablecimientos($establecimientos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEstablecimientos', [$establecimientos]);

        return parent::setEstablecimientos($establecimientos);
    }

    /**
     * {@inheritDoc}
     */
    public function getRazaManejo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRazaManejo', []);

        return parent::getRazaManejo();
    }

    /**
     * {@inheritDoc}
     */
    public function setRazaManejo($raza_manejo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRazaManejo', [$raza_manejo]);

        return parent::setRazaManejo($raza_manejo);
    }

    /**
     * {@inheritDoc}
     */
    public function getSkype()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSkype', []);

        return parent::getSkype();
    }

    /**
     * {@inheritDoc}
     */
    public function setSkype($skype)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSkype', [$skype]);

        return parent::setSkype($skype);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCategoria', []);

        return parent::getCategoria();
    }

    /**
     * {@inheritDoc}
     */
    public function setCategoria($categoria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCategoria', [$categoria]);

        return parent::setCategoria($categoria);
    }

    /**
     * {@inheritDoc}
     */
    public function getNombreCategoriaCliente()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNombreCategoriaCliente', []);

        return parent::getNombreCategoriaCliente();
    }

    /**
     * {@inheritDoc}
     */
    public function getFechaCompra()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFechaCompra', []);

        return parent::getFechaCompra();
    }

    /**
     * {@inheritDoc}
     */
    public function setFechaCompra($fecha_compra)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFechaCompra', [$fecha_compra]);

        return parent::setFechaCompra($fecha_compra);
    }

    /**
     * {@inheritDoc}
     */
    public function getVencimiento()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVencimiento', []);

        return parent::getVencimiento();
    }

    /**
     * {@inheritDoc}
     */
    public function setVencimiento($vencimiento)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVencimiento', [$vencimiento]);

        return parent::setVencimiento($vencimiento);
    }

    /**
     * {@inheritDoc}
     */
    public function getFechaUltimoContacto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFechaUltimoContacto', []);

        return parent::getFechaUltimoContacto();
    }

    /**
     * {@inheritDoc}
     */
    public function setFechaUltimoContacto($fecha_ultimo_contacto)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFechaUltimoContacto', [$fecha_ultimo_contacto]);

        return parent::setFechaUltimoContacto($fecha_ultimo_contacto);
    }

    /**
     * {@inheritDoc}
     */
    public function getFechaUltimoPago()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFechaUltimoPago', []);

        return parent::getFechaUltimoPago();
    }

    /**
     * {@inheritDoc}
     */
    public function setFechaUltimoPago($fecha_ultimo_pago)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFechaUltimoPago', [$fecha_ultimo_pago]);

        return parent::setFechaUltimoPago($fecha_ultimo_pago);
    }

    /**
     * {@inheritDoc}
     */
    public function getNombreLicenciaCliente()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNombreLicenciaCliente', []);

        return parent::getNombreLicenciaCliente();
    }

    /**
     * {@inheritDoc}
     */
    public function getLicencia()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLicencia', []);

        return parent::getLicencia();
    }

    /**
     * {@inheritDoc}
     */
    public function setLicencia($licencia)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLicencia', [$licencia]);

        return parent::setLicencia($licencia);
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVersion', []);

        return parent::getVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function setVersion($version)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVersion', [$version]);

        return parent::setVersion($version);
    }

    /**
     * {@inheritDoc}
     */
    public function addUsuario($usuario)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addUsuario', [$usuario]);

        return parent::addUsuario($usuario);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsuarios()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsuarios', []);

        return parent::getUsuarios();
    }

    /**
     * {@inheritDoc}
     */
    public function getEventos()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEventos', []);

        return parent::getEventos();
    }

    /**
     * {@inheritDoc}
     */
    public function isPrimeraVenta()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPrimeraVenta', []);

        return parent::isPrimeraVenta();
    }

    /**
     * {@inheritDoc}
     */
    public function getPersona()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPersona', []);

        return parent::getPersona();
    }

    /**
     * {@inheritDoc}
     */
    public function setPersona($persona)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPersona', [$persona]);

        return parent::setPersona($persona);
    }

    /**
     * {@inheritDoc}
     */
    public function getDni()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDni', []);

        return parent::getDni();
    }

    /**
     * {@inheritDoc}
     */
    public function setDni($dni)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDni', [$dni]);

        return parent::setDni($dni);
    }

    /**
     * {@inheritDoc}
     */
    public function getCuit_cuil()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCuit_cuil', []);

        return parent::getCuit_cuil();
    }

    /**
     * {@inheritDoc}
     */
    public function setCuit_cuil($cuit_cuil)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCuit_cuil', [$cuit_cuil]);

        return parent::setCuit_cuil($cuit_cuil);
    }

    /**
     * {@inheritDoc}
     */
    public function getRazon_social()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRazon_social', []);

        return parent::getRazon_social();
    }

    /**
     * {@inheritDoc}
     */
    public function setRazon_social($razon_social)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRazon_social', [$razon_social]);

        return parent::setRazon_social($razon_social);
    }

    /**
     * {@inheritDoc}
     */
    public function getCondicion_iva()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCondicion_iva', []);

        return parent::getCondicion_iva();
    }

    /**
     * {@inheritDoc}
     */
    public function setCondicion_iva($condicion_iva)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCondicion_iva', [$condicion_iva]);

        return parent::setCondicion_iva($condicion_iva);
    }

    /**
     * {@inheritDoc}
     */
    public function getDireccion_facturacion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDireccion_facturacion', []);

        return parent::getDireccion_facturacion();
    }

    /**
     * {@inheritDoc}
     */
    public function setDireccion_facturacion($direccion_facturacion)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDireccion_facturacion', [$direccion_facturacion]);

        return parent::setDireccion_facturacion($direccion_facturacion);
    }

    /**
     * {@inheritDoc}
     */
    public function getBanco()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBanco', []);

        return parent::getBanco();
    }

    /**
     * {@inheritDoc}
     */
    public function setBanco($banco)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBanco', [$banco]);

        return parent::setBanco($banco);
    }

    /**
     * {@inheritDoc}
     */
    public function getCbu()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCbu', []);

        return parent::getCbu();
    }

    /**
     * {@inheritDoc}
     */
    public function setCbu($cbu)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCbu', [$cbu]);

        return parent::setCbu($cbu);
    }

    /**
     * {@inheritDoc}
     */
    public function getCargo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCargo', []);

        return parent::getCargo();
    }

    /**
     * {@inheritDoc}
     */
    public function setCargo($cargo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCargo', [$cargo]);

        return parent::setCargo($cargo);
    }

}
