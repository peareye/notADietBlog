<?php
/**
 * Comment Mapper
 */
namespace Blog\Models;

class CommentMapper extends DataMapperAbstract
{
    protected $table = 'comment';
    protected $tableAlias = 'c';
    protected $modifyColumns = array('reply_id', 'post_id', 'name', 'email', 'comment', 'approved');
    protected $domainObjectClass = 'Comment';
    protected $defaultSelect = 'select * from comment';

    /**
     * Get Comments by Post ID
     *
     * @param int $postId Post record ID
     * @param bool $approvedOnly Only get approved comments - defaults to true
     * @return array
     */
    public function getComments($postId = null, $approvedOnly = true)
    {
        $this->sql = $this->defaultSelect . ' where 1=1';

        if ($postId !== null) {
            $this->sql .= ' and post_id = ?';
            $this->bindValues[] = $postId;
        }

        if ($approvedOnly) {
            $this->sql .= ' and approved = \'Y\'';
        }

        // Add order by
        $this->sql .= ' order by post_id, created_date desc';

        return $this->find();
    }

    /**
     * Change Comment Status
     *
     * @param int $commentId
     * @param str $status Y or N, defaults to Y
     */
    public function commentStatus($commentId, $status = 'Y')
    {

    }

}
