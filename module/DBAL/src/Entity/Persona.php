<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Persona
 *
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="PERSONA")
 */
class Persona {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;   
    

    /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string", length=255)
     */
    protected $nombre;

     /**
     * @ORM\Column(name="TELEFONO", nullable=true, type="string", length=255)
     */
    protected $telefono;
    
    
    /**
     * @ORM\Column(name="MAIL", nullable=true, type="string", length=255)
     */
    protected $email;
    
    

    /**
     * @ORM\Column(name="ESTADO", nullable=true, type="string", length=1)
     */
    private $estado;

    /**
     * @ORM\Column(name="TIPO", nullable=true, type="string", length=10)
     */
    private $tipo;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\Evento", mappedBy="persona")
     * @ORM\OrderBy({"id" = "desc"})
     */
    private $eventos;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\Transaccion", mappedBy="persona")
     * @ORM\OrderBy({"id" = "desc"})
     */
    private $transacciones;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\DatoAdicional", mappedBy="id_ficha_persona")
     */
    private $datos_adicionales;


    /**
     * @ORM\Column(name="CUIT", nullable=true, type="string")
     */
    private $cuit_cuil;

    /**
     * @ORM\Column(name="RAZON_SOCIAL", nullable=true, type="string")
     */
    private $razon_social;

    /**
     * Many Personas have One Categoria.
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="ID_CONDICION_IVA", referencedColumnName="ID")
     */
    private $condicion_iva;

    /**
     * @ORM\Column(name="BANCO", nullable=true, type="string")
     */
    private $banco;

    /**
     * @ORM\Column(name="CBU", nullable=true, type="string")
     */
    private $cbu;

   
    /**
     * @ORM\Column(name="DIRECCION_FACTURACION", nullable=true, type="string", length=255)
     */
    private $direccion_facturacion;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return ucwords(strtolower($this->nombre));
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of telefono
     */ 
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */ 
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return strtolower($this->email);
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getEstadoNombre() {
        if ($this->estado == "S") {
            return "Activo";
        } else {
            return "Inactivo";
        }
    }

    public function setEstado($estado) {
        $this->estado = $estado;
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
        return ucwords(strtolower($this->razon_social));
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
    public function getCondicion_iva() {
        if (is_null($this->condicion_iva)){
            return null;
        }
        return $this->condicion_iva;
    }

    /**
     * Set the value of condicion_iva
     *
     * @return  self
     */ 
    public function setCondicion_iva($condicion_iva) {
        $this->condicion_iva = $condicion_iva;
        return $this;
    }

    public function getNombreCondicionIva() {
        if (is_null($this->condicion_iva)) {
            return null;
        } else {
            return ucwords(strtolower($this->condicion_iva->getNombre()));
        }
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

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getEventos() {
        return $this->eventos;
    }

    public function getTransacciones(){
        return $this->transacciones;
    }

    /**
     * Get the value of datos_adicionales
     */ 
    public function getDatos_adicionales()
    {
        return $this->datos_adicionales;
    }

    public function getJson(){
        $output = "";
        $output .= '"value": "' . $this->getId() .'", ';
        $output .= '"label": "' . $this->getNombre() .'", ';
        $output .= '"nro": "' . $this->getId() .'", ';        
        $output .= '"nombre": "' . $this->getNombre() .'" ';
        
        return  '{'.$output.'}' ;
    }

}
