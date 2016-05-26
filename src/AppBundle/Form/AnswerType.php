<?php
/**
 * Answer type.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/answers
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\DataTransformer\TagDataTransformer;
use AppBundle\Form\DataTransformer\CategoryDataTransformer;

/**
 * Class AnswerType.
 *
 * @package AppBundle\Form
 * @author Wanda Sipel
 */
class AnswerType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tagDataTransformer = new TagDataTransformer($options['question_model']);

        $builder->add(
            'id',
            'hidden'
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('answer-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'content',
                'textarea',
                array(
                    'label'      => 'Answer content',
                    'required'   => true,
                    'max_length' => 128,
                )
            );
            $builder->add(
                'question',
                'entity',
                array(
                    'class' => 'AppBundle:Question',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => true
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
                'data_class' => 'AppBundle\Entity\Answer',
                'validation_groups' => 'answer-default',
            )
        );
        $resolver->setRequired(array('question_model'));
        $resolver->setAllowedTypes(
            array(
                'question_model' => 'Doctrine\Common\Persistence\ObjectRepository'
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
        return 'answer_form';
    }
}
