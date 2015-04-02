<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Login controller.
 *
 */
class LoginController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function loginAction()
    {
        $user = $this->getUser();
        if ($user == null) {
            $request = $this->getRequest();
            $session = $request->getSession();
            // get the login error if there is one
            if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
                $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            } else {
                $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
                $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            }
            return $this->render("HBBlogBundle:Login:login.html.twig", array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'user'          => $user
            ));
        } else {
            return $this->render("HBBlogBundle:Login:logout.html.twig", array(
                'user'          => $user
            ));
        }
    }
}
