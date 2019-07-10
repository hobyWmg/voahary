<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Gta;
use AppBundle\Entity\Dgd;
use AppBundle\Entity\Passenger;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use AppBundle\Services\Log;

class ImportController extends Controller
{
    /**
     * @Route("adminarssam/import-gta-data", name="arssam_import_gta")
     */
    public function importGtaAction(Request $request,Log $actLog)
    {       
        $user = $this->getUser(); 
        $log = [];
        $inputFileType = 'Xls';
        $inputFileName = $this->getFilename($request, 'gta');
        if (is_null($inputFileName)){
             /** Add Log */
            $log['user'] = $user;
            $log['action'] = 'Import';
            $log['description'] = 'Import fichier GTA';
            $log['success'] = false;
            $log['error']='fichier introuvable';
            $actLog->addLog($log);
        /** endLog */
            $this->addFlash('error','Fichier xls introuvable');
            return $this->redirectToRoute('velirano_admin_homepage');
        }
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if (count($sheetData)>0){
            $em = $this->getDoctrine()->getManager();
            $worksheet->getStyle('B2:B'.count($sheetData))
            ->getNumberFormat()
            ->setFormatCode('dd/mm/yyyy');
            $worksheet->getStyle('C2:C'.count($sheetData))
            ->getNumberFormat()
            ->setFormatCode('hh:mm');
            for ($row = 2; $row <= count($sheetData); ++$row) {
                $gta = new Gta();
                $num = $worksheet->getCell('A' . $row)->getValue();
                $daty = $worksheet->getCell('B' . $row)->getFormattedValue();
                $lera = $worksheet->getCell('C' . $row)->getFormattedValue();
                $exist = $em->getRepository('AppBundle:Gta')->isAlreadyExist($num, $daty, $lera);
                if (!$exist){
                    $gta->setNumPlaque($num);
                    if ($daty!=""){
                        $datetime = new \DateTime();
                        $newDate = $datetime->createFromFormat('d/m/Y', $daty);
                        $gta->setDaty($newDate);
                    }
                    $gta->setLera($lera);
                    $em->persist($gta);
                }
            }
            $em->flush();
            if(file_exists($inputFileName)){
                unlink($inputFileName);
            }
            $this->addFlash('success','Import de donnée gendarmerie réussi');
            return $this->redirectToRoute('arssam_gta_list');
        }
        $this->addFlash('error','pas de donnée importé');
        return $this->redirectToRoute('velirano_admin_homepage');
    }
    
    /**
     * @Route("adminarssam/import-dgd-data", name="arssam_import_dgd")
     */
    public function importDgdAction(Request $request,Log $actLog)
    { 
        $user = $this->getUser(); 
        $log = [];
        $inputFileType = 'Xls';
        $inputFileName = $this->getFilename($request, 'dgd');
        if (is_null($inputFileName)){
            /** Add Log */
            $log['user'] = $user;
            $log['action'] = 'Import';
            $log['description'] = 'Import fichier DGD';
            $log['success'] = false;
            $log['error']='fichier introuvable';
            $actLog->addLog($log);
            /** endLog */
            $this->addFlash('error','Fichier xls introuvable');
            return $this->redirectToRoute('velirano_admin_homepage');
        }
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if (count($sheetData)>0){
            $em = $this->getDoctrine()->getManager();
            
            for ($row = 2; $row <= count($sheetData); ++$row) {
                $dgd = new Dgd();
                $exist = $em->getRepository('AppBundle:Dgd')->isAlreadyExist(
                    $worksheet->getCell('A' . $row)->getValue(),
                    $worksheet->getCell('B' . $row)->getValue(),
                    $worksheet->getCell('C' . $row)->getValue(),
                    $worksheet->getCell('D' . $row)->getValue(),
                    $worksheet->getCell('E' . $row)->getValue(),
                    $worksheet->getCell('F' . $row)->getValue(),
                    $worksheet->getCell('G' . $row)->getFormattedValue()
                );
                if (!$exist){
                    $dgd->setContrevenants($worksheet->getCell('A' . $row)->getValue());
                    $dgd->setNumero($worksheet->getCell('B' . $row)->getValue());
                    $dgd->setInfraction($worksheet->getCell('C' . $row)->getValue());
                    $dgd->setValeurCaf($worksheet->getCell('D' . $row)->getValue());
                    $dgd->setDcDe($worksheet->getCell('E' . $row)->getValue());
                    $dgd->setSituation($worksheet->getCell('F' . $row)->getValue());
                    $dgd->setMarchandises($worksheet->getCell('G' . $row)->getValue());
                    $em->persist($dgd);
                }
            }
            $em->flush();
            /** Add Log */
            $log['user'] = $user;
            $log['action'] = 'Import';
            $log['description'] = 'Import fichier DGD';
            $log['success'] = true;
            $actLog->addLog($log);
            /** endLog */
            if(file_exists($inputFileName)){
                unlink($inputFileName);
            }
            $this->addFlash('success','Import de donnée de la douane réussi');
            return $this->redirectToRoute('arssam_dgd_list');
        }
        $this->addFlash('error','pas de donnée importé');
        return $this->redirectToRoute('velirano_admin_homepage');
    }
    
    /**
     * @Route("adminarssam/import-passenger-data", name="arssam_import_passenger")
     */
    public function importPassengerAction(Request $request, Log $actLog)
    { 
        $user = $this->getUser(); 
        $log = [];
        $inputFileType = 'Xls';
        $inputFileName = $this->getFilename($request, 'passenger');
        
        if (is_null($inputFileName)){
             /** Add Log */
        $log['user'] = $user;
        $log['action'] = 'Import';
        $log['description'] = 'Import fichier PAF';
        $log['success'] = false;
        $log['error']='fichier introuvable';
        $actLog->addLog($log);
        /** endLog */
            $this->addFlash('error','Fichier xls introuvable');
            return $this->redirectToRoute('velirano_admin_homepage');
        }
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if (count($sheetData)>0){
            $em = $this->getDoctrine()->getManager();
            $worksheet->getStyle('E2:E'.count($sheetData))
            ->getNumberFormat()
            ->setFormatCode('dd/mm/yyyy');
            $worksheet->getStyle('G2:G'.count($sheetData))
            ->getNumberFormat()
            ->setFormatCode('dd/mm/yyyy');
            for ($row = 2; $row <= count($sheetData); ++$row) {
                $passenger = new Passenger();
                
                $exist = $em->getRepository('AppBundle:Passenger')->isAlreadyExist(
                    $worksheet->getCell('A' . $row)->getValue(),
                    $worksheet->getCell('B' . $row)->getValue(),
                    $worksheet->getCell('C' . $row)->getValue(),
                    $worksheet->getCell('D' . $row)->getValue(),
                    $worksheet->getCell('E' . $row)->getFormattedValue(),
                    $worksheet->getCell('F' . $row)->getValue(),
                    $worksheet->getCell('G' . $row)->getFormattedValue(),
                    $worksheet->getCell('H' . $row)->getValue(),
                    $worksheet->getCell('I' . $row)->getValue(),
                    $worksheet->getCell('J'. $row)->getValue()
                );
                if (!$exist){
                    $passenger->setNationalite($worksheet->getCell('A' . $row)->getValue());
                    $passenger->setNumero($worksheet->getCell('B' . $row)->getValue());
                    $passenger->setNom($worksheet->getCell('C' . $row)->getValue());
                    $passenger->setPrenom($worksheet->getCell('D' . $row)->getValue());
                    if ($worksheet->getCell('E' . $row)->getFormattedValue()!=""){
                        $datetime = new \DateTime();
                        $newDate = $datetime->createFromFormat('d/m/Y', $worksheet->getCell('E' . $row)->getFormattedValue());
                        $passenger->setDateNaissance($newDate);
                    }
                    $passenger->setSexe($worksheet->getCell('F' . $row)->getValue());
                    if ($worksheet->getCell('G' . $row)->getFormattedValue()!=""){
                        $datetime = new \DateTime();
                        $newDate = $datetime->createFromFormat('d/m/Y', $worksheet->getCell('G' . $row)->getFormattedValue());
                        $passenger->setDateVoyage($newDate);
                    }
                    $passenger->setTransport($worksheet->getCell('H' . $row)->getValue());
                    $passenger->setSens($worksheet->getCell('I' . $row)->getValue());
                    $passenger->setPosteFrontiere($worksheet->getCell('J'. $row)->getValue());
                    $em->persist($passenger);
                }
            }
            $em->flush();
            /** Add Log */
            $log['user'] = $user;
            $log['action'] = 'Import';
            $log['description'] = 'Import fichier PAF';
            $log['success'] = true;
            $actLog->addLog($log);
            /** endLog */
            if (file_exists($inputFileName)){
                unlink($inputFileName);
            }
            $this->addFlash('success','Import de donnée des passagers réussi');
            return $this->redirectToRoute('arssam_passenger_list');
        }
        $this->addFlash('error','pas de donnée importé');
        return $this->redirectToRoute('velirano_admin_homepage');
    }
    
    private function getFilename($request, $entity){
        if ($handle = opendir($request->server->get('DOCUMENT_ROOT').$request->getBasePath() . '/uploads/'.$entity.'/')) {
            $files = array();
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push($files, $entry);
                }
            }
            closedir($handle);
        }
        if (count($files)>0) {
            return $request->server->get('DOCUMENT_ROOT').$request->getBasePath() . '/uploads/'.$entity.'/'.$files[0];
        }else {
            return null;
        }
    }
}