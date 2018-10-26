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

class IndexController extends AbstractActionController
{
    
    private $entityManager;
    private  $clientesManager;


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
            $result = $this->clientesManager->getDatosClientes($data);
            $this->hacerBackup($result);
            
        }
        return new ViewModel();
    }

    public function hacerBackup($result) {
  
//        // filename for download
//        $filename = "Backup_Clientes" . date('Ymd') . ".xls";
//
//        header("Content-Disposition: attachment; filename=\"$filename\"");
//        header("Content-Type: application/vnd.ms-excel");
//
//        $flag = false;
//        foreach ($result as $row) {
//            if (!$flag) {
//                // display field/column names as first row
//                echo implode("\t", array_keys($row)) . "\r\n";
//                $flag = true;
//            }
//            array_walk($row, __NAMESPACE__ . '\cleanData');
//            echo implode("\t", array_values($row)) . "\r\n";
//        }

//        require_once 'PHPExcel/Classes/PHPExcel.php';

        $objPHPExcel = new PHPExcel();

// Establecer propiedades
        $objPHPExcel->getProperties()
                ->setCreator("Cattivo")
                ->setLastModifiedBy("Cattivo")
                ->setTitle("Documento Excel de Prueba")
                ->setSubject("Documento Excel de Prueba")
                ->setDescription("Demostracion sobre como crear archivos de Excel desde PHP.")
                ->setKeywords("Excel Office 2007 openxml php")
                ->setCategory("Pruebas de Excel");

// Agregar Informacion
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Valor 1')
                ->setCellValue('B1', 'Valor 2')
                ->setCellValue('C1', 'Total')
                ->setCellValue('A2', '10')
                ->setCellValue('C2', '=sum(A2:B2)');
// Renombrar Hoja

        $objPHPExcel->getActiveSheet()->setTitle('Tecnologia Simple');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.

        $objPHPExcel->setActiveSheetIndex(0);


// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pruebaReal.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}
