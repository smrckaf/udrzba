<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 18.11.2018
 * Time: 19:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\KmenovaData;
use AppBundle\Entity\Stroj;
use AppBundle\Form\UpravitKmenovaDataType;
use AppBundle\Manager\UdrzbaManager;
use AppBundle\Form\UpravitStrojType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @Route("/stroj")
 */
class StrojController extends Controller
{
    /**
     * @var UdrzbaManager
     */
    private $udrzbaManager;

    /**
     * PracovnikController constructor.
     */
    public function __construct(UdrzbaManager $udrzbaManager)
    {
        $this->udrzbaManager = $udrzbaManager;
    }

    /**
     * @Route("/index", name="stroj-index")
     */
    public function indexAction()
    {
        $stroje = $this->udrzbaManager->getAllStroje();
        return $this->render("stroj/index.html.twig", [
            'stroje' => $stroje,
        ]);
    }

    /**
     * @Route("/upravit/{stroj}", name="stroj-upravit")
     */
    public function upravit(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Stroj $stroj = null)
    {
        if ($stroj === null)
            $stroj = new Stroj();
        $form = $formFactory->create(UpravitStrojType::class, $stroj);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $this->udrzbaManager->ulozitStroj($stroj);
            $flashBag->add('success', 'Stroj byl úspěšně přidán/upraven.');
            return $this->redirectToRoute('stroj-index');
        }
        return $this->render('stroj/upravit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/upravit-kmenova-data/{stroj}", name="stroj-upravit-kmenova-data")
     */
    public function upravitKmenovaData(FormFactoryInterface $formFactory, Request $request, FlashBagInterface $flashBag, Stroj $stroj)
    {
        $save_dir = $this->getParameter('kmenova_data_images_directory');

        //$kmenovaData = $this->udrzbaManager->getKmenovaDataByStroj($stroj);
        $kmenovaData = $stroj->getKmenovaData();
        if ($kmenovaData == null) {
            $kmenovaData = new KmenovaData();
            $stroj->setKmenovaData($kmenovaData);
            $origImage1 = null;
            $origImage2 = null;
            $origImage3 = null;
        }
        else {
            $origImage1 = $kmenovaData->getObrazek1();
            $origImage2 = $kmenovaData->getObrazek2();
            $origImage3 = $kmenovaData->getObrazek3();

            $kmenovaData->setObrazek1($kmenovaData->getObrazek1() != null ? new File($save_dir . '/' . $kmenovaData->getObrazek1()) : null);
            $kmenovaData->setObrazek2($kmenovaData->getObrazek2() != null ? new File($save_dir . '/' . $kmenovaData->getObrazek2()) : null);
            $kmenovaData->setObrazek3($kmenovaData->getObrazek3() != null ? new File($save_dir . '/' . $kmenovaData->getObrazek3()) : null);
        }

        $form = $formFactory->create(UpravitKmenovaDataType::class, $kmenovaData);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            //ukladani souboru
            $jePrvni = $kmenovaData->getObrazek1() !== null;
            $jeDruhy = $kmenovaData->getObrazek2() !== null;
            $jeTreti = $kmenovaData->getObrazek3() !== null;

            $file1 = $kmenovaData->getObrazek1();
            $file2 = $kmenovaData->getObrazek2();
            $file3 = $kmenovaData->getObrazek3();

            if ($jePrvni)
                $fileName1 = $this->generateUniqueFileName().'.'.$file1->guessExtension();

            if ($jeDruhy)
                $fileName2 = $this->generateUniqueFileName().'.'.$file2->guessExtension();

            if ($jeTreti)
                $fileName3 = $this->generateUniqueFileName().'.'.$file3->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                if ($jePrvni)
                    $file1->move($save_dir, $fileName1);
                if ($jeDruhy)
                    $file2->move($save_dir, $fileName2);
                if ($jeTreti)
                    $file3->move($save_dir, $fileName3);
            } catch (FileException $e) {
                $flashBag->add('danger', 'Nahrávání obrázků se nezdařilo. Kontaktujte správce. Chybová zpráva: ' . $e->getMessage());
            }

            if ($jePrvni)
                $kmenovaData->setObrazek1($fileName1);
            if ($jeDruhy)
                $kmenovaData->setObrazek2($fileName2);
            if ($jeTreti)
                $kmenovaData->setObrazek3($fileName3);

            if (!$file1 instanceof UploadedFile)
                $kmenovaData->setObrazek1($origImage1);
            if (!$file2 instanceof UploadedFile)
                $kmenovaData->setObrazek2($origImage2);
            if (!$file3 instanceof UploadedFile)
                $kmenovaData->setObrazek3($origImage3);

            //odstraneni starych obrazku, prepisujeme-li novymi
            if ($origImage1 !== null && $jePrvni)
                $this->removeFile($origImage1);
            if ($origImage2 !== null && $jeDruhy)
                $this->removeFile($origImage2);
            if ($origImage3 !== null && $jeTreti)
                $this->removeFile($origImage3);
            //ukladani souboru

            $this->udrzbaManager->ulozitKmenovaData($kmenovaData);
            $flashBag->add('success', 'Kmenová data byla úspěšně upravena.');
            return $this->redirectToRoute('stroj-index');
        }
        return $this->render('stroj/upravit-kmenova-data.html.twig', [
            'stroj' => $stroj,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/smazat/{stroj}", name="stroj-smazat")
     */
    public function smazat(Stroj $stroj, FlashBagInterface $flashBag)
    {
        $this->udrzbaManager->smazatStroj($stroj);
        $flashBag->add('success', 'Stroj byl úspěšně smazán.');

        return $this->redirectToRoute('stroj-index');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    public function removeFile($file)
    {
        $file_path = $this->getParameter('kmenova_data_images_directory').'/'.$file;
        if(file_exists($file_path)) unlink($file_path);
    }
}