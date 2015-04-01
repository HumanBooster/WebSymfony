<?php

namespace HB\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HB\BlogBundle\Entity\Article;

/**
 * LoadArticleData est la classe de fixtures pour charger des articles en base
 *
 * @author humanbooster
 */
class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // on récupère norte utilisateur ajouté dans la fixture user
        $user1 = $this->getReference("user1");
        $user2 = $this->getReference("user2");
        
        $article = new Article();
        $article->setTitle("Un article de test");
        $article->setContent("Ce magnifique article a été généré par les DoctrineFixtures");
        $article->setPublished(true);
        $article->setAuthor($user1);

        $manager->persist($article);
        
        $article2 = new Article();
        $article2->setTitle("Un 2nd article de test");
        $article2->setContent("Ce deuxième magnifique article a été généré par les DoctrineFixtures");
        $article2->setPublished(true);
        $article2->setAuthor($user2);
        
        $manager->persist($article2);
        
        // on pousse en base
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}