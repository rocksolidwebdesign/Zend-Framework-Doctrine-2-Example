<?php
namespace Entities\Blog;

/**
 * @Entity(repositoryClass="\Entities\Blog\EntryRepository")
 * @Table(name="blog_entry")
 * @HasLifecycleCallbacks
 */
class Entry extends \Entities\AbstractEntity
{
    /**
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(name="permalink", type="string", length=255) */
    protected $permalink;

    /** @Column(name="title", type="string", length=255) */
    protected $title;

    /** @Column(name="pub_date", type="datetime") */
    protected $pub_date;

    /** @Column(name="content", type="text") */
    protected $content;

    /** @OneToMany(targetEntity="\Entities\Blog\Comment", mappedBy="entry") */
    protected $comments;

    public function getUrl()
    {
        $root_url      = "/blog";
        $archive_url   = $this->getArchiveUrl();
        $permalink_url = $this->getPermalinkUrl();
        $url = "$root_url/$archive_url/$permalink_url";

        return $url;
    }

    public function getArchiveUrl()
    {
        return $this->pub_date->format('m/d/Y');
    }

    public function getPermalinkUrl()
    {
        return ($this->permalink ? $this->permalink : $this->id);
    }

    public function getBreadcrumbs($url = 'UNINITIALIZED', $result = array())
    {
        $url = $url == 'UNINITIALIZED' ? $this->getUrl() : $url;
        $url = $url ? $url : '/';

        preg_match('#^(.*)/([^/]{1,})$#',$url,$matches);

        $crumbs  = isset($matches[1]) ? $matches[1] : '';
        $current = isset($matches[2]) ? $matches[2] : '';

        $title = ($this->getPermalinkUrl() == $current ? $this->title : 
            ($current == 'blog' ? 'Blog' : 
                ($current == '' ? 'Home' : $current)
            )
        );

        // generate the breadcrumb for this page
        $crumb = array(
            'url' => $url,
            'title' => $title,
        );

        // prepend it to the list of crumbs
        array_unshift($result, $crumb);

        // if this page has a parent
        if ($url != '/') {
            $url = $crumbs;
            // add the parent's breadcrumb to the result
            return $this->getBreadcrumbs($url, $result);
        }

        return $result;
    }

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
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();

        $this->created_at = $this->updated_at = new \DateTime("now");
    }
}

class EntryRepository extends \Entities\PaginatedRepository
{
    protected $_entityClassName = 'Entities\Blog\Entry';
}
