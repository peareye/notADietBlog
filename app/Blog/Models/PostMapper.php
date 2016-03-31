<?php
/**
 * Post Mapper
 */
namespace Blog\Models;

class PostMapper extends DataMapperAbstract
{
    protected $table = 'post';
    protected $tableAlias = 'p';
    protected $modifyColumns = array('title', 'url', 'url_locked', 'meta_description', 'content', 'content_html', 'content_excerpt', 'published_date');
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
     * Get Single Post
     *
     * Returns post by url segment, or by post ID
     * @param string|int $id Post URL segment or ID
     * @param bool $publishedPostsOnly Only get published posts - defaults to true
     * @return object
     */
    public function getSinglePost($id, $publishedPostsOnly = true)
    {
        $this->sql = $this->defaultSelect . ' where 1=1';

        // Was a numeric ID supplied?
        if (is_numeric($id)) {
            $where = ' and p.id = ?';
            $this->bindValues[] = (int) $id;
        } else {
            $where = ' and p.url = ?';
            $this->bindValues[] = $id;
        }

        $this->sql .= $where;

        if ($publishedPostsOnly) {
            $this->sql .= ' and p.published_date <= curdate()';
        }

        // Execute the query
        $this->execute();
        $result = $this->statement->fetch();
        $this->clear();

        return $result;
    }

    /**
     * Verify URL
     *
     * Check if post URL is unique
     * @param string $url Cleaned title
     * @return boolean
     */
    public function postUrlIsUnique($url)
    {
        $this->sql = "select 1 from {$this->table} where url = ?";
        $this->bindValues[] = $url;

        // Execute the query
        $this->execute();
        $data = $this->statement->fetchAll();
        $this->clear();

        // Did we find anything?
        if (!empty($data)) {
            return false;
        }

        return true;
    }
}
