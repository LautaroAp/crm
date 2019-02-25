<?php

namespace Ejecutivo\Controller;
use Zend\View\Model\ViewModel;
use Ejecutivo\Controller\EjecutivoController;


class EjecutivoInactivoController extends EjecutivoController
{
    /**
     * @var DoctrineORMEntityManager
     */
    private $ejecutivoInactivoManager;

    public function __construct($entityManager, $ejecutivoInactivoManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->ejecutivoInactivoManager = $ejecutivoInactivoManager;
        $this->personaManager = $personaManager;
    }

    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    public function indexAction() {
        $view = $this->procesarIndexAction();
        return $view;
    }
    public function procesarIndexAction(){
        $this->prepararBreadcrumbs("Inactivos", "/inactivos", "Ejecutivos");
        $paginator = $this->ejecutivoInactivoManager->getTabla();
        $pag = $this->getPaginator($paginator);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form = new EjecutivoForm('create', $this->entityManager);
            $this->ejecutivoManager->getEjecutivoFromForm($form, $data);
            return $this->redirect()->toRoute('ejecutivos');
        }
        return new ViewModel([
            'ejecutivos' => $pag,
            ]);
    }
   
    public function activarAction(){
        $id = $this->params()->fromRoute('id');
        $this->procesarActivar($id);
        $this->redirect()->toRoute('ejecutivos/inactivos');
        return new ViewModel();
    }
    
    private function procesarActivar($id){
        $this->ejecutivoInactivoManager->activarEjecutivo($id);
        $_SESSION['MENSAJES']['ejecutivo_inactivo'] = 1;
        $_SESSION['MENSAJES']['ejecutivo_inactivo_msj'] = 'Ejecutivo dado de Alta correctamente';
    }
   
}
