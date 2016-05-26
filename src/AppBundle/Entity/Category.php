<?php
/**
 * Category entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/categories
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Category.
 *
 * @package Model
 * @author WS
 *
 * @ORM\Table(name="qa_categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Category")
 * @UniqueEntity(fields="name", groups={"category-default"})
 */
class Category
{
    /**
     * Id
     *
     * @ORM\Id
     * @ORM\Column(
     *     type="integer",
     *     nullable=false,
     *     options={
     *         "unsigned" = true
     *     }
     * )
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    private $id;

    /**
     * Name
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     * @Assert\NotBlank(groups={"category-default"})
     * @Assert\Length(min=3, max=128, groups={"category-default"})
     *
     * @var string $name
     */
    private $name;
    
    /**
     * Description
     *
     * @ORM\Column(
     *     name="description",
     *     type="string",
     *     length=128,
     *     nullable=true
     * )
     * @Assert\Length(min=3, max=128, groups={"category-default"})
     *
     * @var string $name
     */
    private $description;

    /**
     * Questions array
     *
     * @ORM\OneToMany(
     *      targetEntity="Question",
     *      mappedBy="category"
     * )
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $questions
     */
    protected $questions;


    /**
     * Set id
     *
     * @param string $id
     * @return Category
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get all records.
     *
     * @access public
     * @return array Categories array
     */
    public function findAll()
    {
        return $this->categories;
    }

    /**
     * Get single record by its id.
     *
     * @access public
     * @param integer $id Single record index
     * @return array Result
     */
    public function find($id)
    {
        if (isset($this->categories[$id]) && count($this->categories)) {
            return $this->categories[$id];
        } else {
            return array();
        }
    }
    
    /**
     * Delete single record by its id.
     *
     * @access public
     * @param integer $category Single record index
     * @return array Result
     */
    public function delete($category)
    {
        return $this->remove($category);
        //$this->sections->removeElement($sections);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Add questions.
     *
     * @param \AppBundle\Entity\Question $question
     */
    public function addQuestion(\AppBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    }

    /**
     * Remove questions
     *
     * @param \AppBundle\Entity\Question $questions
     */
    public function removeAnswer(\AppBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Remove questions
     *
     * @param \AppBundle\Entity\Question $questions
     */
    public function removeQuestion(\AppBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }
}
