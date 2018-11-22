<?php

namespace Usuario\Service;

use DBAL\Entity\Usuario;
use DBAL\Entity\Cliente;
//use Zend\Crypt\Password\Bcrypt;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;


/**
 * This service is responsible for adding/editing usuarios
 * and changing usuario password.
 */
class UsuarioManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * PHP template renderer.
     * @var type 
     */
    private $viewRenderer;

    /**
     * Application config.
     * @var type 
     */
    private $config;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $viewRenderer, $config) {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    //La funcion getTabla() devuelve tabla de clientes sin filtro    
    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Usuario::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    /**
     * This method adds a new usuario.
     */
//    public function addUsuario($data) {
//        $usuario = new Usuario();
//        $usuario->setNombre($data['nombre'])
//                ->setTelefono($data['telefono'])
//                ->setMail($data['mail'])
//                ->setSkype($data['skype']);
//        $idCliente = $data['id'];
//        
//        $cliente = $this->entityManager
//                ->getRepository(Cliente::class)
//                ->findOneBy(['Id' => $idCliente]);
//        
//        $usuario->setCliente($cliente);
//        if ($this->tryAddUsuario($usuario)) {
//            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
//            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Datos guardados correctamente';
//        } else {
//            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
//            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al guardar datos';
//        }
//        return $usuario;
//    }

        public function addUsuario($data) {
        $usuario = new Usuario();
        $usuario->setNombre($data['nombre'])
                ->setTelefono($data['telefono'])
                ->setMail($data['mail'])
                ->setSkype($data['skype']);
        $idCliente = $data['id'];
        
        
        $cliente = $this->entityManager
                ->getRepository(Cliente::class)
                ->findOneBy(['Id' => $idCliente]);
        
        $usuario->setId_cliente($cliente);

        if ($this->tryAddUsuario($usuario)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Datos guardados correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al guardar datos';
        }
        return $usuario;
    }
    
    public function updateUsuario($usuario, $data) {
        $usuario->setNombre($data['nombre']);
        $usuario->setTelefono($data['telefono']);
        $usuario->setMail($data['mail']);
        $usuario->setSkype($data['skype']);
        if ($this->tryUpdateUsuario($usuario)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Datos editados correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al editar datos';
        }
        return true;
    }

    public function removeUsuario($usuario) {
        if ($this->tryRemoveUsuario($usuario)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Datos eliminados correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al eliminar datos';
        }
    }

    private function tryAddUsuario($usuario) {
        try {
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateUsuario($usuario) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    private function tryRemoveUsuario($usuario) {
        try {
            $this->entityManager->remove($usuario);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    public function recuperarUsuario($id_usuario) {
        $usuario = $this->entityManager->getRepository(Usuario::class)
                ->findOneBy(['id_usuario' => $id_usuario]);

        return $usuario;
    }
    
    public function buscarUsuarios($parametros) {      
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('U')
                 ->from(Usuario::class, 'U');
        $indices = array_keys($parametros);

        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];

            if ($i == 0) {
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $queryBuilder->where("U.nombre LIKE ?$p");
                } else {
                    $queryBuilder->where("U.$nombreCampo LIKE ?$p");
                }
            } else {
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $queryBuilder->andWhere("U.nombre LIKE ?$p");
                } else {
                    $queryBuilder->andWhere("U.$nombreCampo like ?$p");
                }
            }
            $queryBuilder->setParameter("$p", '%'.$valorCampo.'%');
        }
        return $queryBuilder->getQuery()->execute();
    }

    public function getClientesUsuarioAdicional($parametros){
        $clientes = array();
        if (COUNT($parametros)>0){
            $usuarios = $this->buscarUsuarios($parametros);
            $i = 0;
            foreach ($usuarios as $usuario):
                if ($this->entityManager->getRepository(Cliente::class)->findOneBy(['Id'=>$usuario->getCliente()])){
                    $clientes[$i] = $usuario->getCliente();
                    $i=$i+1;
            }
            endforeach;
        }
        return $clientes;
    }
    
    public function getClientes($parametros){
        $entityManager = $this->entityManager;
        $qb = $entityManager->createQueryBuilder();
        $qb->select('C') 
                ->from(Usuario::class, 'U')
                ->leftJoin(Cliente::class, 'C',  "WITH", 'C.Id = U.id_cliente');
        $indices = array_keys($parametros);

        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];

            if ($i == 0) {
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $qb->where("U.nombre LIKE ?$p");
                } else {
                    $qb->where("U.$nombreCampo LIKE ?$p");
                }
            } else {
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $qb->andWhere("U.nombre LIKE ?$p");
                } else {
                    $qb->andWhere("U.$nombreCampo like ?$p");
                }
            }
            $qb->setParameter("$p", '%'.$valorCampo.'%');
        }

        return $qb->getQuery();
    }
     
    public function getUsuario($id){
        return $this->entityManager->getRepository(Usuario::class)
                ->find($id);
    }
    
    public function getIdCliente($id){
        $usuario = $this->recuperarUsuario($id);
        return $usuario->getCliente()->getId();
    }
    
    public function getNombre($id){
        $usuario = $this->getUsuario($id);
        return $usuario->getNombre();
    }
    public function getTelefono($id){
        $usuario = $this->getUsuario($id);
        return $usuario->getTelefono();
    }
    public function getSkype($id){
        $usuario = $this->getUsuario($id);
        return $usuario->getSkype();
    }
    public function getMail($id){
        $usuario = $this->getUsuario($id);
        return $usuario->getMail();
    }
    
    public function getData($id){
        $usuario = $this->getUsuario($id);
        $arr = [
            'nombre' =>$usuario->getNombre(),
            'telefono' =>$usuario->getTelefono(),
            'skype' => $usuario->getSkype(),
            'mail' => $usuario->getMail(),
        ];
        return $arr;
    }
    
}
