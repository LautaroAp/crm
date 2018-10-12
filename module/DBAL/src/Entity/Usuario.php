<?php
namespace DBAL\Entity;

use DBAL\Entity\Cliente;
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
     * @ORM\Column(name="NOMBRE_CONTACTO", nullable=true, type="string", length=255)
     */
    protected $nombre;

     /**
     * @ORM\Column(name="TELEFONO", nullable=true, type="string", length=255)
     */
    protected $telefono;
    
      /**
     * @ORM\Column(name="CELULAR", nullable=true, type="string", length=255)
     */
    protected $celular;
    
         /**
     * @ORM\Column(name="MAIL_CONTACTO", nullable=true, type="string", length=255)
     */
    protected $mail;
    
    function getId() {
        return $this->id_usuario;
    }

    function getCliente() {
        return $this->id_cliente;
    }

    
    function getNombre() {
        return $this->nombre;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getMail() {
        return $this->mail;
    }

    function getSkype(){
        return "no definido";
    }
    
    function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
//        $clientes->addUsuario($this);
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function getCelular() {
        return $this->celular;
    }

    function setCelular($celular) {
        $this->celular = $celular;
    }



}
