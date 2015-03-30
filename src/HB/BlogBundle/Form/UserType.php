<?php

namespace HB\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email', 'repeated', array(
                'type' => 'email',
                'invalid_message' => 'Les adresss email doivent correspondre.',
                'options' => array('required' => true),
                'first_options'  => array('label' => 'Email'),
                'second_options' => array('label' => 'Email (confirmer)')
            ))
            ->add('login')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => array('required' => true),
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Mot de passe (confirmer)')
            ))
                // birthday permet de sélectionner un plage de date
                //  correspondant aux années de naissance
            ->add('birthDate', 'birthday')
                // on peut aussi définir une range personnalisée
            ->add('creationDate', 'datetime', array('years' => range(date('Y') - 20, date('Y'))))
            ->add('lastEditDate')
                // permet de ne pas cocher une checkbox
            ->add('enabled', 'checkbox', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HB\BlogBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hb_blogbundle_user';
    }
}
