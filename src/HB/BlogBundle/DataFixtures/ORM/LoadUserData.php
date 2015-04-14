<?php

namespace HB\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use HB\UserBundle\Entity\User;

/**
 * LoadUserData est la classe de fixtures pour charger des users en base
 *
 * @author humanbooster
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        
        $user = $userManager->createUser();

        $user->setUsername("bogosss93");
        $user->setPlainPassword("123456");
        //$user->setName("Marcel Patulacci");
        //$user->setBirthDate(new \DateTime("05/25/1985"));
        $user->setEmail("peuimporte@domaine.com");
        $user->setEnabled(true);
        
        $userManager->updateUser($user);

        $user2 = $userManager->createUser();

        $user2->setUsername("bqsdqs");
        $user2->setPlainPassword("12s3456");
        //$user2->setName("Manu la tremblotte");
        //$user2->setBirthDate(new \DateTime("05/15/1985"));
        $user2->setEmail("importe@domaine.com");
        $user->setEnabled(true);
        
        $userManager->updateUser($user2);
        
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

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

}