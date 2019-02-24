<?php

namespace AppBundle\Controller;



use AppBundle\Entity\LogNotifikace;
use AppBundle\Entity\Pracovnik;
use AppBundle\Entity\Porucha;
use AppBundle\Entity\Pravidelnaudrzba;
use AppBundle\Entity\Prevzal;

use AppBundle\Entity\LogObsluhy;
use AppBundle\Entity\Stroj;
use AppBundle\Repository\PoruchaRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Utility;




/**
 * @Rest\Route("/api")
 */
class ApiController extends FOSRestController
{
    /**
     * @var Utility\Notifikace
     */
    private $notifikace;

    /**
     * ApiController constructor.
     */

    //pro posilani parametru notifikaci
    public function __construct(Utility\Notifikace $notifikace)
    {
        $this->notifikace = $notifikace;
    }

    /**
     * Tady nevím, jestli je tato metoda potřeba, možná bude stačit ta login, pak je možné to firebase id přesunout
     * až do loginu a ukládat to tam, tato metoda vytváří nové uživatele
     * @Rest\Post("/register")
     */
    public function registerAction(Request $request)
    {
        // screenshot pro postman - udrzba_02.png
        $content = $request->getContent();
        $data = json_decode($content, true);


        // tady by měly být kontroly, jestli požadované položky v tom requestu jsou
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        // firebase id
        // ...
        $token = random_int(1000, 2000);

        $encoder = $this->get('security.password_encoder');

        $user = new Pracovnik();
        $user->setLogin($username);
        $encodedPassword = $encoder->encodePassword($user, $password);
        $user->setHeslo($encodedPassword);
        $user->setEmail($email);
        $user->setJmeno(random_bytes(5));
        $user->setPrijmeni(random_bytes(5));
        $user->setHodsazba(1);
        $user->setRole('ROLE_API');
        $user->setToken($token);

        $em = $this->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        // pokud je zde potřeba vrátit token, tak takto, jinak není potřeba vracet žádnou odpověď
        return new JsonResponse(['token' => $token], 201);
    }

    /**
     * @Rest\Post("/login")
     */
    public function loginAction(Request $request)
    {
        // nastavení postman - udrzba_03.png
        $content = $request->getContent();
        $data = json_decode($content, true);

        $username = $data['_username'];
        $password = $data['_password'];

        $user = $this->get('doctrine')->getManager()->getRepository(Pracovnik::class)->findOneBy(['login' => $username]);

        if (!$user) {
            throw new ResourceNotFoundException('User not found');
        }

        // tady se ověřuje heslo ukládané šifrovaně, pokud to tak nebudete ukládát, je možné porovnat jen $password === $uset->getHeslo()
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        if (!$encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) {
            throw new AuthenticationException('Incorrect password');
        }

        return new JsonResponse(
            [
                'token' => $user->getToken(),
            ]
        );
    }

    /**
     * @Rest\Get("/pracovnik")
     */
    public function getSomethingHiddenAction()
    {
        // toto je endpoint /api/pracovnik, je pod auth, je tedy nutné posílat token, jinak vrací 401,
        // v hlavičce jako X-AUTH-TOKEN, screenshot postmana - udrzba_01.png

        // takto lze vytáhnout identitu a dále s ní pracovat (odpovídá instanci Pracovnik)
        $user = $this->getUser();

        // nejsnadnejsi zpusob jak vratit response, do json response vlozit pole dat, dá se to dělat také přes (jms) serializer
        // což je v dokumentaci k tomu fos rest api, ale pro tuto appku je to asi zbytečně komplikované a takto by
        // to mělo stačit
        return new JsonResponse([
            'hello' => 'there',
            'jmeno' => $user->getJmeno(),
            'prijmeni' => $user->getPrijmeni(),
        ]);
    }





    /**
     * @Rest\Put("/pracovnikold/{id}")
     */
    public function updateOldAction($id,Request $request)
    {
        $data = new Pracovnik();
        $idzarizeni = $request->get('idzarizeni');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Pracovnik')->find($id);




        if (empty($user)) {
            return new JsonResponse("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($idzarizeni)) {
            $user->setIdzarizeni($idzarizeni);
            $sn->flush();
            return new JsonResponse("User Updated Successfully", Response::HTTP_OK);
        }

        else return new JsonResponse("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Put("/logout")
     */
    // odstrani-updatuje se id zarzeni
    public function logoutAction(Request $request)
    {
        $data = new Pracovnik();


        //$content = $request->getContent();
        //$data = json_decode($content, true);

        //$idzarizeni = $data['_idzarizeni'];
//zjisi se cislo pracovnika z tokenu
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Pracovnik')->find($id_pracovnika);
        if (empty($user)) {
            return new JsonResponse("nenasel se uzivatel", Response::HTTP_NOT_FOUND);
        }
        else
            {
            $user->setIdzarizeni(NULL);
            $sn->flush();
            return new JsonResponse("idzarizeni bylo vynulovano-uzivatel je odhlasen", Response::HTTP_OK);
        }


    }


    /**
     * @Rest\Put("/pracovnik")
     */
    // poslani idzarizeni a tokenu, z tokenu se zjisti id uzivatet a u nej se updatuje idzarizeni
    public function updateAction(Request $request)
    {
        $data = new Pracovnik();


        $content = $request->getContent();
        $data = json_decode($content, true);

        $idzarizeni = $data['_idzarizeni'];

        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Pracovnik')->find($id_pracovnika);
        if (empty($user)) {
            return new JsonResponse("nenasel se uzivatel", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($idzarizeni)) {
            $user->setIdzarizeni($idzarizeni);
            $sn->flush();
            return new JsonResponse("idzarizeni Updated Successfully", Response::HTTP_OK);
        }

        else return new JsonResponse("idzarizeni cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }



    /**
     * @Rest\Get("/porucha")
     * Pro přístup ke všem poruchám
     */
    public function getPoruchaAction()
    {
        $poruchy = $this->get('doctrine')->getManager()->getRepository(Porucha::class)->findAll();

        $result = [];

        foreach ($poruchy as $porucha) {
            $result[] = [
                'id' => $porucha->getId(),
                'stroj' => $porucha->getStroj(),
                'casvzniku' => $porucha->getCasvzniku(),
                'oblastpriciny' => $porucha->getOblastpriciny(),
                'priorita' => $porucha->getPriorita(),
                'poznamka' => $porucha->getPoznamka(),
                'vyreseno' => $porucha->getVyreseno(),
            ];
        }

        return new JsonResponse($result);
    }






    /**
     * Pouze jedna porucha
     * GET na například /api/porucha/1
     * @Rest\Get("/porucha/{id}")
     * @Rest\QueryParam(name="id", requirements="\d+", description="ID poruchy.")
     * @param $id
     * @return JsonResponse
     */
    public function getSinglePorucha($id)
    {
        dump($id);
        $porucha = $this->get('doctrine')->getManager()->getRepository(Porucha::class)->find($id);

        if (!$porucha) {
            throw new ResourceNotFoundException('Porucha not found');
        }

        return new JsonResponse([
            'id' => $porucha->getId(),
            'stroj' => $porucha->getStroj(),
            'casvzniku' => $porucha->getCasvzniku(),
            'oblastpriciny' => $porucha->getOblastpriciny(),
            'priorita' => $porucha->getPriorita(),
            'poznamka' => $porucha->getPoznamka(),
            'vyreseno' => $porucha->getVyreseno(),
        ]);
    }



    /**
     * @Rest\Post("/prevzal/")
     */
    public function postPrevzalAction(Request $request)
    {
        $data = new Prevzal();
        //$id = $request->get('id');

        $id_poruchy = $request->get('id_poruchy');
        $id_pracovnika = $request->get('id_pracovnika');
        $prevzetidatcas = $request->get('prevzetidatcas');
        $role_obsluhy = $request->get(  'role_obsluhy');


        if(empty($id_poruchy) || empty($id_pracovnika) || empty($prevzetidatcas) || empty($role_obsluhy))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }
        //$id->setId($id);
        $data->setIdPoruchy($id_poruchy);
        $data->setIdPracovnika($id_pracovnika);
        $data->setPrevzetidatcas(new \DateTime($prevzetidatcas));
        $data->setRole_obsluhy($role_obsluhy);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);

        $em->flush();

        return new JsonResponse("Prevzeti poruchy bylo uspesne", Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/prevzal")
     * Pro přístup ke všem poruchám
     */
    public function getPrevzalAction()
    {
        $prevzeti = $this->get('doctrine')->getManager()->getRepository(Prevzal::class)->findAll();

        $result = [];

        foreach ($prevzeti as $prevzal) {
            $result[] = [
                'id' => $prevzal->getId(),
                'id_poruchy' => $prevzal->getIdPoruchy(),
                'id_pracovnika' => $prevzal->getIdPracovnika(),
                'prevzetidatcas' => $prevzal->getPrevzetidatcas(),
                'role_obsluhy' => $prevzal->getRole_obsluhy(),


            ];
        };

        return new JsonResponse($result);
    }

    /**
     * Pouze jedno prevzal
     * GET na například /api/prevzal/1
     * @Rest\Get("/prevzal/{id}")
     * @Rest\QueryParam(name="id", requirements="\d+", description="ID poruchy.")
     * @param $id
     * @return JsonResponse
     */
    public function getSinglePrevzal($id)
    {
        dump($id);
        $prevzal = $this->get('doctrine')->getManager()->getRepository(Prevzal::class)->find($id);

        if (!$prevzal) {
            throw new ResourceNotFoundException('Prevzal not found');
        }

        return new JsonResponse([
            'id' => $prevzal->getId(),
            '$id_poruchy' => $prevzal->getIdPoruchy(),
            '$id_pracovnika' => $prevzal->getIdPracovnika(),
            'prevzetidatcas' => $prevzal->getPrevzetidatcas(),
            '$role_obsluhy' => $prevzal->getRole_obsluhy(),
        ]);
    }


    /**
     * @Rest\Post("/porucha")
     */

    //zaslani poruchu pomoci api, pote ulozeni v tab porucha, zaslani notifikace a ulozeni notifikace ???
    public function PoruchaNotifikaceAction(Request $request)
    {


        $content = $request->getContent();
        $data = json_decode($content, true);
        $stroj = $data['_stroj'];
        $oblastpriciny = $data['_oblastpriciny'];
        $priorita = $data['_priorita'];
        $poznamka = $data['_poznamka'];

        if (empty($stroj) ||  empty($oblastpriciny) || empty($priorita) || empty($poznamka)) {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }

        //return new JsonResponse($stroj. " ". $oblastpriciny." ".$priorita." ".$poznamka.    "Poruchy Added Successfully", Response::HTTP_OK);

        //$id->setId($id);
        //Ulozeni poruchy do Porucha.php
        $data = new Porucha;
        $data->setStroj($stroj);
        $data->setCasvzniku(new \DateTime);
        $data->setOblastpriciny($oblastpriciny);
        $data->setPriorita($priorita);
        $data->setPoznamka($poznamka);


        $em = $this->getDoctrine()->getManager();
        $em->persist($data);

        $em->flush();
        $cisloporuchy = $data->getId();

        //nalezeni vsech pracovniku

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
                           
              ");

        $nalezene = $query->getResult();

        //
        //poslani notifikace vsem zamestnancum, pak opravit na skupinu stroje
        $datalognotifikace = new LogNotifikace;
        foreach ($nalezene as $d) {
            //jestli je pracovnik ve strojich
            foreach ($d->getStroje() as $stroj2) {
               if ($stroj2->getId() == (int)$stroj) {

                    $zarizeni = $d->getIdzarizeni();


                    //ulozeni do logu notifikace

                    $datalognotifikace->setIdzarizeni($zarizeni);
                    $datalognotifikace->setDatumcas(new \DateTime);
                    $datalognotifikace->setPorucha($cisloporuchy);
                    $datalognotifikace->setStroj($stroj);

                    $this->notifikace->posliAction('Stroj: ' . $stroj, 'Hlášení poruchy!', $zarizeni);
                    $em->persist($datalognotifikace);

                    $em->flush();
               }
            }


            return new JsonResponse("Poruchy Added Successfully", Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Post("/startopravyold")
     */
     // status stroje dat na na 0
    //zacatek opravy poslat 1, pokracovani opravy poslat 3
     public function StartoldAction(Request $request)
    {
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $typ = $data['_typ'];

        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();


        //return new JsonResponse($id_stroje." ".$id_poruchy."  ".$id_pracovnika. "Poslane hodnoty", Response::HTTP_NOT_ACCEPTABLE);





        $status=0; //stroj nepracuje, opravuje se

        if(empty($id_stroje)  || empty($id_poruchy) || empty($typ) ) //dodelat kontrolu statusu
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }
        //hledani v tabulce prevzal
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
                    ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();





        //Vlozeni noveho zaznamu do tabulky LogObsluhy
        $dataLogobsluhy->setIdprevzal($cisloprevzeti);
        $dataLogobsluhy->setStart(new \DateTime);
        $dataLogobsluhy->setTyp($typ);//1 start/ 3 pokracovani


        $em = $this->getDoctrine()->getManager();
        $em->persist($dataLogobsluhy);

        $em->flush();

        //update statusu stroje pracuje 1/nepracuje 0
        $sn = $this->getDoctrine()->getManager();
        $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);

        if (empty($stroj)) {
            return new JsonResponse("Nenasel se stroj", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($stroj)) {
            $stroj->setStatus($status);
            $sn->flush();
            return new JsonResponse("User Updated Successfully", Response::HTTP_OK);
        }

        else return new JsonResponse("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
        return new JsonResponse($output."  Prevzeti LoguObsluhy bylo uspesne", Response::HTTP_OK);
    }


    /**
     * @Rest\Put("/stopopravyOld")
     */
    //preruseni opravy typ 3 nebo ukonceni opravy typ 4
    // status 0 stroj nepracuje 1 stroj pracuje

    public function StopOldAction(Request $request)
    {
        $dataL = new LogObsluhy();
        $dataS = new Stroj();
        //$id_stroje = $request->get('id_stroje');
        //$id_poruchy = $request->get('id_poruchy');
        //$id_pracovnika = $request->get('id_pracovnika');
        //$typ = $request->get('typ'); // S nebo P


        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        //$typ = $data['_typ'];   //3 preruseni  4 konec
        //
        /// /3 preruseni, 4 ukonceni
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje


        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();


        //return new JsonResponse($id_stroje." ".$id_poruchy."  ".$id_pracovnika. "Poslane hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        $status=0; //stroj nepracuje, opravuje se

        if(empty($id_stroje) ||empty($id_pracovnika) || empty($id_poruchy) ) //dodelat kontrolu statusu
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }

        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));

        //$prevzal = $query->getResult();

        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $output = $prevzal->getId();
        //return new JsonResponse($output."  nalezeni idPrevzal", Response::HTTP_OK);



        //najdu v LoguObsluhy id, kde je prazdny zaznam konec v LogObsluhy se správnym id_prevzal
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
           "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.idprevzal= :idpor AND l.konec is NULL")
            ->setParameter('idpor',$output);
        $LogObsluhy = $query->setMaxResults(1)->getOneOrNullResult();
       if (empty($LogObsluhy))
       {
           return new JsonResponse("  udaj pro update neni prazdny", Response::HTTP_OK);
       }
           $idLogobsluhy = $LogObsluhy->getId();
        $typ = $LogObsluhy->getTyp();

        //return new JsonResponse("typ ".$typ." idlogobsl ".$idLogobsluhy."   udaj pro update neni prazdny", Response::HTTP_OK);



        //update konec a typ do LogObsluhy

        $sn = $this->getDoctrine()->getManager();
        $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
        $logobsluhy->setKonec(new \DateTime);

       if ($typ=1)
           $logobsluhy -> setTyp(4); // 2 Konec
        if ($typ=2) $logobsluhy -> setTyp(3); // typ 1  začátek práce, 2 	začátek přerušení, 3 konec přerušení, 4 konec práce

        $sn->flush();
return new JsonResponse("  Update LoguObsluhy bylo uspesne", Response::HTTP_OK);
    }



    /**
     * @Rest\Post("/prevzateporuchy")
     */
    //zacatek opravy typ S stroj stoji nebo pokracovani opravy typ P
    // status stroje dat na na 0

    public function pokusAction(Request $request)
    {

//hledani id pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');

        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id = $nalezene->getId();//ok

        //vypis vsech prevzatych z tabulky prevzal a porucha

        //$prevzal = $this->get('doctrine')->getManager()->getRepository(Prevzal::class)->findBy(array('idPracovnika' => $id));

        //$result = [];
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p, r
              FROM AppBundle:Prevzal p
              JOIN p.idPoruchy r
              WHERE p.idPracovnika= :pracovnik")
            ->setParameter('pracovnik',1);

        // ->setParameter("autori",$autori);

               $data = $query->getResult();
        $result = [];

       foreach ($data as $d) {
            $result[] = [
                'idprevzal' => $d->getId(),
                'idporuchy' => $d->getIdPoruchy()->getId(),
                'stroj' => $d->getIdPoruchy()->getStroj(),
                'casvzniku' => $d->getIdPoruchy()->getCasvzniku(),
                'oblastpriciny' => $d->getIdPoruchy()->getOblastpriciny(),
                'priorita' => $d->getIdPoruchy()->getPriorita(),
                'poznamka' => $d->getIdPoruchy()->getPoznamka(),
                'vyreseno' => $d->getIdPoruchy()->getVyreseno(),
                'idpracovnika' => $d->getIdPracovnika(),
                'prevzeticas' => $d->getPrevzetidatcas(),

            ];
        }

        return new JsonResponse($result);
    }


    /**
     * @Rest\Post("/prevzetiporuchy")
     */
    //prevzeti pomocí dat poslanych v aplikaci json
    public function postPrevzal2Action(Request $request)
    {

        //$id = $request->get('id');
        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_poruchy = $data['_id_poruchy'];
        $role_obsluhy = $data['_roleobsluhy'];
        //return new JsonResponse($id_poruchy."".$role_obsluhy. "Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);


        //hledani id pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');

        if(empty($id_poruchy) ||  empty($role_obsluhy))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }




        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene;//ok

       //return new JsonResponse($id_pracovnika."   ".$id_poruchy."  ".$role_obsluhy. "Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);




        $data = new Prevzal();


        $data->setIdPracovnika($id_pracovnika);
        $data->setIdPoruchy($em->find(Porucha::class, $id_poruchy));
        $data->setPrevzetidatcas(new \DateTime);
        $data->setRole_obsluhy($role_obsluhy);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);

        $em->flush();

        return new JsonResponse("Prevzeti poruchy bylo uspesne", Response::HTTP_OK);

    }

    /**
     * @Rest\Get("/poslilogobsluhy")
     * Pro přístup ke všem poruchám
     */
    public function getLogObsluhyAction()
    {
        $logobsluhy = $this->get('doctrine')->getManager()->getRepository(LogObsluhy::class)->findAll();

        $result = [];

        foreach ($logobsluhy as $log) {
            $result[] = [
                'id' => $log->getId(),
                'idprevzal' => $log->getIdprevzal(),
                'start' => $log->getStart(),
                'konec' => $log->getKonec(),
                'typ' => $log->getTyp(),

            ];
        }

        return new JsonResponse($result);
    }

    /**
     * @Rest\Post("/poslijedenlogobsluhy")
     * Pro přístup ke všem poruchám
     */
    public function JedenLogObsluhyAction(Request $request)
    {
        $content = $request->getContent();
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_poruchy = $data['_id_poruchy'];

                //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        if(empty($id_poruchy))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }


        //hledani v tabulce prevzal
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));

        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();

        $logobsluhy = $this->get('doctrine')->getManager()->getRepository(LogObsluhy::class)->findBy(
            array('prevzal' => $cisloprevzeti));
        //return new JsonResponse( $id_pracovnika." testy ".$cisloprevzeti, Response::HTTP_NOT_ACCEPTABLE);

       // $logobsluhy = $this->get('doctrine')->getManager()->getRepository(LogObsluhy::class)->findAll();
        $result = [];

        foreach ($logobsluhy as $log) {
            $result[] = [
                'id' => $log->getId(),
                'idprevzal' => $log->getIdprevzal(),
                'start' => $log->getStart(),
                'konec' => $log->getKonec(),
                'typ' => $log->getTyp(),

            ];
        }

        return new JsonResponse($result);
    }




    /**
     * @Rest\Post("/startopravy")
     */

    // status stroje dat na na 0
    //zacatek opravy poslat 1, pokracovani opravy poslat 3
    public function StartAction(Request $request)
    {
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje


        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();


        //return new JsonResponse($id_stroje." ".$id_poruchy."  ".$id_pracovnika. "Poslane hodnoty", Response::HTTP_NOT_ACCEPTABLE);







        if(empty($id_stroje)  || empty($id_poruchy) || empty($status)) //dodelat kontrolu statusu
        {
            return new JsonResponse("Posílátye prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }
        //hledani v tabulce prevzal
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();


//hledani pro dané čísloprevzetí 1 v tabulce logObsluhy

        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.idprevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$cisloprevzeti,'typ'=>1 ));

        $nalezeno = $query2->setMaxResults(1)->getOneOrNullResult();


        if ($nalezeno==NULL)
        {
            //return new JsonResponse($cisloprevzeti. "  jsem V nenalezeno 1", Response::HTTP_OK);

            $dataLogobsluhy->setIdprevzal($cisloprevzeti);
            $dataLogobsluhy->setStart(new \DateTime);
            $dataLogobsluhy->setTyp(1);//1 start/ 3 pokracovani
        }
        else
        {
            $dataLogobsluhy->setIdprevzal($cisloprevzeti);
            $dataLogobsluhy->setStart(new \DateTime);
            $dataLogobsluhy->setTyp(2);//1 start/ 3 pokracovani
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($dataLogobsluhy);

        $em->flush();
        //$cisloprevzeti = $nalezeno->getId();



        //Vlozeni noveho zaznamu do tabulky LogObsluhy




        //update statusu stroje pracuje 1/nepracuje 0
        $sn = $this->getDoctrine()->getManager();
        $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);

         //stroj nepracuje, opravuje se
        $stroj->setStatus($status);
        $sn->flush();

        return new JsonResponse("  Prevzeti LoguObsluhy bylo uspesne", Response::HTTP_OK);
    }





    /**
     * @Rest\Put("/stopopravy")
     */
    //typ 1  začátek práce, 3 	stop pokracovani, 2 start pokracuj 4 stop konec práce
    // status 0 stroj nepracuje 1 stroj pracuje

    public function StopAction(Request $request)
    {
        $dataL = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje
        $poznamka = $data['_poznamka'];


        if(empty($id_stroje) || empty($id_poruchy) || $status <'0' || $status >'1' )
        {
            return new JsonResponse("Posilate prazdne hodnoty nebo spatny status (0,1) ", Response::HTTP_NOT_ACCEPTABLE);
        }
        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        // hleda cisloprevzeti z tabulky Prevzal na yaklade id pracovnika a cisla poruchy
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();



        //hledani pro dané čísloprevzetí typ 2 v tabulce logObsluhy
        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.idprevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$cisloprevzeti,'typ'=>2 ));
        $nalezeno2 = $query2->setMaxResults(1)->getOneOrNullResult();

        //hledani pro dané čísloprevzetí typ 1 v tabulce logObsluhy
        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.idprevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$cisloprevzeti,'typ'=>1 ));
        $nalezeno1 = $query2->setMaxResults(1)->getOneOrNullResult();






        $vysledek='nedoslo k update, data byla jiz zadana';

        if ($nalezeno2!=NULL)
        {
            //update sloupce konec tabulky logobsluhy
            $idLogobsluhy = $nalezeno2->getId();
            $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
            $logobsluhy->setKonec(new \DateTime);
            $logobsluhy->setTyp(3);
            $vysledek='typ 3';
        }

        //hledani pro dané cisloprevzetí typ 1 v tabulce logObsluhy

        if ($nalezeno1!=NULL and $nalezeno2==NULL)
        {
            //update sloupce konec tabulky logobsluhy toto je akce Konec
            $idLogobsluhy = $nalezeno1->getId();
            $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
            $logobsluhy->setKonec(new \DateTime);
            $logobsluhy->setTyp(4);

            $logobsluhy->setPoznamka($poznamka);

            $vysledek='typ 4';
            //zapis vzreseni ke stroji
            $porucha = $this->getDoctrine()->getRepository('AppBundle:Porucha')->find($id_poruchy);
            $porucha->setVyreseno(new \DateTime);

       }

       // $em = $this->getDoctrine()->getManager();
        //$em->persist($dataLogobsluhy);
        //$em->flush();


        //update statusu stroje pracuje 1/nepracuje 0 v tabulce stroj, tato promenna prijde z rest api
        $sn = $this->getDoctrine()->getManager();
        $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);
        $stroj->setStatus($status);
        $sn->flush();
        return new JsonResponse("  Vysledek je: ".$vysledek, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/zobrazudrzbu")
     * zobrazi vsechny pravidelne udrzby
     */
    public function getZobrazUdrzbuAction()
    {

        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p,s FROM AppBundle:Pravidelnaudrzba p join p.idStroje s");
        $result = [];


            //    ->setParameter("autori",$autori);

               $data = $query->getResult();
               foreach ($data as $d) {

                   $result[] = ['id' => $d->getId(),
                                //'idstroje' => $d->getIdStroje(),
                              'nazevstroje' => $d->getIdStroje()->getNazev(),
                                'datum' => $d->getDatumUdrzby(),
                                'popis' => $d->getPopisUdrzby(),
                                'provedl' => ($d->getProvedl() != null ? $d->getProvedl()->getJmeno() : null),
                    ];
                }

        return new JsonResponse($result);

    }

    /**
     * @Rest\Get("/neprijateporuchy2pokus")
     * posle vsechny neprijate poruchy danym pracovnikem
     */
    public function getNeprijatePoruchypokusAction(Request $request)
    {


        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        //return new JsonResponse($id_pracovnika. "Test idpracovnika", Response::HTTP_NOT_ACCEPTABLE);

        $em = $this->getDoctrine()->getManager();
        $query=$em->createQueryBuilder()->select ('p')
                                -> from (Porucha::class,'p');


           // "SELECT p, l  FROM AppBundle:Porucha p LEFT JOIN  p.prevzate l WHERE l.idPoruchy= :podm")
           // ->setParameter('podm',8);



        $data = $query->getQuery()->getResult();
//return new JsonResponse( "Test idpracovnika", Response::HTTP_NOT_ACCEPTABLE);
        $result = [];

                foreach ($data as $porucha) {

                    $result[] = [

                        'id' => $porucha->getId(),
                        'stroj' => $porucha->getStroj(),
                        'casvzniku' => $porucha->getCasvzniku(),
                        'oblastpriciny' => $porucha->getOblastpriciny(),
                        'priorita' => $porucha->getPriorita(),
                        'poznamka' => $porucha->getPoznamka(),

                        ];

                        foreach ( $porucha->getPrevzate() as $prevzal)
                        {
                            $result[] = [
                            'prevzal'=>$prevzal->getId(),
                            'prevzal2'=>$prevzal->getPrevzetidatcas(),
                            ];
                        }

                        //'vyreseno' => $porucha->getVyreseno(),


                }


        return new JsonResponse($result);

    }




    /**
     * @Rest\Get("/neprijateporuchy")
     * posle vsechny neprijate poruchy danym pracovnikem
     */
    public function getNeprijatePoruchyAction(Request $request)
    {


        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        //return new JsonResponse($id_pracovnika. "Test idpracovnika", Response::HTTP_NOT_ACCEPTABLE);

        $sn = $this->getDoctrine()->getManager();

        //$prevzal = $this->getDoctrine()->getRepository('AppBundle:Prevzal')->findBy([
         // 'idPracovnika'=> $id_pracovnika

        //]);
        $prevzal = $this->getDoctrine()->getRepository('AppBundle:Prevzal')->findBy([
            'idPracovnika'=> $id_pracovnika

        ]);
        $poruchy = $this->getDoctrine()->getRepository('AppBundle:Porucha')->findAll();
        $poleprevzate = [];
        foreach ($prevzal as $prevzate)
            {
                $poleprevzate[] = $prevzate->GetIdPoruchy();
            }

        $result = [];

        foreach ($poruchy as $porucha) {

            if (!in_array($porucha, $poleprevzate)) {
                $result[] = [

                    'id' => $porucha->getId(),
                    'stroj' => $porucha->getStroj(),
                    'casvzniku' => $porucha->getCasvzniku(),
                    'oblastpriciny' => $porucha->getOblastpriciny(),
                    'priorita' => $porucha->getPriorita(),
                    'poznamka' => $porucha->getPoznamka(),

                ];
            }

        }

            //'vyreseno' => $porucha->getVyreseno(),





        return new JsonResponse($result);

    }



    /**
     * @Rest\Get("/ukonceneporuchy")
     * pro konkrétního údržbáře pošle  ukončené poruchy (mají logobsluhy se 4)
     */
    public function getUkoncenePoruchypokusAction(Request $request)
    {
    $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        //return new JsonResponse($id_pracovnika. "Test idpracovnika", Response::HTTP_NOT_ACCEPTABLE);



        $prevzal = $this->getDoctrine()->getRepository('AppBundle:Prevzal')->findBy([
            'idPracovnika'=> $id_pracovnika ]);

        $poruchy = $this->getDoctrine()->getRepository('AppBundle:Porucha')->findAll();

        $poruchy = $this->getDoctrine()->getManager()->createQuery("SELECT p FROM " . Porucha::class . " p
        JOIN p.prevzate prev 
            WHERE prev.idPracovnika = :idPracovnika AND p.vyreseno IS NOT NULL")
            ->setParameters(
                ["idPracovnika" => $id_pracovnika]
            )->getResult();

        $response = [];
        foreach($poruchy as $porucha){
            $response[] = [
                'id' => $porucha->getId(),
                'stroj' => $porucha->getStroj(),
                'casvzniku' => $porucha->getCasvzniku(),
                'oblastpriciny' => $porucha->getOblastpriciny(),
                'priorita' => $porucha->getPriorita(),
                'poznamka' => $porucha->getPoznamka(),
                'vyreseno' => $porucha->getVyreseno(),
            ];
        }
        return new JsonResponse($response);


      /*  foreach ($prevzal as $prevzate)
            {
                $poruchaid = $prevzate->getIdPoruchy()->getId();

                return new JsonResponse($poruchaid. "porucha id", Response::HTTP_NOT_ACCEPTABLE);

$result = [];
foreach ($poruchy as $porucha) {

    //najdu v logu obsluhy ten spravny log se 4


    if ($porucha->getVyreseno() != NULL   and $porucha->getId() == $poruchaid) {
        $result[] = [

            'id' => $porucha->getId(),
            'stroj' => $porucha->getStroj(),
            'casvzniku' => $porucha->getCasvzniku(),
            'oblastpriciny' => $porucha->getOblastpriciny(),
            'priorita' => $porucha->getPriorita(),
            'poznamka' => $porucha->getPoznamka(),
            'vyreseno' => $porucha->getVyreseno(),
        ];
    }
}
}
//'vyreseno' => $porucha->getVyreseno(),
return new JsonResponse($result);*/

    }

    /**
     * @Rest\Get("/neukonceneporuchy")
     * pro konkrétního údržbáře pošle  ukončené poruchy (mají logobsluhy se 4)
     */
    public function getNeukoncenePoruchypokusAction(Request $request)
    {
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        //return new JsonResponse($id_pracovnika. "Test idpracovnika", Response::HTTP_NOT_ACCEPTABLE);



        $prevzal = $this->getDoctrine()->getRepository('AppBundle:Prevzal')->findBy([
            'idPracovnika'=> $id_pracovnika ]);

        $poruchy = $this->getDoctrine()->getRepository('AppBundle:Porucha')->findAll();

        $poruchy = $this->getDoctrine()->getManager()->createQuery("SELECT p FROM " . Porucha::class . " p
        JOIN p.prevzate prev 
            WHERE prev.idPracovnika = :idPracovnika AND p.vyreseno IS NULL")
            ->setParameters(
                ["idPracovnika" => $id_pracovnika]
            )->getResult();

        $response = [];
        foreach($poruchy as $porucha){
            $response[] = [
                'id' => $porucha->getId(),
                'stroj' => $porucha->getStroj(),
                'casvzniku' => $porucha->getCasvzniku(),
                'oblastpriciny' => $porucha->getOblastpriciny(),
                'priorita' => $porucha->getPriorita(),
                'poznamka' => $porucha->getPoznamka(),
                'vyreseno' => $porucha->getVyreseno(),
            ];
        }
        return new JsonResponse($response);


        /*  foreach ($prevzal as $prevzate)
              {
                  $poruchaid = $prevzate->getIdPoruchy()->getId();

                  return new JsonResponse($poruchaid. "porucha id", Response::HTTP_NOT_ACCEPTABLE);

  $result = [];
  foreach ($poruchy as $porucha) {

      //najdu v logu obsluhy ten spravny log se 4


      if ($porucha->getVyreseno() != NULL   and $porucha->getId() == $poruchaid) {
          $result[] = [

              'id' => $porucha->getId(),
              'stroj' => $porucha->getStroj(),
              'casvzniku' => $porucha->getCasvzniku(),
              'oblastpriciny' => $porucha->getOblastpriciny(),
              'priorita' => $porucha->getPriorita(),
              'poznamka' => $porucha->getPoznamka(),
              'vyreseno' => $porucha->getVyreseno(),
          ];
      }
  }
  }
  //'vyreseno' => $porucha->getVyreseno(),
  return new JsonResponse($result);*/

    }
/**
* @Rest\Get("/neprovedenaudrzba")
* posle neprovedenou pravidelnou udrzbu
*/
    public function getNeprovedenaUdrzbaAction()
    {
        $udrzba = $this->get('doctrine')->getManager()->getRepository(Pravidelnaudrzba::class)->findBy([
            'provedeni'=> NULL ]);

        $result = [];

        foreach ($udrzba as $item) {
            $result[] = [
                'id' => $item->getId(),
                'idstroje' => $item->getIdStroje()->getId(),
                'datumudrzby' => $item->getDatumUdrzbyod(),
                'popis' => $item->getPopisUdrzby(),
                'provedeni'=>$item->getProvedeni(),
                'poznudrzbare'=>$item->getPoznUdrzbare(),


            ];
        }

        return new JsonResponse($result);
    }

    /**
     * @Rest\Get("/provedenaudrzba2")
     * posle neprovedenou pravidelnou udrzbu
     */
    public function getProvedenaUdrzbaAction()
    {

        $udrzby = $this->getDoctrine()->getManager()->createQuery("SELECT p FROM " . Pravidelnaudrzba::class . " p
        
            WHERE p.provedeni IS NOT NULL")

            ->getResult();

        $response = [];
        foreach($udrzby as $item){
            $response[] = [
                'id' => $item->getId(),
                'idstroje' => $item->getIdStroje()->getId(),
                'datumudrzby' => $item->getDatumUdrzbydo(),
                'popis' => $item->getPopisUdrzby(),
                'provedeni'=>$item->getProvedeni(),
                'poznudrzbare'=>$item->getPoznUdrzbare(),

            ];
        }
        return new JsonResponse($response);

        foreach ($result as $item) {
            $vysledek = [


            ];
        }

        return new JsonResponse($vysledek);
    }



    /**
     * @Rest\Put("/zadejpravidelnouudrzbu")
     */
    //zada se splneni pravidelne udrzby

    public function ZadejpravidelnoudrzbuAction(Request $request)
    {



        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_udrzby = $data['_idudrzby'];
        $poznamka = $data['_poznamka'];


        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene;


       //return new JsonResponse($id_pracovnika." ".$id_udrzby. "Poslane hodnoty", Response::HTTP_NOT_ACCEPTABLE);


        if(empty($id_udrzby))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }




        //update pravidelne udrzby

        $sn = $this->getDoctrine()->getManager();
        $pravidelnaudrzba = $this->getDoctrine()->getRepository('AppBundle:Pravidelnaudrzba')->find($id_udrzby);
        $pravidelnaudrzba->setProvedl($id_pracovnika );
        $pravidelnaudrzba->setProvedeni(new \DateTime);
        $pravidelnaudrzba->setPoznUdrzbare($poznamka);
        $sn->flush();
        return new JsonResponse("  Update Pravidelne udrzby byl uspesny", Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/ukonceneporuchy4")
     * pro konkrétního údržbáře pošle  ukončené poruchy (mají logobsluhy se 4)
     */
    public function getUkoncenePoruchy4pokusAction(Request $request)
    {
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        //return new JsonResponse($id_pracovnika. "Test idpracovnika", Response::HTTP_NOT_ACCEPTABLE);



        //$porucha = $this->getDoctrine()->getRepository('AppBundle:Prevzal')->findBy([ 'idPracovnika'=> $id_pracovnika ]);

        /** @var PoruchaRepository $repistory */
        $repistory = $this->getDoctrine()->getRepository('AppBundle:Porucha');
        $poruchy = $repistory->findByVyreseneByPracovnikAndTyp($id_pracovnika, 4);

        /*$poruchy = $this->getDoctrine()->getManager()->createQuery("SELECT p FROM " . Porucha::class . " p
        JOIN p.prevzate prev 
            WHERE prev.idPracovnika = :idPracovnika AND p.vyreseno IS NOT NULL")
            ->setParameters(
                ["idPracovnika" => $id_pracovnika]
            )->getResult();
        */
        $response = [];
        foreach($poruchy as $porucha){
            $response[] = [
                'id' => $porucha->getId(),
                'stroj' => $porucha->getStroj(),
                'casvzniku' => $porucha->getCasvzniku(),
                'oblastpriciny' => $porucha->getOblastpriciny(),
                'priorita' => $porucha->getPriorita(),
                'poznamka' => $porucha->getPoznamka(),
                'vyreseno' => $porucha->getVyreseno(),
            ];
        }
        return new JsonResponse($response);


        /*  foreach ($prevzal as $prevzate)
              {
                  $poruchaid = $prevzate->getIdPoruchy()->getId();

                  return new JsonResponse($poruchaid. "porucha id", Response::HTTP_NOT_ACCEPTABLE);

  $result = [];
  foreach ($poruchy as $porucha) {

      //najdu v logu obsluhy ten spravny log se 4


      if ($porucha->getVyreseno() != NULL   and $porucha->getId() == $poruchaid) {
          $result[] = [

              'id' => $porucha->getId(),
              'stroj' => $porucha->getStroj(),
              'casvzniku' => $porucha->getCasvzniku(),
              'oblastpriciny' => $porucha->getOblastpriciny(),
              'priorita' => $porucha->getPriorita(),
              'poznamka' => $porucha->getPoznamka(),
              'vyreseno' => $porucha->getVyreseno(),
          ];
      }
  }
  }
  //'vyreseno' => $porucha->getVyreseno(),
  return new JsonResponse($result);*/

    }


    /**
     * @Rest\Post("/startopravy2")
     */

    // status stroje dat na 0
    //zacatek opravy poslat 1, pokracovani opravy poslat 3
    public function Start2Action(Request $request)
    {
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        //$status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje


        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        if(empty($id_stroje)  || empty($id_poruchy) ) //dodelat kontrolu statusu
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }



        //hledani v tabulce prevzal
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();




//hledani pro dané čísloprevzetí 1 v tabulce logObsluhy

        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$prevzal,'typ'=>1 ));
        $nalezeno = $query2->setMaxResults(1)->getOneOrNullResult();

        if ($nalezeno==NULL) //1 nenalezena
        {
            $query2=$em->createQuery(
                "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
                ->setParameters(array('prev'=>$prevzal,'typ'=>4 ));
            $nalezeno2 = $query2->setMaxResults(1)->getOneOrNullResult();

            if ($nalezeno2==NULL)       //4 nenalezena
            {
                // neni 1 ani 4 vlozime radek s 1
                $dataLogobsluhy->setIdprevzal($prevzal);
                $dataLogobsluhy->setStart(new \DateTime);
                $dataLogobsluhy->setTyp(1);//1 start
                $vysledek = 'Začátek opravy poruchy vložen.';

                $em = $this->getDoctrine()->getManager();
                $em->persist($dataLogobsluhy);
                $em->flush();
            }
            else //je 4 nalezena
            {
                $vysledek = 'Začátek opravy poruchy neproběhl. Porucha je již ukončena.';
            }
        }
        else //1 je nalezena, hledame 2
        {
            //udelat zmenu z 2 na 3
            $query2 = $em->createQuery(
                "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
                ->setParameters(array('prev' => $prevzal, 'typ' => 2));
            $nalezeno2 = $query2->setMaxResults(1)->getOneOrNullResult();

            if ($nalezeno2!=NULL) //2 nalezena
            {

                //update sloupce konec tabulky logobsluhy toto je akce Konec
                $idLogobsluhy = $nalezeno2->getId();
                $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
                $logobsluhy->setKonec(new \DateTime);
                $logobsluhy->setTyp(3);
                $vysledek='Pokračování opravy poruchy.';
                $em->flush();

            }
            else {
                $vysledek = 'Pokračování opravy poruchy neproběhlo. Oprava poruchy nebyla přerušena.';
            }
        }

        //Vlozeni noveho zaznamu do tabulky LogObsluhy


        $status=0;

        //update statusu stroje pracuje 1/nepracuje 0
        $sn = $this->getDoctrine()->getManager();
        $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);

        //stroj nepracuje, opravuje se
        $stroj->setStatus($status);
        $sn->flush();

        return new JsonResponse("Log Obsluhy: ".$vysledek, Response::HTTP_OK);
    }


    /**
     * @Rest\Put("/konecopravy2")
     */
    //typ 1  začátek práce, 3 	stop pokracovani, 2 start pokracuj 4 stop konec práce
    // status 0 stroj nepracuje 1 stroj pracuje

    public function Konec2Action(Request $request)
    {
        $dataL = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje
        $poznamka = $data['_poznamka'];


        if(empty($id_stroje) || empty($id_poruchy) || $status <'0' || $status >'1' )
        {
            return new JsonResponse("Posilate prazdne hodnoty nebo spatny status (0,1) ", Response::HTTP_NOT_ACCEPTABLE);
        }
        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');



        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();


        // hleda cisloprevzeti z tabulky Prevzal na zaklade id pracovnika a cisla poruchy
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();



//hledani pro dané čísloprevzetí typ 2 v tabulce logObsluhy
        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$cisloprevzeti,'typ'=>2 ));
        $nalezeno2 = $query2->setMaxResults(1)->getOneOrNullResult();



        $vysledek='';
        if ($nalezeno2==NULL) //2 nenalezena
            {
            //oprava neni prerusena, hledame 1

            $query2 = $em->createQuery(
                "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
                ->setParameters(array('prev' => $cisloprevzeti, 'typ' => 1));


            $nalezeno1 = $query2->setMaxResults(1)->getOneOrNullResult();

            if ($nalezeno1!=NULL) //1 nalezena, probehne update na 4
            {


                //update sloupce konec tabulky logobsluhy toto je akce Konec
                $idLogobsluhy = $nalezeno1->getId();
                $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
                $logobsluhy->setKonec(new \DateTime);
                $logobsluhy->setTyp(4);
                $logobsluhy->setPoznamka($poznamka);

                //zapis vyreseni ke stroji
                $porucha = $this->getDoctrine()->getRepository('AppBundle:Porucha')->find($id_poruchy);
                $porucha->setVyreseno(new \DateTime);
                $vysledek='Ukončení poruchy proběhlo úspěšně';
                $em->flush();
            }
            else {
                    $vysledek = 'Ukončení poruchy proběhlo neúspěšně. Na poruše se nezačalo pracovat nebo oprava poruchy je již ukončená';
                 }
        }
        else
        {
            $vysledek = 'Ukončení poruchy proběhlo neúspěšně. Oprava poruchy je přerušená.';
        }
        //update statusu stroje pracuje 1/nepracuje 0 v tabulce stroj, tato promenna prijde z rest api
        $sn = $this->getDoctrine()->getManager();
        $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);
        $stroj->setStatus($status);
        $sn->flush();
        return new JsonResponse("  Vysledek je: ".$vysledek, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/preruseniopravy2")
     */
    //pokud bude existovat 1, vytvoří se 2
    // status 0 stroj nepracuje 1 stroj pracuje

    public function preruseniopravyAction(Request $request)
    {
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje
        //$poznamka = $data['_poznamka'];


        if(empty($id_stroje) || empty($id_poruchy) || $status <'0' || $status >'1' )
        {
            return new JsonResponse("Posilate prazdne hodnoty nebo spatny status (0,1) ", Response::HTTP_NOT_ACCEPTABLE);
        }
        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        // hleda cisloprevzeti z tabulky Prevzal na zaklade id pracovnika a cisla poruchy
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();



        //hledani pro dané čísloprevzetí typ 2 v tabulce logObsluhy
        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$cisloprevzeti,'typ'=>1 ));
        $nalezeno1 = $query2->setMaxResults(1)->getOneOrNullResult();

        if ($nalezeno1==NUll) {
            $vysledek = 'Opravu poruchy nelze Přerušit. Na opravě se nezačalo pracovat.';
        }
        else
        {
            $query2=$em->createQuery(
                "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
                ->setParameters(array('prev'=>$cisloprevzeti,'typ'=>2 ));
            $nalezeno2 = $query2->setMaxResults(1)->getOneOrNullResult();
            if ($nalezeno2==NUll)
            {
                $dataLogobsluhy->setIdprevzal($prevzal);
                $dataLogobsluhy->setStart(new \DateTime);
                $dataLogobsluhy->setTyp(2);//1 start

                $vysledek = 'Přerušení opravy poruchy proběhlo.';

                $em = $this->getDoctrine()->getManager();
                $em->persist($dataLogobsluhy);
                $em->flush();
            }
            else
            {
                $vysledek = 'Přerušení opravy poruchy proběhlo. Opravu poruchy je již přerušena.';
            }
        }

        //update statusu stroje pracuje 1/nepracuje 0 v tabulce stroj, tato promenna prijde z rest api
        $sn = $this->getDoctrine()->getManager();
        $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);
        $stroj->setStatus($status);
        $sn->flush();
        return new JsonResponse("  Vysledek je: ".$vysledek, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/statistika")
     */


    /*
      pošle počet přijatých neukončených poruch uživatelem
      počet nepřijatých poruch celkem
      počet pravidelných údržeb, které nejsou uzavřené
   */

    public function statistikaAction(Request $request)
    {
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        // zjisti pocet prijatých poruch pracovnikem
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT count(p)
              FROM AppBundle:Prevzal p
              WHERE p.idPracovnika= :idprac")
            ->setParameters(array('idprac'=>$id_pracovnika ));
        $pocetprijatych = $query->getSingleScalarResult();

        // zjisti pocet ukoncenych poruch (type 4) danym pracovnikem
        $query=$em->createQuery(
            "SELECT count(l)
              FROM AppBundle:LogObsluhy l
              JOIN l.prevzal p
              WHERE p.idPracovnika= :idprac AND l.typ= :t")
            ->setParameters(array('idprac'=>$id_pracovnika,'t'=>4));
        $pocetukoncenych = $query->getSingleScalarResult();
        $pocetprijatychneukoncenychporuch=$pocetprijatych-$pocetukoncenych;
        //return new JsonResponse(" pocet prijatych: ". $pocetprijatych. " pocet ukoncenych: ".$pocetukoncenych, Response::HTTP_OK);

// zjisti pocet poruch
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT count(p)
              FROM AppBundle:Porucha p
              ");
        $pocetporuch = $query->getSingleScalarResult();

        // zjisti pocet prijatých poruch vsemi pracovniky
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT count(p)
              FROM AppBundle:Prevzal p");
        $pocetprijatychporuchvsemi = $query->getSingleScalarResult();
        $pocetneprijatychporuch=$pocetporuch-$pocetprijatychporuchvsemi;
        //return new JsonResponse(" pocet poruch: ". $pocetporuch." pocetprijatychporuchvsemi: ".$pocetprijatychporuchvsemi, Response::HTTP_OK);

        //počet pravidelných údržeb, které nejsou uzavřené
        $query=$em->createQuery(
            "SELECT count(p)
              FROM AppBundle:Pravidelnaudrzba p
              WHERE p.provedeni is NULL" );
        $pocetneuzavrenychudrzeb = $query->getSingleScalarResult();

        //return new JsonResponse("pocet_NeprijatePoruchy:". $pocetprijatychneukoncenychporuch. "pocet_PrijateNeukonceneUzivatele: ". $pocetneprijatychporuch. "pocet_Udrzby: ".$pocetneuzavrenychudrzeb, Response::HTTP_OK);

        return new JsonResponse([ 'pocet_NeprijatePoruchy'=>(string)$pocetprijatychneukoncenychporuch,'pocet_PrijateNeukonceneUzivatele'=>(string)$pocetneprijatychporuch,'pocet_Udrzby' =>$pocetneuzavrenychudrzeb]);
        //return new JsonResponse(['pocet_Udrzby' =>$pocetneuzavrenychudrzeb]);
    }



    /**
     * @Rest\Post("/stroj")
     */

    //api pro vkladani stroju
    public function StrojAction(Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $id1pcontrol = $data['_id1pcontrol'];
        $nazev = $data['_nazev'];
        

        if (empty($id1pcontrol) ||  empty($nazev))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }

        $data = new Stroj;
        $data->setId1pcontrol($id1pcontrol);
        $data->setNazev($nazev);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new JsonResponse("Stroj byl vložen", Response::HTTP_OK);
        }

  /**
     * @Rest\Post("/startopravy3")
     */

    //1 běží oprava
    //2 přerušení opravy
    //3 konec opravy

    // pokud bude existovat 1 neudělá se nic, jinak nový řádek s hodnotou 1
    //nesmi existova 3 - oprava ukoncena

    public function Start3Action(Request $request)
    {
        $statusresult=0;
        $dataLogobsluhy = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        //$status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje


        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();

        if(empty($id_stroje)  || empty($id_poruchy) ) //dodelat kontrolu statusu
        {
            $statusresult=0;
            return new JsonResponse(["Status"=>$statusresult,"Message"=>"Posíláte prázdné hodnoty"], Response::HTTP_NOT_ACCEPTABLE);
        }

        //hledani v tabulce prevzal
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();

//hledani pro dané čísloprevzetí 1 v tabulce logObsluhy

        $query2=$em->createQuery(
            "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev'=>$prevzal,'typ'=>1 ));
        $nalezeno = $query2->setMaxResults(1)->getOneOrNullResult();

        if ($nalezeno==NULL) //1 nenalezena
        {
            $query2=$em->createQuery(
                "SELECT l
              FROM AppBundle:LogObsluhy l
              WHERE l.prevzal= :prev AND l.typ= :typ")
                ->setParameters(array('prev'=>$prevzal,'typ'=>3 ));
            $nalezeno2 = $query2->setMaxResults(1)->getOneOrNullResult();

            if ($nalezeno2==NULL)       //3 nenalezena
            {
                // neni 1 ani 3 vlozime radek s 1
                $dataLogobsluhy->setIdprevzal($prevzal);
                $dataLogobsluhy->setStart(new \DateTime);
                $dataLogobsluhy->setTyp(1);//1 start
                $vysledek = 'Začátek opravy poruchy vložen.';

                $em = $this->getDoctrine()->getManager();
                $em->persist($dataLogobsluhy);
                $em->flush();

                // vlozeni statusu stroje
                $status=0;
                //update statusu stroje pracuje 1/nepracuje 0
                $sn = $this->getDoctrine()->getManager();
                $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);
                //stroj nepracuje, opravuje se
                $stroj->setStatus($status);
                $sn->flush();

                $statusresult=1;
            }
            else //je 3 nalezena
            {
                $statusresult=0;
                $vysledek = 'Začátek opravy poruchy neproběhl. Porucha je již ukončena.';
            }
        }
        else //1 je nalezena, hledame 2
        {
            $statusresult=0;
            $vysledek = 'Oprava poruchy již běží.';
        }
        return new JsonResponse(["Status"=>$statusresult,"Message"=>$vysledek]);
    }


    /**
     * @Rest\Put("/preruseniopravy3")
     */
    //pokud nebude existovat 1, neni co prerusovat
    // bude existovat, zmeni se 1 na 2

    public function preruseniopravy3Action(Request $request)
    {
        $statusresult=0;
        $dataL = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje
        //$poznamka = $data['_poznamka'];


        if(empty($id_stroje) || empty($id_poruchy) || $status <'0' || $status >'1' )
        {
            return new JsonResponse("Posilate prazdne hodnoty nebo spatny status (0,1) ", Response::HTTP_NOT_ACCEPTABLE);
        }
        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');



        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();


        // hleda cisloprevzeti z tabulky Prevzal na zaklade id pracovnika a cisla poruchy
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();



//hledani pro dané čísloprevzetí typ 2 v tabulce logObsluhy


        $vysledek='';
        $query2 = $em->createQuery(
            "SELECT l
                  FROM AppBundle:LogObsluhy l
                  WHERE l.prevzal= :prev AND l.typ= :typ")
            ->setParameters(array('prev' => $cisloprevzeti, 'typ' => 1));

        $nalezeno1 = $query2->setMaxResults(1)->getOneOrNullResult();

        if ($nalezeno1!=NULL) //1 nalezena
        {
            //update sloupce konec tabulky logobsluhy toto je akce Konec
            $idLogobsluhy = $nalezeno1->getId();
            $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
            $logobsluhy->setKonec(new \DateTime);
            $logobsluhy->setTyp(2);
            //$logobsluhy->setPoznamka($poznamka);

            //zapis vyreseni ke stroji
            $porucha = $this->getDoctrine()->getRepository('AppBundle:Porucha')->find($id_poruchy);
            $porucha->setVyreseno(new \DateTime);
            $statusresult=1;
            $vysledek='Přerušení poruchy proběhlo úspěšně';
            $em->flush();

            //update statusu stroje pracuje 1/nepracuje 0 v tabulce stroj, tato promenna prijde z rest api
            $status=1;
            $sn = $this->getDoctrine()->getManager();
            $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);
            $stroj->setStatus($status);
            $sn->flush();
        }
        else {
            $statusresult=0;
            $vysledek = 'Přerušení poruchy proběhlo neúspěšně. Na poruše se nezačalo pracovat nebo oprava poruchy je již ukončená';
        }
        return new JsonResponse(["Status"=>$statusresult,"Message"=>$vysledek]);

    }


    /**
     * @Rest\Put("/konecopravy3")
     */
    //nebude existovat 1, nic nedelat, jinak 1 na 3


    public function Konec3Action(Request $request)
    {
        $statusresult=0;
        $dataL = new LogObsluhy();
        $dataS = new Stroj();

        $content = $request->getContent();
        $data = json_decode($content, true);
        $id_stroje = $data['_id_stroje'];
        $id_poruchy = $data['_id_poruchy'];
        $status = $data['_status']; //0 stroj nepracuje, 1 stroj pracuje
        $poznamka = $data['_poznamka'];


        if(empty($id_stroje) || empty($id_poruchy) || $status <'0' || $status >'1' )
        {
            $statusresult=0;
            $vysledek="Posilate prazdne hodnoty nebo spatny status (0,1) ";
            return new JsonResponse(["Status"=>$statusresult,"Message"=>$vysledek]);
        }
        //hledani id pracovnika pro poslany token v tabulce pracovnik
        $token=$request->headers->get('X-Auth-Token');



        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Pracovnik p
              WHERE p.token= :token")
            ->setParameter('token',$token);
        $nalezene = $query->setMaxResults(1)->getOneOrNullResult();
        $id_pracovnika = $nalezene->getId();


        // hleda cisloprevzeti z tabulky Prevzal na zaklade id pracovnika a cisla poruchy
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            "SELECT p
              FROM AppBundle:Prevzal p
              WHERE p.idPoruchy= :idpor AND p.idPracovnika= :idprac")
            ->setParameters(array('idpor'=>$id_poruchy,'idprac'=>$id_pracovnika ));
        $prevzal = $query->setMaxResults(1)->getOneOrNullResult();
        $cisloprevzeti = $prevzal->getId();



//hledani pro dané čísloprevzetí typ 2 v tabulce logObsluhy


        $vysledek='';
            $query2 = $em->createQuery(
                "SELECT l
                  FROM AppBundle:LogObsluhy l
                  WHERE l.prevzal= :prev AND l.typ= :typ")
                ->setParameters(array('prev' => $cisloprevzeti, 'typ' => 1));

            $nalezeno1 = $query2->setMaxResults(1)->getOneOrNullResult();

            if ($nalezeno1!=NULL) //1 nalezena
            {
                //update sloupce konec tabulky logobsluhy toto je akce Konec
                $idLogobsluhy = $nalezeno1->getId();
                $logobsluhy = $this->getDoctrine()->getRepository('AppBundle:LogObsluhy')->find($idLogobsluhy);
                $logobsluhy->setKonec(new \DateTime);
                $logobsluhy->setTyp(3);
                $logobsluhy->setPoznamka($poznamka);

                //zapis vyreseni ke stroji
                $porucha = $this->getDoctrine()->getRepository('AppBundle:Porucha')->find($id_poruchy);
                $porucha->setVyreseno(new \DateTime);

                $statusresult=0;
                $vysledek='Ukončení poruchy proběhlo úspěšně';
                $em->flush();

                //update statusu stroje pracuje 1/nepracuje 0 v tabulce stroj, tato promenna prijde z rest api
                $status=1;
                $sn = $this->getDoctrine()->getManager();
                $stroj = $this->getDoctrine()->getRepository('AppBundle:Stroj')->find($id_stroje);
                $stroj->setStatus($status);
                $sn->flush();
            }
            else {
                $statusresult=0;
                $vysledek = 'Ukončení poruchy proběhlo neúspěšně. Na poruše se nezačalo pracovat nebo oprava poruchy je již ukončená';
            }
        return new JsonResponse(["Status"=>$statusresult,"Message"=>$vysledek]);
    }





}
