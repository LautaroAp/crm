<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Ejecutivo
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
     * Get the value of id
     */ 


    /**
     * 
     * @ORM\OneToMany(targetEntity="\DBAL\Entity\Evento", mappedBy="persona")
     * @ORM\OrderBy({"fecha" = "desc"})
     */
    private $eventos;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
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
        return $this->email;
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
}
