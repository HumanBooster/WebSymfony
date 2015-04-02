<?php

namespace HB\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            //->add('creationDate')
            //->add('lastEditDate')
            ->add('publishDate', 'datetime')
            ->add('published', 'checkbox', array('required' => false))
            ->add('enabled', 'checkbox', array('required' => false))
            ->add('author', 'entity', array('class' => 'HBBlogBundle:User',
                                            'property' => 'nameLogin'))
            ->add('banner', new ImageType())
                //->add('author', new UserType())
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HB\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hb_blogbundle_article';
    }
}
