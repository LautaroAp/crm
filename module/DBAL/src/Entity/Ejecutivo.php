<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Ejecutivo
 *
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="EJECUTIVO")
 */
class Ejecutivo {
    //put your code here
    
    /**
     * @ORM\Id
     * @ORM\Column(name="ID_EJECUTIVO", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id_ejecutivo;   
    
    /**
     * @ORM\Column(name="APELLIDO_EJECUTIVO", nullable=true, type="string", length=255)
     */
    protected $apellido;

     /**
     * @ORM\Column(name="NOMBRE_EJECUTIVO", nullable=true, type="string", length=255)
     */
    protected $nombre;
    
         /**
     * @ORM\Column(name="MAIL_EJECUTIVO", nullable=true, type="string", length=255)
     */
    protected $mail;
    
         /**
     * @ORM\Column(name="NOMUSR", nullable=true, type="string", length=255)
     */
    protected $usuario;
    
         /**
     * @ORM\Column(name="PASSUSR", nullable=true, type="string", length=255)
     */
    protected $clave;
    
    
  
    
    function getId_ejecutivo() {
        return $this->id_ejecutivo;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getMail() {
        return $this->mail;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getClave() {
        return $this->clave;
    }

    function setId_ejecutivo($id_ejecutivo) {
        $this->id_ejecutivo = $id_ejecutivo;
    }

    function setApellido ($apellido) {
        $this->apellido = $apellido;
    }

    function setNombre ($nombre) {
        $this->nombre= $nombre;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }


}
