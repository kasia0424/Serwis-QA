<?php
/**
 * Tag type.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/tags
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TagType.
 *
 * @package AppBundle\Form
 * @author Wanda Sipel
 */
class TagType extends AbstractType
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
            && !in_array('tag-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'name',
                'text',
                array(
                    'label'      => 'Tag name',
                    'required'   => true,
                    'max_length' => 128,
                )
            );
        }
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'Save'
            )
        );
    }

    /**
     * Sets default options for form.
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Tag',
                'validation_groups' => 'tag-default',
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
        return 'tag_form';
    }
}
