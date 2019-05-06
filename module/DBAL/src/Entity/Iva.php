<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Iva
 *
 * This class represents a registered categoriaProducto.
 * @ORM\Entity()
 * @ORM\Table(name="IVA")
 */
class Iva
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="NOMBRE", nullable=true, type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(name="VALOR", nullable=true, type="decimal")
     */
    protected $valor;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * Get the value of id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return self
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }


    /**
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
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
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getJSON(){

        $output = "";
        $output .= '"Id": "' . $this->getId() .'", ';
        $output .= '"Nombre": "' . $this->getNombre() .'", ';
        $output .= '"Descripcion": "' . $this->getDescripcion() .'", ';
        $output .= '"Valor": "' . $this->getValor() .'" ';
        // $output = '"bien": {'.$output.'}';

        return  '{'.$output.'}' ;
    }
}
