<?php

namespace AppBundle\Controller;



use AppBundle\Entity\Pracovnik;
use AppBundle\Entity\Porucha;
use AppBundle\Entity\Prevzal;
use AppBundle\Entity\Logporuch;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;


/**
 * @Rest\Route("/api")
 */
class ApiController extends FOSRestController
{
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
     * @Rest\Get("/user")
     */
    //public function getAction()
    //{
       // $user = $this->getUser();

        //return new JsonResponse([
        //    'jmeno' => $user->getJmeno(),
        //    'prijmeni' => $user->getPrijmeni(),
       // ]);
    //}


    /**
     * @Rest\Put("/pracovnik/{id}")
     */
    public function updateAction($id,Request $request)
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
     * @Rest\Post("/porucha/")
     */
    public function postPoruchaAction(Request $request)
    {
        $data = new Porucha;
        //$id = $request->get('id');
        $stroj = $request->get('stroj');
        $casvzniku = $request->get('casvzniku');
        $oblastpriciny = $request->get('oblastpriciny');
        $priorita = $request->get('priorita');
        $poznamka = $request->get('poznamka');
        //$vyreseno = $request->get('vyreseno');

        if(empty($stroj) || empty($casvzniku) || empty($oblastpriciny) || empty($priorita) || empty($poznamka))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }
        //$id->setId($id);
        $data->setStroj($stroj);
        $data->setCasvzniku(new \DateTime($casvzniku));
        $data->setOblastpriciny($oblastpriciny);
        $data->setPriorita($priorita);
        $data->setPoznamka($poznamka);
        //$data->setVyreseno(new \DateTime($vyreseno));

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);

        $em->flush();

        return new JsonResponse("User Added Successfully", Response::HTTP_OK);
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
                '$id_poruchy' => $prevzal->getIdPoruchy(),
                '$id_pracovnika' => $prevzal->getIdPracovnika(),
                'prevzetidatcas' => $prevzal->getPrevzetidatcas(),
                '$role_obsluhy' => $prevzal->getRole_obsluhy(),


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
     * @Rest\Post("/logporuch/")
     */
    public function postlogporuchAction(Request $request)
    {
        $data = new Logporuch();
        //$id = $request->get('id');



        $idprevzal = $request->get('idprevzal');
        $pretusenistrojbezi = $request->get('pretusenistrojbezi');
        $prerusenistrojestoji = $request->get('prerusenistrojestoji');
        $pokracovani = $request->get(  'pokracovani');


        if(empty($idprevzal) || empty($pretusenistrojbezi) || empty($prerusenistrojestoji) || empty($pokracovani))
        {
            return new JsonResponse("Posíláte prázdné hodnoty", Response::HTTP_NOT_ACCEPTABLE);
        }
        //$id->setId($id);
        $data->setIdprevzal($idprevzal);
        $data->setPretusenistrojbezi(new \DateTime($pretusenistrojbezi));
        $data->setPrerusenistrojestoji(new \DateTime($prerusenistrojestoji));
        $data->setPokracovani(new \DateTime($pokracovani));
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);

        $em->flush();

        return new JsonResponse("Prevzeti Logporuchy bylo uspesne", Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/logporuch")
     * Pro přístup ke všem poruchám
     */
    public function getLogporuchAction()
    {
        $logporucha = $this->get('doctrine')->getManager()->getRepository(Logporuch::class)->findAll();

        $result = [];

        foreach ($logporucha as $logporuch) {
            $result[] = [
                'id' => $logporuch->getId(),
                'idprevzal' => $logporuch->getIdprevzal(),
                'pretusenistrojbezi' => $logporuch->getPretusenistrojbezi(),
                'prerusenistrojestoji' => $logporuch->getPrerusenistrojestoji(),
                'pokracovani' => $logporuch->getPokracovani(),
            ];
        };

        return new JsonResponse($result);
    }
    /**
     * Pouze jedno prevzal
     * GET na například /api/logporuch/1
     * @Rest\Get("/logporuch/{id}")
     * @Rest\QueryParam(name="id", requirements="\d+", description="ID logporuch.")
     * @param $id
     * @return JsonResponse
     */
    public function getSingleLogporuch($id)
    {
        dump($id);
        $logporuch = $this->get('doctrine')->getManager()->getRepository(Logporuch::class)->find($id);

        if (!$logporuch) {
            throw new ResourceNotFoundException('Prevzal not found');
        }

        return new JsonResponse([
            'id' => $logporuch->getId(),
            'idprevzal' => $logporuch->getIdprevzal(),
            'pretusenistrojbezi' => $logporuch->getPretusenistrojbezi(),
            'prerusenistrojestoji' => $logporuch->getPrerusenistrojestoji(),
            'pokracovani' => $logporuch->getPokracovani(),
        ]);
    }
}
