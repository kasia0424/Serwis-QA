<?php
/**
 * Answer entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/answers
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Answer.
 *
 * @package Model
 * @author WS
 *
 * @ORM\Table(name="qa_answers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Answer")
 * @UniqueEntity(fields="content", groups={"answer-default"})
 */
class Answer
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
     * Content
     *
     * @ORM\Column(
     *     name="content",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     * @Assert\NotBlank(groups={"answer-default"})
     * @Assert\Length(min=3, max=128, groups={"answer-default"})
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
     * @Assert\NotBlank(groups={"answer-default"})
     *
     * @var \DateTime $date
     */
    private $date;
    
    /**
     * Questions array
     *
     * @ORM\ManyToOne(
     *      targetEntity="Question",
     *      inversedBy="answers"
     * )
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $question
     */
    protected $question;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set id
     *
     * @param string $id
     * @return Answer
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
     * Set content
     *
     * @param string $content
     * @return Answer
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
    
    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get all records.
     *
     * @access public
     * @return array Answers array
     */
    public function findAll()
    {
        return $this->answers;
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
        if (isset($this->answers[$id]) && count($this->answers)) {
            return $this->answers[$id];
        } else {
            return array();
        }
    }
    
    /**
     * Delete single record by its id.
     *
     * @access public
     * @param integer $answer Single record index
     * @return array Result
     */
    public function delete($answer)
    {
        return $this->remove($answer);
        //$this->sections->removeElement($sections);
    }
}
