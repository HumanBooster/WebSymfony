<?php

namespace HB\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HB\BlogBundle\Entity\User;

/**
 * LoadUserData est la classe de fixtures pour charger des users en base
 *
 * @author humanbooster
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setLogin("bogosss93");
        $user->setPassword("123456");
        $user->setName("Marcel Patulacci");
        $user->setBirthDate(new \DateTime("05/25/1985"));
        $user->setEmail("peuimporte@domaine.com");
        
        $manager->persist($user);

        $user2 = new User();
        $user2->setLogin("bqsdqs");
        $user2->setPassword("12s3456");
        $user2->setName("Manu la tremblotte");
        $user2->setBirthDate(new \DateTime("05/15/1985"));
        $user2->setEmail("peuimporte@domaine.com");
        
        $manager->persist($user2);
        
        $user3 = new User();
        $user3->setLogin("test");
        $user3->setPassword("pwd");
        $user3->setName("Admin");
        $user3->setBirthDate(new \DateTime("05/15/1985"));
        $user3->setEmail("padmin@domaine.com");
        
        $manager->persist($user3);
        
        // on pousse en base
        $manager->flush();
        
        // on sotcke dans le repository des fixtures, les objets à partager
        $this->addReference('user1', $user);
        $this->addReference('user2', $user2);
    }

    /**
     * Permet de définir l'oredre de chargement des fixtures
     * 
     * @return int
     */
    public function getOrder() {
        return 1;
    }

}