<?php
namespace Entities\Blog;

/**
 * @Entity(repositoryClass="\Entities\Blog\CommentRepository")
 * @Table(name="blog_comment")
 * @HasLifecycleCallbacks
 */
class Comment extends \Entities\AbstractEntity
{
    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Entities\Blog\Entry")
     * @JoinColumn(name="entry_id", referencedColumnName="id")
     */
    protected $entry;

    /** @Column(name="approved", type="string", length=255) */
    protected $approved;

    /** @Column(name="title", type="string", length=255) */
    protected $title;

    /** @Column(name="content", type="text") */
    protected $content;

    /** @Column(name="pub_date", type="datetime") */
    protected $pub_date;

    /** @Column(type="datetime") */
    private $created_at;

    /** @Column(type="datetime") */
    private $updated_at;

    /** @PreUpdate */
    public function updated()
    {
        $this->updated_at = new \DateTime("now");
    }

    public function __construct()
    {
        $this->created_at = $this->updated_at = new \DateTime("now");
    }
}

class CommentRepository extends \Entities\PaginatedRepository
{
    protected $_entityClassName = 'Entities\Blog\Comment';
}
