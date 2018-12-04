<?php namespace DBAL\test\Entity;

use DBAL\Entity\CategoriaCliente;
use PHPUnit_Framework_TestCase as TestCase;

class CategoriaClienteTest extends TestCase {
    
    public function testInitialTipoEventoValuesAreNull() {
        $categoria_cliente = new CategoriaCliente();
        $this->assertNull($categoria_cliente->getId(), '"id_tipo_evento" no el NULL !!');
        $this->assertNull($categoria_cliente->getNombre(), '"nombre" no es NULL !!');
    }

    public function testTipoEventoSetsAndGetsPropertiesCorrectly() {
        $categoria_cliente = new CategoriaCliente();
        $categoria_cliente->setId(123);
        $categoria_cliente->setNombre("test");
        $this->assertSame(123, $categoria_cliente->getId(), '"id" no seteado correctamente !!');
        $this->assertSame("test", $categoria_cliente->getNombre(), '"nombre" no seteado correctamente !!');
        }
}