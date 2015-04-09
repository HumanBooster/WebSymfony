<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     * @Template()
     */
    public function indexAction() {
        // on récupère l'entity manager à l'aide du service Doctrine
        $em = $this->getDoctrine()->getManager();

        // on récupère le repository de Article et on lui demande 
        // tous les articles
        $articles = $em->getRepository('HBBlogBundle:Article')->findAll();

        // on transmet la liste d'article au template en la nommant entities
        return array(
            'articles' => $articles,
        );
    }
}
