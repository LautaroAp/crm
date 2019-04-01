<?php
namespace DBAL\Entity;

use DBAL\Entity\Cliente;
use DBAL\Entity\Persona;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Ejecutivo
 *
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="USUARIO")
 */
class Usuario {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_USUARIO", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id_usuario;   
    
    /**
     * Many Usuario have One Cliente.
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumn(name="ID_CLIENTE", referencedColumnName="ID_CLIENTE")
     */ 
    protected $id_cliente;
        
    /**
     * @ORM\Column(name="SKYPE", nullable=true, type="string", length=255)
     */
    protected $skype;
    
     /**
     * Many Usuario have One Persona.
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="ID_PERSONA", referencedColumnName="ID")
     */ 
    protected $persona;

    
    function getId() {
        return $this->id_usuario;
    }

    function getCliente() {
        return $this->id_cliente;
    }
    function getSkype(){
        return $this->skype;
    }
    
    function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
        return $this;
    }


    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
//        $clientes->addUsuario($this);
    }

    function setSkype($skype) {
        $this->skype = $skype;
        return $this;
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
}
