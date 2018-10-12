<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ejecutivo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;

use DBAL\Entity\Ejecutivo;
use DBAL\Entity\Evento;
use Ejecutivo\Form\EjecutivoForm;

class EjecutivoController extends AbstractActionController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;
    

    //private $ejecutivoManager;
    
    public function __construct($entityManager, $ejecutivoManager)
    {
        $this->entityManager = $entityManager;
        $this->ejecutivoManager = $ejecutivoManager;
    }
    
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    public function indexAction()
    {
        $ejecutivos = $this->getEntityManager()
                ->getRepository(Ejecutivo::class)->findAll();         
        
        $paginator = $this->ejecutivoManager->getTabla();
        $mensaje ="";
        
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        
        return new ViewModel([
            'ejecutivos' => $paginator,
            'mensaje' => $mensaje
        ]);
    }
    
    private function procesarAddAction(){
        // Create ejecutivo form
        $form = new EjecutivoForm('create', $this->entityManager);
        
        // Check if ejecutivo has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                // Add ejecutivo.
                $ejecutivo = $this->ejecutivoManager->addEjecutivo($data);
                
                return $this->redirect()->toRoute('ejecutivos');
                /*
                // Redirect to "view" page
                return $this->redirect()->toRoute('ejecutivos', 
                        ['action'=>'view', 'id'=>$ejecutivo->getId_ejecutivo()]);
                
                 */                
            }               
        } 
        
        return new ViewModel([
                'form' => $form
            ]);
    }
    
    
    public function addAction()
    {
       $view = $this->procesarAddAction();
        
        return $view; 
    }
     
    public function viewAction() 
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find a user with such ID.
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
                ->find($id);
        
        if ($ejecutivo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
                
        return new ViewModel([
            'ejecutivo' => $ejecutivo
        ]);
    }
    
    private function procesarEditAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);  
        
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
                ->find($id);
        
        if ($ejecutivo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Create user form
        $form = new EjecutivoForm('update', $this->entityManager, $ejecutivo);
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                // Update the user.
                $this->ejecutivoManager->updateEjecutivo($ejecutivo, $data);
                
                return $this->redirect()->toRoute('ejecutivos');
                                              
            }               
        } else {
            $form->setData(array(
                    'id_ejecutivo'=>$ejecutivo->getId_ejecutivo(),
                    'apellido'=>$ejecutivo->getApellido(),
                    'nombre'=>$ejecutivo->getNombre(),
                    'mail'=>$ejecutivo->getMail(),
                    'usuario'=>$ejecutivo->getUsuario(),
                    'clave'=>$ejecutivo->getClave(),                   
                ));
        }
        
        return new ViewModel(array(
            'ejecutivo' => $ejecutivo,
            'form' => $form
        ));
        
    }
    
    public function editAction() 
    {
        $view = $this->procesarEditAction();
        
        return $view;
    }
    
    private function procesarDeleteAction()
    {
        if (!$this->getRequest()->isPost()) {
            
            $id = $this->params()->fromRoute('id');
            if ($id<1) {
                $this->getResponse()->setStatusCode(404);
                return;
            }

            $ejecutivo = $this->ejecutivoManager->recuperarEjecutivo($id);
            if ($ejecutivo == null) {
                $this->getResponse()->setStatusCode(404);
                return;
            }   
            
            // Recupero todos los eventos del ejecutivo
            $eventos = $this->entityManager->getRepository(Evento::class)
                    ->findBy(['id_ejecutivo'=>$id]);
            // Recorro array de eventos eliminando
            foreach($eventos as $evento)
            {
                $this->entityManager->remove($evento);
                $this->entityManager->flush();
            }
            
            $this->ejecutivoManager->removeEjecutivo($ejecutivo);
            // Redirect the user to "admin" page.
            $this->redirect()->toRoute('ejecutivos');        
        }
        else {
            $view = new ViewModel();
            return $view;
        }
        
    }
    
    public function deleteAction()
    {
        $view = $this->procesarDeleteAction();
        return $view;
    }
}
