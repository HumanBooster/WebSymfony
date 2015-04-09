<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogController extends Controller {

    /**
     * @Route("/", name="blog_index")
     * @Route("/page/{page}", name="blog_index_page")
     * @Template()
     */
    public function indexAction($page = 1) {
        // on récupère l'entity manager à l'aide du service Doctrine
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HBBlogBundle:Article');
        // on récupère le repository de Article et on lui demande 

        $articles = $repo->getHomepageArticles();

        // on récupère le service paginator
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
                $articles, // liste des articles ou query
                $page, // numéro de page
                2 // nombre d'élements par page
        );
        //$pagination->setUsedRoute("blog_index_page");
        
        return array(
            'pagination' => $pagination
        );
    }

}
