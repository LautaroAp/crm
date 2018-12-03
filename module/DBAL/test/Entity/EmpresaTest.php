<?php namespace DBAL\test\Entity;

use DBAL\Entity\Empresa;
use PHPUnit_Framework_TestCase as TestCase;

class EmpresaTest extends TestCase {
    
    public function testInitialTipoEventoValuesAreNull() {
        $empresa = new Empresa();
        $this->assertNull($empresa->getId(), '"id_tipo_evento" no el NULL !!');
        $this->assertNull($empresa->getNombre(), '"nombre" no es NULL !!');
        $this->assertNull($empresa->getDireccion(), '"direcion" no es NULL !!');
        $this->assertNull($empresa->getTelefono(), '"telefono" no es NULL !!');
        $this->assertNull($empresa->getMail(), '"mail" no es NULL !!');
        $this->assertNull($empresa->getMovil(), '"movil" no es NULL !!');
        $this->assertNull($empresa->getFax(), '"fax" no es NULL !!');
        $this->assertNull($empresa->getWeb(), '"web" no es NULL !!');
        $this->assertNull($empresa->getCuit_cuil(), '"cuit_cuil" no es NULL !!');
        $this->assertNull($empresa->getVencimiento_cai(), '"vencimiento_cai" no es NULL !!');
        $this->assertNull($empresa->getRazon_social(), '"razon_social" no es NULL !!');
        $this->assertNull($empresa->getTipo_iva(), '"tipo_iva" no es NULL !!');
        $this->assertNull($empresa->getLocalidad(), '"localidad" no es NULL !!');
        $this->assertNull($empresa->getProvincia(), '"provincia" no es NULL !!');
        $this->assertNull($empresa->getPais(), '"pais" no es NULL !!');
        $this->assertNull($empresa->getCP(), '"cp" no es NULL !!');
    }

    public function testTipoEventoSetsAndGetsPropertiesCorrectly() {
        $empresa = new Empresa();
        $empresa->setNombre("nombre");
        $empresa->setDireccion("direccion");
        $empresa->setTelefono("telefono");
        $empresa->setMail("mail");
        $empresa->setMovil("movil");
        $empresa->setFax("fax");
        $empresa->setWeb("web");
        $empresa->setCuit_cuil("cuit_cuil");
        $empresa->setVencimiento_cai("vencimiento_cai");
        $empresa->setRazon_social("razon_social");
        $empresa->setTipo_iva("tipo_iva");
        $empresa->setLocalidad("localidad");
        $empresa->setProvincia("provincia");
        $empresa->setPais("pais");
        $empresa->setCP("cp");
        $this->assertSame("nombre", $empresa->getNombre(), '"nombre" no seteado correctamente !!');
        $this->assertSame("direccion", $empresa->getDireccion(), '"direccion" no seteado correctamente !!');
        $this->assertSame("telefono", $empresa->getTelefono(), '"telefono" no seteado correctamente !!');
        $this->assertSame("mail", $empresa->getMail(), '"mail" no seteado correctamente !!');
        $this->assertSame("movil", $empresa->getMovil(), '"nombre" no seteado correctamente !!');

        }
}