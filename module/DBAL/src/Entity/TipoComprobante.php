<?php
namespace DBAL\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Iva
 *
 * This class represents a registered categoriaProducto.
 * @ORM\Entity()
 * @ORM\Table(name="TIPO_COMPROBANTE")
 */
class TipoComprobante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Many Services have One Transaccion.
     * @ORM\ManyToOne(targetEntity="Comprobante")
     * @ORM\JoinColumn(name="ID_COMPROBANTE", referencedColumnName="ID")
     */
    protected $comprobante;

    /**
     * @ORM\Column(name="DESCRIPCION", nullable=true, type="string")
     */
    protected $descripcion;

    /**
     * @ORM\Column(name="CODIGO", nullable=true, type="integer")
     */
    protected $codigo;

    /**
     * @ORM\Column(name="TIPO", nullable=true, type="string")
     */
    protected $tipo;

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
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        // return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        // $this->nombre = $nombre;

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
        $output .= '"Nombre": "' . $this->getNombre() .'" ';
    
        return  '{'.$output.'}' ;
    }

    /**
     * Get the value of codigo
     */ 
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */ 
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

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
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }
}
