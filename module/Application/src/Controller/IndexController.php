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
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $result = $this->entityManager->getResult($data);
            hacerBackup($result);
        }
        return new ViewModel();
    }

    public function hacerBackup($result) {
        $filename = "Backup_" . $nombre_tabla . ".xls"; // File Name
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        foreach ($results as $x => $x_value) {
            echo '"' . $x . '",' . '"' . $x_value . '"' . "\r\n";
        }
        return $this->redirect()->toRoute('home');
    }

}
