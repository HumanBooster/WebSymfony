<?php
namespace HB\BlogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array('childrenAttributes' => array('class' => "nav"),
                                                   'currentClass' => 'active'));
        
        $menu->addChild('Home', array('route' => 'blog_index'));
        $menu->addChild('Liste des articles', array('route' => 'article'));
        $menu->addChild('Liste des utilisateurs', array('route' => 'user'));

        // access services from the container!
        $em = $this->container->get('doctrine')->getManager();
        // findMostRecent and Blog are just imaginary examples
        $articles = $em->getRepository('HBBlogBundle:Article')->getHomepageArticles(10);

        $menu->addChild('Derniers articles', array(
                'uri' => "#",
                'linkAttributes' => array('class' => 'dropdown-toggle',
                                          'data-toggle' => 'dropdown'),
                'attributes' => array('class' => 'dropdown'),
                'childrenAttributes' => array('class' => 'dropdown-menu')));
        
        foreach ($articles as $article) {
            $menu['Derniers articles']->addChild($article->getTitle(), array(
                'route' => 'blog_article_slug',
                'routeParameters' => array('slug' => $article->getSlug())
            ));
        }
        /*
        // create another menu item
        $menu->addChild('About Me', array('route' => 'about'));
        // you can also add sub level's to your menu's as follows
        $menu['About Me']->addChild('Edit profile', array('route' => 'edit_profile'));
*/
        // ... add more children

        return $menu;
    }
}
