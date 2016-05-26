<?php
/**
 * Tag data transformer.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/tags
 */

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagDataTransformer.
 *
 * @package AppBundle\Form\DataTransformer
 * @author Wanda Sipel
 */
class TagDataTransformer implements DataTransformerInterface
{
    /**
     * Model object.
     *
     * @var ObjectRepository $model
     */
    private $model = null;

    /**
     * TagDataTransformer constructor.
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
     * @param array $tags Array of tag objects
     *
     * @return string Result
     */
    public function transform($tags)
    {
        if (!$tags) {
            return '';
        }

        $result = array();

        foreach ($tags as $tag) {
            $result[] = $tag->getName();
        }

        return join(', ', $result);

    }

    /**
     * Reversed transform.
     *
     * @param string $tags Tag names
     *
     * @return array Result
     */
    public function reverseTransform($tags)
    {
        if (!$tags) {
            return array();
        }

        $result = array();
        $tagsNames = explode(',', $tags);

        foreach ($tagsNames as $name) {
            $name = trim($name);

            $tag = $this->model->findOneByName($name);

            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
                $this->model->save($tag);
            }

            $result[] = $tag;
        }

        return $result;
    }
}
