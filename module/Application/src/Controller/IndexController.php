<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
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
        
        // $this->layout()->setTemplate('layout/simple');
        return new ViewModel();
        
    }
}

