<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use HB\BlogBundle\Entity\Article;

class BlogController extends Controller {

    /**
     * @Route("/", name="blog_index")
     * @Route("/", name="home")
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
                20 // nombre d'élements par page
        );
        $pagination->setUsedRoute("blog_index_page");
        
        return array(
            'pagination' => $pagination
        );
    }
    
    /**
     * @Route("/blog/{slug}", name="blog_article_slug")
     * @Template()
     */
    public function showAction(Article $article) {

        return array(
            'article' => $article
        );
    }

    /**
     * Fournit le widget de recherche d'article avec autocomplétion, le code est dans le template.
     * 
     * Le code qui fournit les résultats est dans ArticleController:ajaxSearch
     * 
     * @Template()
     */
    public function searchAction() {
        return array();
    }
}
