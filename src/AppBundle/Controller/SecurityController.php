<?php
/**
 * Created by PhpStorm.
 * Pracovnik: Jáchym
 * Date: 22.11.2018
 * Time: 23:02
 */

namespace AppBundle\Controller;


use AppBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
           '_username' => $lastUsername,
        ]);

        return $this->render('security/login.html.twig', array(
            'form' => $form->createView(),
            //'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * Redirect users after login based on the granted ROLE
     * @Route("/redirect", name="redirect")
     */
    public function loginRedirectAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('login');
            // throw $this->createAccessDeniedException();
        }

        if($this->get('security.authorization_checker')->isGranted('ROLE_DASHBOARD'))
        {
            return $this->redirectToRoute('dashboard2');
        }
        else
        {
            return $this->redirectToRoute('udrzba-index');
        }

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}