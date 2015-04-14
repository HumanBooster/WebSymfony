<?php

namespace HB\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HB\BlogBundle\Entity\Article;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * LoadArticleData est la classe de fixtures pour charger des articles en base
 *
 * @author humanbooster
 */
class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // on récupère norte utilisateur ajouté dans la fixture user
        $user1 = $this->getReference("user1");
        //$user2 = $this->getReference("user2");
        $slugger = $this->container->get('hb_blog.slugger');
        
        for ($i = 0; $i< 100; $i++) {

            $article = new Article();
            $article->setTitle("Un article de test" .$i);
            $article->setContent("Ce magnifique article a été généré par les DoctrineFixtures");
            $article->setPublished(true);
            $article->setAuthor($user1);
            $article->setSlug($slugger->getSlug($i."-".$article->getTitle()));

            $manager->persist($article);

        }
       
        
        // on pousse en base
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

}