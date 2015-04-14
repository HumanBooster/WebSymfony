<?php

namespace HB\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HB\BlogBundle\Entity\Article;
use HB\BlogBundle\Form\ArticleType;

/**
 * Controleur Article permet de faire un CRUDL sur Article
 *
 * L'annotation Route au niveau du Controller impose un préfixe à la route
 * de chaque méthode
 * 
 * @Route("/article")
 */
class ArticleController extends Controller {

    /**
     * Liste tous les articles
     *
     * @Route("/", name="article")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        // on récupère l'entity manager à l'aide du service Doctrine
        $em = $this->getDoctrine()->getManager();

        // on récupère le repository de Article et on lui demande 
        // tous les articles
        $entities = $em->getRepository('HBBlogBundle:Article')->findAll();

        // on transmet la liste d'article au template en la nommant entities
        return array(
            'articles' => $entities,
        );
    }

    /**
     * Liste tous les articles
     *
     * Route("/recents/{number}", name="article_recents")
     * Method("GET")
     * @Template()
     */
    public function recentsAction($number) {
        // on récupère l'entity manager à l'aide du service Doctrine
        $em = $this->getDoctrine()->getManager();

        // on récupère le repository de Article et on lui demande 
        // tous les articles
        $entities = $em->getRepository('HBBlogBundle:Article')->findAll();

        // on transmet la liste d'article au template en la nommant entities
        return array(
            'entities' => $entities,
            'number' => $number
        );
    }

    /**
     * Affiche un formulaire pour ajouter un article
     *
     * @Route("/new", name="article_new")
     * @Template()
     */
    public function newAction() {
        $article = new Article;
        return $this->editAction($article);
    }

    /**
     * Affiche un article sur un Id
     * 
     * @Route("/{id}", name="article_show")
     * @Template()
     */
    public function showAction(Article $article) {
        // on a récupéré l'article grace à un ParamConverter magique
        // on transmet notre article à la vue
        return array('article' => $article);
    }

    /**
     * Affiche un formulaire pour éditer un article sur un Id
     *
     * @Route("/{id}/edit", name="article_edit")
     * @Template("HBBlogBundle:Article:new.html.twig")
     */
    public function editAction(Article $article) {
        // on créé un objet formulaire en lui précisant quel Type utiliser
        $form = $this->createForm(new ArticleType, $article);

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $article contient les valeurs entrées dans
            // le formulaire par le visiteur
            $form->bind($request);


            if ($article->getSlug() == "") {

                $slugger = $this->get('hb_blog.slugger');

                $slug = $slugger->getSlug((int) $article->getId() . ' ' . $article->getTitle());
                $article->setSlug($slug);
            }

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                // On l'enregistre notre objet $article dans la base de données
                $em = $this->getDoctrine()->getManager();
                // on déclenche l'upload
                if ($article->getBanner())
                    $article->getBanner()->upload();

                $em->persist($article);
                $em->flush();

                // On redirige vers la page de visualisation de l'article nouvellement créé
                return $this->redirect(
                                $this->generateUrl('article_read', array('id' => $article->getId()))
                );
            }
        }

        if ($article->getId() > 0)
            $edition = true;
        else
            $edition = false;

        // passe la vue de formulaire à la vue
        return array('form' => $form->createView(), 'edition' => $edition);
    }

    /**
     * Supprime un article sur un Id
     *
     * @Route("/{id}/delete", name="article_delete")
     */
    public function deleteAction(Article $article) {
        // on a récupéré l'article grace à un ParamConverter magique
        // on demande à l'entity manager de supprimer l'article
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($article);
        $em->flush();

        // On redirige vers la page de liste des articles
        return $this->redirect(
                        $this->generateUrl('article_list')
        );
    }

}
