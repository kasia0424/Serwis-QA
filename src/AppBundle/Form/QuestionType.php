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
                    'label'      => 'Question tile',
                    'required'   => true,
                    'max_length' => 128,
                )
            );
            $builder->add(
                'content',
                'textarea',
                array(
                    'label'      => 'Question content',
                    'required'   => false,
                    'max_length' => 128,
                )
            );
            // $builder->add(
                // $builder
                    // ->create('tags', 'text')
                    // ->addModelTransformer($tagDataTransformer),
                // array(
                    // 'required'   => false,
                // )
            // );
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
                    // query choices from this entity
                    'class' => 'AppBundle:Category',
                    // use the User.username property as the visible option string
                    'property' => 'name',
                    // used to render a select box, check boxes or radios
                    'multiple' => false,
                    'expanded' => true,
                    'empty_data'  => null
                )
            );
            // $builder->add(
                // $builder
                    // ->create('categories', 'text')
                    // ->addModelTransformer($categoryDataTransformer)
            // );
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
