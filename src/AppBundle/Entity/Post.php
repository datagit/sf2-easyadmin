<?php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 *
 * @Vich\Uploadable
 */
class Post
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(name="slug", type="string")
     */
    private $slug;

    /**
     * @ORM\Column(name="tags", type="string")
     */
    private $tags;

    /**
     * @ORM\Column(name="summary", type="string")
     * @Assert\NotBlank(message="post.blank_summary")
     */
    private $summary;

    /**
     * @ORM\Column(name="content", type="text")
     * @Assert\Length(min = "10", minMessage = "post.too_short_content")
     */
    private $content;

    /**
     * @ORM\Column(name="author_email", type="string")
     * @Assert\Email()
     */
    private $authorEmail;

    /**
     * @ORM\Column(name="published_at", type="datetime")
     * @Assert\DateTime()
     */
    private $publishedAt;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"publishedAt" = "DESC"})
     */
    private $comments;

    //start------------------------------------
    /**
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="photo")
     * @Assert\File(maxSize="2M")
     * @var File $photoFile
     */
    protected $photoFile;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Post
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setPhotoFile(File $image = null)
    {
        $this->photoFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    //end------------------------------------


    public function __construct()
    {
        $d = new \DateTime();
        $this->publishedAt = $d;
        $this->createdAt = $d;
        $this->updatedAt = $d;
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Is the given User the author of this Post?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isAuthor(User $user = null)
    {
        return $user->getEmail() == $this->getAuthorEmail();
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        //$this->comments->add($comment);
        //$comment->setPost($this);

        $this->comments[] = $comment;

        return $this;
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
        $comment->setPost(null);
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function __toString()
    {
        return $this->title;
    }

}
