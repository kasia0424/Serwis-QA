<?php
/**
 * Tag type.
 *
 * @copyright (c) 2016 Agnieszka Gorgolewska
 * @link http://wierzba.wzks.uj.edu.pl/~12_gorgolewska
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserType.
 *
 * @package AppBundle\Form
 * @author Agnieszka Gorgolewska
 */
class UserType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'id',
            'hidden'
        );

        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('user-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'username',
                'text',
                array(
                'label'      => 'Username',
                'required'   => true,
                'max_length' => 255,
                )
            );
            $builder->add(
                'email',
                'email',
                array(
                'label' => 'form.email',
                'required' => true,
                )
            );
            $builder->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ));

            $builder->add(
                'roles',
                'entity',
                array(
                    'class'    => 'AppBundle:Role',
                    'property' => 'role',
                    'multiple' => true,
                    'required' => true,
                    'label'    => 'Role'
                )
            );

            $builder->add(
                'save',
                'submit',
                array(
                'label' => 'Save'
                )
            );
        } elseif (in_array('user-delete', $options['validation_groups'])) {
            $builder->add('Yes', 'submit');
            $builder->add('No', 'submit');
        }


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
                'validation_groups' => 'user-default',
            )
        );
    }

    /**
     * Getter for form name.
     *
     * @return string Form name
     */
    public function getName()
    {
        return 'user_form';
    }
}
