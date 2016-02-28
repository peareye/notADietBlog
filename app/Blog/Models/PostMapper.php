<?php
/**
 * Post Mapper
 */
namespace Blog\Models;

class PostMapper extends DataMapperAbstract
{
    protected $table = 'post';
    protected $tableAlias = 'p';
    protected $modifyColumns = array('title', 'url', 'content', 'content_excerpt', 'published_date');
    protected $domainObjectClass = 'Post';
    protected $defaultSelect = 'select SQL_CALC_FOUND_ROWS p.* from post p';

    /**
     * Get Blog Posts with Offset
     *
     * Define limit and offset to limit result set.
     * Returns an array of Domain Objects (one for each record)
     * @param int $limit Limit
     * @param int $offset Offset
     * @param bool $publishedPostsOnly Only get published posts - defaults to true
     * @return array
     */
    public function getPosts($limit = null, $offset = null, $publishedPostsOnly = true)
    {
        $this->sql = $this->defaultSelect;

        if ($publishedPostsOnly) {
            $this->sql .= ' where p.published_date <= curdate()';
        }

        // Add order by
        if ($publishedPostsOnly) {
            $this->sql .= ' order by p.published_date desc';
        } else {
            $this->sql .= ' order by p.published_date is null desc, p.published_date desc';
        }

        if ($limit) {
            $this->sql .= " limit ?";
            $this->bindValues[] = $limit;
        }

        if ($offset) {
            $this->sql .= " offset ?";
            $this->bindValues[] = $offset;
        }

        return $this->find();
    }

    /**
     * Save Blog Post
     *
     * Adds pre-save manipulation prior to calling _save
     * @param Domain $post Object
     * @return mixed Domain Object on success, false otherwise
     */
    // public function save(DomainObjectAbstract $post)
    // {
    //     // Get dependencies
    //     $app = \Slim\Slim::getInstance();
    //     $Toolbox = $this->container->toolbox;

    //     // Set URL safe post title
    //     $post->url = $Toolbox->cleanUrl($post->title);

    //     // Set content excerpt
    //     $post->content_excerpt = $Toolbox->truncateHtmlText($post->content);

    //     return $this->_save($post);
    // }
}
