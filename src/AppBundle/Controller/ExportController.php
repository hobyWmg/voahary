<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Intl\Intl;

/**
 * Export controller.
 *
 * @Route("adminarssam/export-xls")
 */
class ExportController extends Controller
{
    /**
     * @Route("/paf", name="arssam_exportcsv_paf")
     */
    public function companiesAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('ApiBundle:Company');
		$response = new StreamedResponse();
		$response->setCallback(function() use ($repository,$translator) {
			$handle = fopen('php://output', 'w+');
			$column = [];
			$column[] = $translator->trans('pomp.name');
			$column[] = $translator->trans('pomp.email');
			$column[] = $translator->trans('pomp.phone');
			$column[] = $translator->trans('pomp.locale');
			$column[] = $translator->trans('pomp.users');
			$column[] = $translator->trans('pomp.price');
			$column[] = $translator->trans('pomp.creation_date');
			fputcsv($handle, $column, ';');
			$results = $repository->findAll();
			foreach ($results as $company) {
				$line = [];
				$line[] = $company->getName();
				$line[] = $company->getEmail()." ".$company->getInvoiceEmail();
				$line[] = $company->getPhone();
				$line[] = $company->getLocale(); 
				$line[] = count($company->getUsers());
				$line[] = 'Diesel: '.$company->getPricePerLiter()->getDiesel().'€/l,Unleaded95: '.$company->getPricePerLiter()->getUnleaded95().'€/l,Unleaded98: '.$company->getPricePerLiter()->getUnleaded98().'€/l,'.(null !== $company->getUpdated()?\DateTime::createFromFormat(DATE_W3C, $company->getUpdated()->format(DATE_ATOM))->format('m/d/Y \a\t h:i A'):'');
				$line[] = \DateTime::createFromFormat(DATE_W3C, $company->getCreated()->format(DATE_ATOM))->format('m/d/Y \a\t h:i A');
				
				fputcsv( $handle,$line,';');
			}
			fclose($handle);
		});
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'text/csv; charset=utf-8');
		$response->headers->set('Content-Disposition','attachment; filename="export-companies.csv"');

		return $response;
	}

	/**
     * @Route("/passengers", name="arssam_exportxls_passenger")
     */
    public function exportPassengerAction(Request $request)
    {
		$nationalite = $request->request->get('fancy-checkbox-nationalite');
		$numero = $request->request->get('fancy-checkbox-numero');
		$nom = $request->request->get('fancy-checkbox-nom');
		$prenom = $request->request->get('fancy-checkbox-prenom');
		$naissance = $request->request->get('fancy-checkbox-naissance');
		$sexe = $request->request->get('fancy-checkbox-sexe');
		$voyage = $request->request->get('fancy-checkbox-voyage');
		$transport = $request->request->get('fancy-checkbox-transport');
		$sens = $request->request->get('fancy-checkbox-sens');
		$poste = $request->request->get('fancy-checkbox-poste');
		
		$dateDebNais = $request->request->get('dateDebNais');
		$dateFinNais = $request->request->get('dateFinNais');
		$dateDebVoyage = $request->request->get('dateDebVoyage');
		$dateFinVoyage = $request->request->get('dateFinVoyage');

        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('AppBundle:Passenger');
        $result = $repository->exportPassenger($nationalite, $numero, $nom, $prenom, $naissance, $sexe, $voyage, $transport, $sens, $poste, $dateDebNais, $dateFinNais, $dateDebVoyage, $dateFinVoyage);
        if ($result==-1){
            $this->get('session')->getFlashBag()->add('warning', "aucune colonne sélectionnée");
        } elseif ($result==-2){
            $this->get('session')->getFlashBag()->add('error', "Désolé, il y a une erreur!");
        } elseif (count($result)>0){

            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->getStyle('A3:J3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('80BDE5');
            $activeSheet->getStyle("A3:J3")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setSize('16');
            $activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $activeSheet->getRowDimension('3')->setRowHeight(30);
            $activeSheet->getStyle('A3:J3')->getAlignment()->setVertical('center');
            $activeSheet->mergeCells("A1:J1");
            $activeSheet->setCellValue("A1",'Statistiques des voyageurs');
            $activeSheet->setCellValue("A3", "Nationalité");
            $activeSheet->setCellValue("B3", "Numéro");
            $activeSheet->setCellValue("C3", "Nom");
            $activeSheet->setCellValue("D3", "Prénom");
            $activeSheet->setCellValue("E3", "Date de naissance");
            $activeSheet->setCellValue("F3", "Sexe");
            $activeSheet->setCellValue("G3", "Date du voyage");
            $activeSheet->setCellValue("H3", "Transport");
            $activeSheet->setCellValue("I3", "Sens");
            $activeSheet->setCellValue("J3", "Poste de frontière");
            $compteurLigne = 4;
            foreach ($result as $r){
                $dateNaissance = $r->getDateNaissance();
                if (is_null($dateNaissance)) {
                    $dateNaissance = "";
                } else{
                    $dateNaissance = $dateNaissance->format("d/m/Y");
                }
                $dateVoyage = $r->getDateVoyage();
                if (is_null($dateVoyage)) {
                    $dateVoyage = "";
                } else{
                    $dateVoyage = $dateVoyage->format("d/m/Y");
                }
                $activeSheet->getRowDimension($compteurLigne)->setRowHeight(30);
                $activeSheet->setCellValue("A".$compteurLigne, (is_null($nationalite)? "": $r->getNationalite()));
                $activeSheet->setCellValue("B".$compteurLigne, (is_null($numero)? "": $r->getNumero()));
                $activeSheet->setCellValue("C".$compteurLigne, (is_null($nom)? "": $r->getNom()));
                $activeSheet->setCellValue("D".$compteurLigne, (is_null($prenom)? "": $r->getPrenom()));
                $activeSheet->setCellValue("E".$compteurLigne, (is_null($naissance)? "": $dateNaissance));
                $activeSheet->setCellValue("F".$compteurLigne, (is_null($sexe)? "": $r->getSexe()));
                $activeSheet->setCellValue("G".$compteurLigne, (is_null($voyage)? "": $dateVoyage));
                $activeSheet->setCellValue("H".$compteurLigne, (is_null($transport)? "": $r->getTransport()));
                $activeSheet->setCellValue("I".$compteurLigne, (is_null($sens)? "": $r->getSens()));
                $activeSheet->setCellValue("J".$compteurLigne, (is_null($poste)? "": $r->getPosteFrontiere()));
                $compteurLigne++;
            }
            $activeSheet->getColumnDimension('A')->setAutoSize(true);
            $activeSheet->getColumnDimension('B')->setAutoSize(true);
            $activeSheet->getColumnDimension('C')->setAutoSize(true);
            $activeSheet->getColumnDimension('D')->setAutoSize(true);
            $activeSheet->getColumnDimension('E')->setAutoSize(true);
            $activeSheet->getColumnDimension('F')->setAutoSize(true);
            $activeSheet->getColumnDimension('G')->setAutoSize(true);
            $activeSheet->getColumnDimension('H')->setAutoSize(true);
            $activeSheet->getColumnDimension('I')->setAutoSize(true);
            $activeSheet->getColumnDimension('J')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);

            $fileName = 'export_passager'.'-'.date("d-m-Y").'.xls';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            $this->get('session')->getFlashBag()->add('warning', "aucun résultat");
        }
        return $this->redirect($this->generateUrl('arssam_passenger_list'));
	}

    /**
     * @Route("/gendarmerie", name="arssam_exportxls_gendarmerie")
     */
    public function exportGendarmerieAction(Request $request)
    {
        $numero = $request->request->get('fancy-checkbox-numero');
        $date = $request->request->get('fancy-checkbox-date');
        $heure = $request->request->get('fancy-checkbox-heure');
        $infraction = $request->request->get('fancy-checkbox-infraction');
        $suspect = $request->request->get('fancy-checkbox-suspect');

        $dateDeb = $request->request->get('dateDeb');
        $dateFin = $request->request->get('dateFin');

        $selInfraction = $request->request->get('selInfraction');
        $selVehicule = $request->request->get('selVehicule');

        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('AppBundle:Gta');
        $result = $repository->exportGendarmerie($numero, $date, $heure,$infraction, $suspect, $dateDeb, $dateFin, $selInfraction, $selVehicule);
        if ($result==-1){
            $this->get('session')->getFlashBag()->add('warning', "aucune colonne sélectionnée");
        } elseif ($result==-2){
            $this->get('session')->getFlashBag()->add('error', "Désolé, il y a une erreur!");
        } elseif (count($result)>0){

            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->getStyle('A3:E3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('80BDE5');
            $activeSheet->getStyle("A3:E3")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setSize('12');
            $activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $activeSheet->getRowDimension('3')->setRowHeight(30);
            $activeSheet->getStyle('A3:E3')->getAlignment()->setVertical('center');
            $activeSheet->mergeCells("A1:E1");
            $activeSheet->setCellValue("A1",'Rapport véhicules suspects et/ou ayant fait une infraction');
           
            $activeSheet->setCellValue("A3", "Numéro");
            $activeSheet->setCellValue("B3", "Date");
            $activeSheet->setCellValue("C3", "Heure");
            $activeSheet->setCellValue("D3", "Infractions");
            $activeSheet->setCellValue("E3", "Véhicule suspect");

            $compteurLigne = 4;
            foreach ($result as $r){
                $dateSave = $r->getDaty();
                if (is_null($dateSave)) {
                    $dateSave = "";
                } else{
                    $dateSave = $dateSave->format("d/m/Y");
                }
                $suspect = ($r->getSuspect())?"OUI":"NON";
                $activeSheet->getRowDimension($compteurLigne)->setRowHeight(30);
                $activeSheet->setCellValue("A".$compteurLigne, (is_null($numero)? "": $r->getNumPlaque()));
                $activeSheet->setCellValue("B".$compteurLigne, (is_null($date)? "": $dateSave));
                $activeSheet->setCellValue("C".$compteurLigne, (is_null($heure)? "": $r->getLera()));
                $activeSheet->setCellValue("D".$compteurLigne, (is_null($infraction)? "": $r->getInfractions()));
                $activeSheet->setCellValue("E".$compteurLigne, (is_null($suspect)? "":$suspect));
                $compteurLigne++;
            }
            $activeSheet->getColumnDimension('A')->setAutoSize(true);
            $activeSheet->getColumnDimension('B')->setAutoSize(true);
            $activeSheet->getColumnDimension('C')->setAutoSize(true);
            $activeSheet->getColumnDimension('D')->setAutoSize(true);
            $activeSheet->getColumnDimension('E')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);

            $fileName = 'export_gendarmerie'.'-'.date("d-m-Y").'.xls';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            $this->get('session')->getFlashBag()->add('warning', "aucun résultat");
        }
        return $this->redirect($this->generateUrl('arssam_gta_list'));
    }

    /**
     * @Route("/douane", name="arssam_exportxls_douane")
     */
    public function exportDouaneAction(Request $request)
    {
        $contrevenant = $request->request->get('fancy-checkbox-contrevenant');
        $numero = $request->request->get('fancy-checkbox-numero');
        $infraction = $request->request->get('fancy-checkbox-infraction');
        $caf = $request->request->get('fancy-checkbox-caf');
        $dcde = $request->request->get('fancy-checkbox-dcde');
        $situation = $request->request->get('fancy-checkbox-situation');
        $marchandises = $request->request->get('fancy-checkbox-marchandises');

        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('AppBundle:Dgd');
        $result = $repository->exportDouane($contrevenant, $numero, $infraction, $caf, $dcde, $situation, $marchandises);
        if ($result==-1){
            $this->get('session')->getFlashBag()->add('warning', "aucune colonne sélectionnée");
        } elseif (count($result)>0){

            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->getStyle('A3:G3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('80BDE5');
            $activeSheet->getStyle("A3:G3")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setSize('16');
            $activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $activeSheet->getRowDimension('3')->setRowHeight(30);
            $activeSheet->getStyle('A3:G3')->getAlignment()->setVertical('center');
            $activeSheet->mergeCells("A1:F1");
            $activeSheet->setCellValue("A1",'Dossier contentieux');
            // $activeSheet->getColumnDimension('C')->setWidth(30);
            // $activeSheet->getColumnDimension('F')->setWidth(30);
            // $activeSheet->getRowDimension('3')->setRowHeight(-1);
            $activeSheet->setCellValue("A3", "Contrevenant");
            $activeSheet->setCellValue("B3", "Numéro");
            $activeSheet->setCellValue("C3", "Infraction");
            $activeSheet->setCellValue("D3", "CAF");
            $activeSheet->setCellValue("E3", "DC / DE");
            $activeSheet->setCellValue("F3", "Situation");
            $activeSheet->setCellValue("G3", "Marchandises");

            $compteurLigne = 4;
            foreach ($result as $r){
                $activeSheet->getRowDimension($compteurLigne)->setRowHeight(30);
                $activeSheet->setCellValue("A".$compteurLigne, (is_null($contrevenant)? "": $r->getContrevenants()));
                $activeSheet->setCellValue("B".$compteurLigne, (is_null($numero)? "": $r->getNumero()));
                $activeSheet->setCellValue("C".$compteurLigne, (is_null($infraction)? "": $r->getInfraction()));
                $activeSheet->setCellValue("D".$compteurLigne, (is_null($caf)? "": $r->getValeurCaf()));
                $activeSheet->setCellValue("E".$compteurLigne, (is_null($dcde)? "": $r->getDcDe()));
                $activeSheet->setCellValue("F".$compteurLigne, (is_null($situation)? "": $r->getSituation()));
                $activeSheet->setCellValue("G".$compteurLigne, (is_null($marchandises)? "": $r->getMarchandises()));

                $compteurLigne++;
            }
            $activeSheet->getColumnDimension('A')->setAutoSize(true);
            $activeSheet->getColumnDimension('B')->setAutoSize(true);
            $activeSheet->getColumnDimension('C')->setAutoSize(true);
            $activeSheet->getColumnDimension('D')->setAutoSize(true);
            $activeSheet->getColumnDimension('E')->setAutoSize(true);
            $activeSheet->getColumnDimension('F')->setAutoSize(true);
            $activeSheet->getColumnDimension('G')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);

            $fileName = 'export_douane'.'-'.date("d-m-Y").'.xls';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            $this->get('session')->getFlashBag()->add('warning', "aucun résultat");
        }
        return $this->redirect($this->generateUrl('arssam_dgd_list'));
    }

    /**
     * @Route("/voyageur", name="arssam_exportxls_voyageur")
     */
    public function exportVoyageurAction(Request $request)
    {
        $dateDeb = $request->request->get('dateDeb');
        $dateFin = $request->request->get('dateFin');

        $selStatus = $request->request->get('selStatus');
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('AppBundle:Cis');
        $result = $repository->exportVoyageur($dateDeb, $dateFin, $selStatus);
        if ($result==-2){
            $this->get('session')->getFlashBag()->add('error', "Désolé, il y a une erreur!");
        } elseif (count($result)>0){

            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->getStyle('A3:F3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('80BDE5');
            $activeSheet->getStyle("A3:F3")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setSize('16');
            $activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $activeSheet->getRowDimension('3')->setRowHeight(30);
            $activeSheet->getStyle('A3:F3')->getAlignment()->setVertical('center');
            $activeSheet->mergeCells("A1:F1");
            $activeSheet->setCellValue("A1",'Rapport personnes suspects et réseaux de malfaiteurs');
            $activeSheet->setCellValue("A3", "Date de création");
            $activeSheet->setCellValue("B3", "Nom");
            $activeSheet->setCellValue("C3", "Prénom");
            $activeSheet->setCellValue("D3", "Autre information");
            $activeSheet->setCellValue("E3", "Réseaux");
            $activeSheet->setCellValue("F3", "Enquête en cours");

            $compteurLigne = 4;
            foreach ($result as $r){
                $activeSheet->getRowDimension($compteurLigne)->setRowHeight(30);
                $activeSheet->setCellValue("A".$compteurLigne, $r->getCreatedAt()->format("d/m/Y"));
                $activeSheet->setCellValue("B".$compteurLigne, $r->getNom());
                $activeSheet->setCellValue("C".$compteurLigne, $r->getPrenom());
                $activeSheet->setCellValue("D".$compteurLigne, $r->getAutre());
                $activeSheet->setCellValue("E".$compteurLigne, $r->getReseaux());
                $activeSheet->setCellValue("F".$compteurLigne, ($r->getStatus()==0)? "Non": "Oui");
                $compteurLigne++;
            }
            $activeSheet->getColumnDimension('A')->setAutoSize(true);
            $activeSheet->getColumnDimension('B')->setAutoSize(true);
            $activeSheet->getColumnDimension('C')->setAutoSize(true);
            $activeSheet->getColumnDimension('D')->setAutoSize(true);
            $activeSheet->getColumnDimension('E')->setAutoSize(true);
            $activeSheet->getColumnDimension('F')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);

            $fileName = 'export_voyageur'.'-'.date("d-m-Y").'.xls';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            $this->get('session')->getFlashBag()->add('warning', "aucun résultat");
        }
        return $this->redirect($this->generateUrl('arssam_cis_list'));
    }

    /**
     * @Route("/epidemie", name="arssam_exportxls_epidemie")
     */
    public function exportEpidemieAction(Request $request)
    {
        $dateDeb = $request->request->get('dateDeb');
        $dateFin = $request->request->get('dateFin');

        $selNiveau = $request->request->get('selNiveau');
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('AppBundle:Dvsse');
        $result = $repository->exportEpidemie($dateDeb, $dateFin, $selNiveau);
        if ($result==-2){
            $this->get('session')->getFlashBag()->add('error', "Désolé, il y a une erreur!");
        } elseif (count($result)>0){

            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->getStyle('A3:C3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('80BDE5');
            $activeSheet->getStyle("A3:C3")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setBold( true );
            $activeSheet->getStyle("A1")->getFont()->setSize('12');
            $activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $activeSheet->getRowDimension('3')->setRowHeight(30);
            $activeSheet->getStyle('A3:C3')->getAlignment()->setVertical('center');
            $activeSheet->mergeCells("A1:C1");
            $activeSheet->setCellValue("A1",'Information sur les épidémies');
           

            $activeSheet->setCellValue("A3", "Date de création");
            $activeSheet->setCellValue("B3", "Pathologies");
            $activeSheet->setCellValue("C3", "Pays");

            $compteurLigne = 4;
            $listePays = Intl::getRegionBundle()->getCountryNames();

            foreach ($result as $r){
                $activeSheet->getRowDimension($compteurLigne)->setRowHeight(30);
                $activeSheet->setCellValue("A".$compteurLigne, $r->getCreatedAt()->format("d/m/Y"));
                $activeSheet->setCellValue("B".$compteurLigne, $r->getInfo());
                $activeSheet->setCellValue("C".$compteurLigne, $listePays[$r->getPays()]);
                $compteurLigne++;
            }
            $activeSheet->getColumnDimension('A')->setAutoSize(true);
            $activeSheet->getColumnDimension('B')->setAutoSize(true);
            $activeSheet->getColumnDimension('C')->setAutoSize(true);
            $writer = new Xlsx($spreadsheet);

            $fileName = 'export_epidemie'.'-'.date("d-m-Y").'.xls';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } else {
            $this->get('session')->getFlashBag()->add('warning', "aucun résultat");
        }
        return $this->redirect($this->generateUrl('arssam_dvsse_list'));
    }
}
