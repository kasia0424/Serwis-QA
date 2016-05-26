<?php
/**
 * Category data transformer.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/categories
 */

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class CategoryDataTransformer.
 *
 * @package AppBundle\Form\DataTransformer
 * @author Wanda Sipel
 */
class CategoryDataTransformer implements DataTransformerInterface
{
    /**
     * Model object.
     *
     * @var ObjectRepository $model
     */
    private $model = null;

    /**
     * CategoryDataTransformer constructor.
     *
     * @param ObjectRepository $model
     */
    public function __construct(ObjectRepository $model)
    {
        $this->model = $model;
    }

    /**
     * Transform.
     *
     * @param array $categories Array of category objects
     *
     * @return string Result
     */
    public function transform($categories)
    {
        if (!$categories) {
            return '';
        }

        $result = array();

        foreach ($categories as $category) {
            $result[] = $category->getName();
        }

        return join(', ', $result);

    }

    /**
     * Reversed transform.
     *
     * @param string $categories Category names
     *
     * @return array Result
     */
    public function reverseTransform($categories)
    {
        if (!$categories) {
            return array();
        }

        $result = array();
        $categoriesNames = explode(',', $categories);

        foreach ($categoriesNames as $name) {
            $name = trim($name);

            $category = $this->model->findOneByName($name);

            if (!$category) {
                $category = new Category();
                $category->setName($name);
                $this->model->save($category);
            }

            $result[] = $category;
        }

        return $result;
    }
}
