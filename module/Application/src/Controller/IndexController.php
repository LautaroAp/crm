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
use PHPExcel\Classes\PHPExcel;
use DBAL\Entity\Cliente;
use Clientes\Service\ClientesManager;

class IndexController extends AbstractActionController
{
    
    private $entityManager;
    private  $profesionclienteManager;
    protected $result;

    public function __construct($entityManager,$clientesManager) {
        $this->entityManager=$entityManager;
        $this->clientesManager = $clientesManager;
    }
    

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
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $_SESSION['PARAMETROS_BACKUP']= $data;        
            $this->redirect()->toRoute("backup");            
        }
        return new ViewModel();
    }
    
    public function backupAction() {
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->clientesManager->getListaClientes();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }
    


}
