<?php
/**
 * Question type.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/questions
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\DataTransformer\TagDataTransformer;
use AppBundle\Form\DataTransformer\CategoryDataTransformer;

/**
 * Class QuestionType.
 *
 * @package AppBundle\Form
 * @author Wanda Sipel
 */
class QuestionType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tagDataTransformer = new TagDataTransformer($options['tag_model']);
        $categoryDataTransformer = new CategoryDataTransformer($options['category_model']);

        $builder->add(
            'id',
            'hidden'
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('question-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'title',
                'text',
                array(
                    'label'      => 'Tytuł',
                    'required'   => true,
                    'max_length' => 128,
                )
            );
            $builder->add(
                'content',
                'textarea',
                array(
                    'label'      => 'Treść',
                    'required'   => false,
                    'max_length' => 128,
                )
            );
            $builder->add(
                'tags',
                'entity',
                array(
                    'class' => 'AppBundle:Tag',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'empty_data'  => null
                )
            );
            $builder->add(
                'category',
                'entity',
                array(
                    'class' => 'AppBundle:Category',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => true,
                    'empty_data'  => null
                )
            );
        }
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'Zapisz'
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
                'data_class' => 'AppBundle\Entity\Question',
                'validation_groups' => 'question-default',
            )
        );
        $resolver->setRequired(array('tag_model', 'category_model'));
        $resolver->setAllowedTypes(
            array(
                'tag_model' => 'Doctrine\Common\Persistence\ObjectRepository',
                'category_model' => 'Doctrine\Common\Persistence\ObjectRepository'
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
        return 'question_form';
    }
}
