<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Uri\Uri;
use User\Form\LoginForm;
use User\Entity\User;

/**
 * This controller is responsible for letting the user to log in and log out.
 */
class AuthController extends AbstractActionController {

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Auth manager.
     * @var User\Service\AuthManager
     */
    private $authManager;

    /**
     * User manager.
     * @var User\Service\UserManager
     */
    private $userManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $authManager, $userManager) {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    /**
     * Authenticates user given email address and password credentials.
     */
    public function loginAction() {
        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successfull login.
        $this->layout()->setTemplate('layout/layout-login');
        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }
        $form = new LoginForm();
        $isLoginError = false;
        $this->procesarLogin($form);
        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    /**
     * The "logout" action performs logout operation.
     */
    public function logoutAction() {
        $this->authManager->logout();
        return $this->redirect()->toRoute('login');
    }
    
    private function procesarLogin($form){
        $data = $this->params()->fromPost();
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $result = $this->authManager->login($data['email'], $data['password'], $data['remember_me']);
            if ($result->getCode() == Result::SUCCESS) {
                $redirectUrl = $this->params()->fromPost('redirect_url', '');
                if (!empty($redirectUrl)) {
                    // The below check is to prevent possible redirect attack
                    // (if someone tries to redirect user to another domain).
                    $uri = new Uri($redirectUrl);
                    if (!$uri->isValid() || $uri->getHost() != null)
                        throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                }
                if (empty($redirectUrl)) {
                    return $this->redirect()->toRoute('home');
                } else {
                    $this->redirect()->toUrl($redirectUrl);
                }
            } else {
                // Error de Login
                $isLoginError = true;
            }
        } else {
            // Error de Formulario de Login
            $isLoginError = true;
        }
    }

}
