<?php namespace DBAL\test\Entity;

use DBAL\Entity\TipoEvento;
use PHPUnit_Framework_TestCase as TestCase;

class TipoEventoTest extends TestCase {
    
    public function testInitialTipoEventoValuesAreNull() {
        $tipo_evento = new TipoEvento();
        $this->assertNull($tipo_evento->getId(), '"id_tipo_evento" no el NULL !!');
        $this->assertNull($tipo_evento->getNombre(), '"nombre" no es NULL !!');
    }

    public function testTipoEventoSetsAndGetsPropertiesCorrectly() {
        $tipo_evento = new TipoEvento();
        $tipo_evento->setId_tipoevento(123);
        $tipo_evento->setNombre("test");
        $this->assertSame(123, $tipo_evento->getId(), '"id" no seteado correctamente !!');
        $this->assertSame("test", $tipo_evento->getNombre(), '"nombre" no seteado correctamente !!');
        }
}