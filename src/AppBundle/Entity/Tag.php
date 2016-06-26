<?php
/**
 * Tag entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/tags
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Tag.
 *
 * @package Model
 * @author WS
 *
 * @ORM\Table(name="qa_tags")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tag")
 * @UniqueEntity(fields="name", groups={"tag-default"})
 */
class Tag
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
     * @Assert\NotBlank(groups={"tag-default"})
     * @Assert\Length(min=3, max=128, groups={"tag-default"})
     *
     * @var string $name
     */
    private $name;

    /**
     * Questions array
     *
     * @ORM\ManyToMany(targetEntity="Question", mappedBy="tags")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $questions
     */
    protected $questions;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Tag
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
     * @return Tag
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
     * Add questions.
     *
     * @param \AppBundle\Entity\Question $questions
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
    public function removeQuestion(\AppBundle\Entity\Question $question)
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
     * Get all records.
     *
     * @access public
     * @return array Tags array
     */
    public function findAll()
    {
        return $this->tags;
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
        if (isset($this->tags[$id]) && count($this->tags)) {
            return $this->tags[$id];
        } else {
            return array();
        }
    }
    
    /**
     * Delete single record by its id.
     *
     * @access public
     * @param integer $tag Single record index
     * @return array Result
     */
    public function delete($tag)
    {
        return $this->remove($tag);
    }
}
