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
class ArticleController extends Controller
{

    /**
     * Liste tous les articles
     *
     * @Route("/", name="article")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        // on récupère l'entity manager à l'aide du service Doctrine
        $em = $this->getDoctrine()->getManager();

        // on récupère le repository de Article et on lui demande 
        // tous les articles
        $entities = $em->getRepository('HBBlogBundle:Article')->findAll();

        // on transmet la liste d'article au template en la nommant entities
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Trouve et affiche un objet Article
     *
     * @Route("/{id}", name="article_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        // on récupère l'entity manager
        $em = $this->getDoctrine()->getManager();
        // on récupère le repository de Article, et on lui demande de 
        // trouver l'article de l'id demandé
        $entity = $em->getRepository('HBBlogBundle:Article')->find($id);

        // s'il n'y a pas d'article pour cette id, on lève une exception NotFound
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        // on génère le formulaire de suppression pour l'afficher dans la page
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Affiche un formulaire pour créer un nouvel article
     *
     * @Route("/new", name="article_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        // on crée un nouvel objet article vierge
        $entity = new Article();
        // on génère un formulaire à partir de cet article
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Affiche un formulaire d'édition pour l'article correspondant à l'id 
     *
     * @Route("/{id}/edit", name="article_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        //on vérifie d'abord que l'article demandé existe
        // on récupère l'entity manager
        $em = $this->getDoctrine()->getManager();
        // on récupère le repository de Article, et on lui demande de 
        // trouver l'article de l'id demandé
        $entity = $em->getRepository('HBBlogBundle:Article')->find($id);

        // s'il n'existe pas, on lève une exception NotFound
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        // on créé les formulaire d'édition et de suppression
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        // on passe à la vue l'article et les deux vues des formulaires
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Récupère la Request et gère la soumission d'un formulaire de 
     * création d'un article
     *
     * @Route("/", name="article_create")
     * @Method("POST")
     * @Template("HBBlogBundle:Article:new.html.twig")
     */
    public function createAction(Request $request)
    {
        // on instancie un nouvel objet Article
        $entity = new Article();
        
        // on génère un formulaire - ici pour le traitement
        // on lui passe (par référence) l'objet article vide
        $form = $this->createCreateForm($entity);
        
        // permet de "bind" la Request à notre formulaire, de 
        // vérifier les données saisies, et de remplir notre
        // objet article $entity
        $form->handleRequest($request);

        // si le formulaire est valide, on insère en base
        if ($form->isValid()) {
            // on récupère l'entity manager
            $em = $this->getDoctrine()->getManager();
            // on persiste l'entité dans le manager
            $em->persist($entity);
            // on applique les modifs en base de données
            $em->flush();

            // on redirige vers l'url générée à partir du nom de la route
            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
        }

        // si le formulaire est invalide, on renvoie le formulaire qui 
        // inclut les messages de validation.
        return array(
            'entity' => $entity,
            // à noter qu'on ne transmet pas le form, mais sa vue générée
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Permet de traiter la soumission d'un formulaire pour éditer un article existant
     *
     * @Route("/{id}", name="article_update")
     * @Method("PUT")
     * @Template("HBBlogBundle:Article:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        // de nouveau, on récupère l'em et le repo, on demande l'article
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('HBBlogBundle:Article')->find($id);
        // et de nouveau on vérifie son existence avant d'aller plus loin
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }
        
        // gérer la datetime de dernière édition
        $entity->setLastEditDate(new \DateTime());
        
        // on créé un formulaire de suppression
        $deleteForm = $this->createDeleteForm($id);
        // puis un formulaire d'édition
        $editForm = $this->createEditForm($entity);
        // auquel on bind la Request
        $editForm->handleRequest($request);

        // si le formulaire est valide, on pousse les entités en base
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        // sinon, on affiche les formulaires avec les erreurs
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Traite le formulaire de suppression d'article
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // on génère de nouveau le formulaire pour le traiter
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        // si le formulaire est valide (on a confirmé la suppression)
        if ($form->isValid()) {
            // on teste l'existence
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HBBlogBundle:Article')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Article entity.');
            }

            // on supprime l'entité
            $em->remove($entity);
            // et on pousse en bdd
            $em->flush();
        }

        // on redirige vers la liste
        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * Génére un formulaire HTML pour créer un nouvel article
     * Cette méthode est privée et n'a pas de route, on l'appelle
     * depuis le controller
     *
     * @param Article $entity L'entité article
     *
     * @return \Symfony\Component\Form\Form Le formulaire de création d'article
     */
    private function createCreateForm(Article $entity)
    {
        // on créé un formulaire à partir d'un nouvel ArticleType
        // on lui passe un objet article pour le préremplir
        // puis un tableau d'option
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));

        // on ajoute un bouton pour soumettre le formulaire
        $form->add('submit', 'submit', 
                array('label' => 'Create',
                      // permet d'ajouter une classe à la balise html
                      'attr' => array( 'class' => 'btn')
            ));

        return $form;
    }
    
    /**
    * Créé un formulaire d'édition d'un article
    *
    * @param Article $entity L'entité article
    *
    * @return \Symfony\Component\Form\Form Le formulaire 
    */
    private function createEditForm(Article $entity)
    {
        // on créé un formulaire à partir d'un nouvel ArticleType
        // on lui passe un objet article pour le préremplir
        // puis un tableau d'option
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        // on ajoute le bouton submit
        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class'=>'btn')));

        return $form;
    }

    /**
     * Crée un formulaire pour supprimer un article
     *
     * @param mixed $id L'id de l'entité article
     *
     * @return \Symfony\Component\Form\Form Le formulaire
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
