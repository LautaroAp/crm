<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Pais\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class PaisController extends AbstractActionController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;
    
     /**
     * Pais manager.
     * @var User\Service\PaisManager 
     */
    
    protected $paisManager;


    public function __construct($entityManager, $paisManager)
    {
        $this->entityManager = $entityManager;
        $this->paisManager = $paisManager;
    }
    
   
    public function indexAction()
    {
        return $this->procesarIndexAction();
    }
    
    private function procesarIndexAction(){
        $paises = $this->paisManager->getPaises();   
        return new ViewModel([
            'paises' => $paises
        ]);
    }
    
    public function addAction()
    {
        $view = $this->procesarAddAction();
        return $view;
       
    }
    
    private function procesarAddAction() {
    $form = $this->paisManager->createForm();
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $pais = $this->paisManager->getPaisFromForm($form, $data);
            return $this->redirect()->toRoute('application', ['action' => 'view']);
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction() 
    {
        $view = $this->procesarEditAction();
        return $view;
       
    }
  
    public function procesarEditAction()
    {
        $id = $this->params()->fromRoute('id', -1);
        $pais = $this->paisManager->getPaisId($id);
        $form =$this->paisManager->getFormForPais($pais);
        if ($form == null){
            $this->getResponse()->setStatusCode(404);
        }
        else{
            if ($this->getRequest()->isPost()) {
               $data = $this->params()->fromPost();
               if ($this->paisManager->formValid($form,$data)){
                    $this->paisManager->updatePais($pais,$form);
                    return $this->redirect()->toRoute('application',['action'=>'view']);    
               }
            }               
         else {
            $this->paisManager->getFormEdited($form, $pais);
         }
         
        return new ViewModel(array(
            'pais' => $pais,
            'form' => $form
        ));
        }
            
    }

    public function removeAction() 
    {
       //$this->layout()->setTemplate('layout/simple');
       $view = $this->procesarRemoveAction();
       return $view;
    }
    
    public function procesarRemoveAction(){
        $id = (int)$this->params()->fromRoute('id', -1);
        $pais = $this->paisManager->getPaisId($id);
         
        if ($pais == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        else{
            $this->paisManager->removePais($pais);
            return $this->redirect()->toRoute('application', ['action'=>'view']);   
        }
    }
  
      public function viewAction() 
    {
       /* $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find a user with such ID.
        $pais = $this->entityManager->getRepository(Pais::class)
                ->find($id_pais);
        
        if ($pais == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
                
        return new ViewModel([
            'pais' => $pais
        ]);*/
          
          
          return new ViewModel();
    }
}
