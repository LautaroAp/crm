<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\DBAL\Schema\SchemaManager;
use Doctrine\DBAL\Schema\Table;

class IndexController extends AbstractActionController
{
    
    private $entityManager;
    
    
    public function indexAction()
    {
        
        $_SESSION['PARAMETROS_VENTA']=array();
        $_SESSION['PARAMETROS_CLIENTE'] =array();
        $this->layout()->setTemplate('layout/simple');
        return new ViewModel(array('titulo' => 'Hola mundo',));
    }
    
    public function viewAction(){
        return new ViewModel();
    }
    
    public function utilidadesAction(){
        return new ViewModel();
    }
    
    public function gestionAction(){
        return new ViewModel();
    }
    
    
    public function backupmenuAction(){
        //VER DE CONSEGUIR OBTENER TODAS LAS TABLAS DESDE SCHEMA MANAGER
        $tablas = [
                    ['id'=>1,'nombre'=>'CategoriaCliente'],
                    ['id'=>2,'nombre'=>'Cliente'],
                    ['id'=>3,'nombre'=>'Ejecutivo'],
                    ['id'=>4,'nombre'=>'Empresa'],
                    ['id'=>5,'nombre'=>'Evento'],
                    ['id'=>6,'nombre'=>'Licencia'],
                    ['id'=>7,'nombre'=>'Pais'],
                    ['id'=>8,'nombre'=>'Producto'],
                    ['id'=>9,'nombre'=>'ProfesionCliente'],
                    ['id'=>10,'nombre'=>'Provincia'],
                    ['id'=>11,'nombre'=>'Servicio'],
                    ['id'=>12,'nombre'=>'TipoEvento'],
                    ['id'=>13,'nombre'=>'Usuario']
                    ];
         return new ViewModel([
            'tablas' => $tablas,
         ]);
    }

    public function backupAction() {
        $filename = "Backup_" . $nombre_tabla . ".xls"; // File Name
        $filename = "webreport.csv";
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        $results = $this->entityManager->getRepository(Ejecutivo::class)->findAll();
        foreach ($results as $x => $x_value) {
            echo '"' . $x . '",' . '"' . $x_value . '"' . "\r\n";
        }
        return $this->redirect()->toRoute('home');
    }

}
