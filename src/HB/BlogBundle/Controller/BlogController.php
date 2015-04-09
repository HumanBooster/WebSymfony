<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     * @Route("/page/{page}", name="blog_index_page")
     * @Template()
     */
    public function indexAction($page = 1) {
        // on récupère l'entity manager à l'aide du service Doctrine
        $em = $this->getDoctrine()->getManager();

        // on récupère le repository de Article et on lui demande 
        // les articles de la page d'accueil
        $articles = $em->getRepository('HBBlogBundle:Article')
                ->getHomepageArticles($page-1);
        $countPages = $em->getRepository('HBBlogBundle:Article')
                ->getHomepageCountPages();

        $lienPageSuivante = $page < $countPages ?
                $this->generateUrl("blog_index_page", array("page" => $page+1))
                : null ;

        $lienPagePrecedente = $page > 1 ?
                $this->generateUrl("blog_index_page", array("page" => $page-1))
                : null;

        // on transmet la liste d'article au template en la nommant entities
        return array(
            'articles' => $articles,
            'lienPageSuivante' => $lienPageSuivante,
            'lienPagePrecedente' => $lienPagePrecedente,
            'countPages' => $countPages
        );
    }
}
