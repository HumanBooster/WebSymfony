<?php

namespace HB\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    
    /**
     * Renvoie les articles pour la page d'accueil
     */
    public function getHomepageArticles($limit = null) {

        return $this->findBy(
                    array('published' => true, 'enabled' => true),
                    array('publishDate' => 'desc'),
                    $limit
                        );
    }
    
}
