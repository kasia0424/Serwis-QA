<?php
/**
 * Question entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/questions
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Question.
 *
 * @package Model
 * @author WS
 *
 * @ORM\Table(name="qa_questions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Question")
 * @UniqueEntity(fields="title", groups={"question-default"})
 */
class Question
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
     * Title
     *
     * @ORM\Column(
     *     name="title",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     * @Assert\NotBlank(groups={"question-default"})
     * @Assert\Length(min=3, max=128, groups={"question-default"})
     *
     * @var string $title
     */
    private $title;

    /**
     * Content
     *
     * @ORM\Column(
     *     name="content",
     *     type="string",
     *     length=128,
     *     nullable=true
     * )
     * @Assert\Length(min=3, max=128, groups={"question-default"})
     *
     * @var string $content
     */
    private $content;

    /**
     * Date
     *
     * @ORM\Column(
     *     name="date",
     *     type="datetime",
     *     nullable=false
     * )
     * @Assert\NotBlank(groups={"question-default"}))
     *
     * @var \DateTime $date
     */
    private $date;

    /**
     * Tags array
     *
     * @ORM\ManyToMany(
     *      targetEntity="Tag", 
     *      inversedBy="questions"
     * )
     * @ORM\JoinTable(name="qa_questions_tags")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $tags
     */
    protected $tags;

    /**
     * Categories array
     *
     * @ORM\ManyToOne(
     *      targetEntity="Category",
     *      inversedBy="questions"
     * )
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $category
     */
    protected $category;

    /**
     * Answers array
     *
     * @ORM\OneToMany(
     *      targetEntity="Answer",
     *      mappedBy="question"
     * )
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $answers
     */
    protected $answers;
    
    /**
     * Users array
     *
     * @ORM\ManyToOne(
     *      targetEntity="User",
     *      inversedBy="questions"
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $user
     */
    protected $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set id
     *
     * @param string $id
     * @return Question
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
     * Set title
     *
     * @param string $title
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Question
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set date.
     *
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Add tags.
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function addTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get category.
     *
     * @return
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category.
     *
     * @return
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->category->removeElement(category);
    }


    /**
     * Add answers.
     *
     * @param \AppBundle\Entity\Answer $answers
     */
    public function addAnswer(\AppBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;
    }

    /**
     * Remove answers
     *
     * @param \AppBundle\Entity\Answer $answers
     */
    public function removeAnswer(\AppBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Get all records.
     *
     * @access public
     * @return array Questions array
     */
    public function findAll()
    {
        return $this->questions;
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
        if (isset($this->questions[$id]) && count($this->questions)) {
            return $this->questions[$id];
        } else {
            return array();
        }
    }
    
    /**
     * Delete single record by its id.
     *
     * @access public
     * @param integer $question Single record index
     * @return array Result
     */
    public function delete($question)
    {
        return $this->remove($question);
        //$this->sections->removeElement($sections);
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Question
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
