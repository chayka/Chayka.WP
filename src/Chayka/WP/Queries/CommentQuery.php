<?php

namespace Chayka\WP\Queries;

use Chayka\WP\Models\PostModel;
use Chayka\WP\Models\CommentModel;

class CommentQuery{
    
    protected $vars = array();

    /**
     * @var PostModel
     */
    protected $post = null;
    
    public function __construct() {
        ;
    }

    /**
     * Get all the set vars in an assoc array
     *
     * @return array
     */
    public function getVars(){
        return $this->vars;
    }

	/**
	 * Add vars to the set
	 *
	 * @param array $vars
	 *
	 * @return $this
	 */
	public function setVars($vars){
		foreach($vars as $key=>$value){
			$this->vars[$key] = $value;
		}
		return $this;
	}

	/**
     * Set query filter var
     *
     * @param string $key
     * @param string $value
     * @return CommentQuery
     */
    public function setVar($key, $value){
        $this->vars[$key] = $value;
        return $this;
    }
    
    /**
     * Create instance of query object
     *
     * @param integer|PostModel $post postId or post itself
     * @return CommentQuery
     */
    public static function query($post = 0){
        $q  = new self();
        if($post){
            if($post instanceof PostModel){
                $q->post($post);
            }else{
                $q->postId($post);
            }
        }
        return $q;
    }
    
    /**
     * Select all matching comments
     *
     * @return array(CommentModel)
     */
    public function select(){
        $comments = CommentModel::selectComments($this->getVars());
        if($this->post){
            $this->post->setComments($comments);
        }
        
        return $comments;
    }
    
    /**
     * Select firs matching comment
     * 
     * @return CommentModel
     */
    public function selectOne(){
        $comments = $this->select();
        return count($comments)?reset($comments):null;
    }

	/**
	 * Select total amount of comments under this query instead of comments
	 *
	 * @param bool $omitLimitOffset
	 *
	 * @return int
	 */
	public function selectCount($omitLimitOffset = true){
		$params = $this->getVars();
		if($omitLimitOffset) {
			if ( isset( $params['number'] ) ) {
				unset( $params['number'] );
			}
			if ( isset( $params['offset'] ) ) {
				unset( $params['offset'] );
			}
		}
		$params['count'] = true;

		return get_comments($params);
	}
    
    /**
     * Only return comments with this status.
     * 
     * @param string $status
     * @return CommentQuery
     */
    public function status($status){
        return $this->setVar('status', $status);
    }
    
    public function status_Hold(){
        return $this->status('hold');
    }
    
    public function status_Approve(){
        return $this->status('approve');
    }
    
    public function status_Spam(){
        return $this->status('spam');
    }
    
    public function status_Trash(){
        return $this->status('trash');
    }
    
    /**
     * Number of comments to return. Leave blank to return all comments.
     * 
     * @param int $number
     * @return CommentQuery
     */
    public function number($number){
        return $this->setVar('number', $number);
    }
    
    /**
     * Offset from latest comment. You must include $number along with this.
     *
     * @param int $offset
     * @return CommentQuery
     */
    public function offset($offset){
        return $this->setVar('offset', $offset);
    }
    
    /**
     * How to sort $orderby. Valid values: ASC, DESC
     * Default: DESC
     *
     * @param string $order
     * @return CommentQuery
     */
    public function order($order){
        return $this->setVar('order', $order);
    }
    
    public function order_ASC(){
        return $this->order('ASC');
    }
   
    public function order_DESC(){
        return $this->order('DESC');
    }
    
    /**
     * Set the field used to sort comments.
     * Default: comment_date_gmt
     * 
     * @param string $orderBy
     * @return CommentQuery
     */
    public function orderBy($orderBy){
        return $this->setVar('orderby', $orderBy);
    }
    
    /**
     * Only return comments for a particular post or page.
     * 
     * @param int $postId
     * @return CommentQuery
     */
    public function postId($postId){
        return $this->setVar('post_id', $postId);
    }

    /**
     * Only return comments for a particular post.
     *
     * @param PostModel $post
     * @return CommentQuery
     */
    public function post($post){
        $this->post = $post;
        return $this->postId($post->getId());
    }

	/**
	 * Select comments for specified post ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function postIdIn($ids){
		return $this->setVar('post__in', $ids);
	}

	/**
	 * Exclude comments for specified post ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function postIdNotIn($ids){
		return $this->setVar('post__not_in', $ids);
	}

	/**
	 * Select comment by is.
	 *
	 * @param int $commentId
	 *
	 * @return CommentQuery
	 */
	public function commentId($commentId){
		return $this->setVar('ID', $commentId);
	}

	/**
	 * Select comments for specified ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function commentIdIn($ids){
		return $this->setVar('comment__in', $ids);
	}

	/**
	 * Exclude comments for specified ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function commentIdNotIn($ids){
		return $this->setVar('comment__not_in', $ids);
	}


    /**
     * Only return comments for a particular user.
     * 
     * @param int $userId
     * @return CommentQuery
     */
    public function userId($userId){
        return $this->setVar('user_id', $userId);
    }

	/**
	 * Select comments for specified user ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function userIdIn($ids){
		return $this->setVar('author__in', $ids);
	}

	/**
	 * Exclude comments for specified user ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function userIdNotIn($ids){
		return $this->setVar('author__not_in', $ids);
	}

	/**
	 * Select replies for specified id
	 * @param $commentId
	 *
	 * @return CommentQuery
	 */
	public function parent($commentId){
		return $this->setVar('parent', $commentId);
	}

	/**
	 * Search by author email
	 *
	 * @param string $email
	 *
	 * @return CommentQuery
	 */
	public function authorEmail($email){
		return $this->setVar('author_email', $email);
	}

	/**
	 * Only return comments for a particular post author.
	 *
	 * @param int $userId
	 * @return CommentQuery
	 */
	public function postUserId($userId){
		return $this->setVar('post_author', $userId);
	}

	/**
	 * Select comments for specified post author ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function postUserIdIn($ids){
		return $this->setVar('post_author__in', $ids);
	}

	/**
	 * Exclude comments for specified post author ids
	 * @param array|string $ids
	 *
	 * @return CommentQuery
	 */
	public function postUserIdNotIn($ids){
		return $this->setVar('post_author__not_in', $ids);
	}

	/**
	 * Array of IDs or email addresses of users whose unapproved comments
	 * will be returned by the query regardless of `$status`. Default empty.
	 * @param array|string $include
	 *
	 * @return CommentQuery
	 */
    public function includeUnapproved($include = 'self'){
	    if($include === 'self'){
		    $include = array(get_current_user_id());
	    }
	    return $this->setVar('include_unapproved', $include);
    }

	/**
	 * Set return values.
	 *
	 * @param string|array(string) $fields
	 * @return PostQuery
	 */
	public function fields($fields){
		return $this->setVar('fields', $fields);
	}

	public function fields_All(){
		return $this->fields('*');
	}

	public function fields_Ids(){
		return $this->fields('ids');
	}



    /**
     * Only return the total count of comments.
     * 
     * @param boolean $countOnly
     * @return CommentQuery
     */
    public function returnCountOnly($countOnly = true){
        return $this->setVar('count', $countOnly);
    }
    
    /**
     * Custom field key.
     * 
     * @param string $key
     * @return CommentQuery
     */
    public function metaKey($key){
        return $this->setVar('meta_key', $key);
    }
    
    /**
     * Custom field value
     * 
     * @param string $value
     * @return CommentQuery
     */
    public function metaValue($value){
        return $this->setVar('meta_value', $value);
    }
    
    /**
     * Custom field numeric value
     * 
     * @param number $value
     * @return CommentQuery
     */
    public function metaValueNum($value){
        return $this->setVar('meta_value_num', $value);
    }
    
    /**
     * Operator to test the 'meta_value'. 
     * Possible values are '!=', '>', '>=', '<', or '<='. Default value is '='.
     * 
     * @param string $compare
     * @return CommentQuery
     */
    public function metaCompare($compare){
        return $this->setVar('meta_compare', $compare);
    }
    
    /**
     * Custom field parameters (available with Version 3.5).
     * 
     * @param string $key Custom field key
     * @param string|array $value Custom field value 
     * (Note: Array support is limited to a compare value of 
     * 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' or 'NOT EXISTS')
     * @param string $compare Operator to test. Possible values are 
     * '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 
     * 'BETWEEN', 'NOT BETWEEN', 'EXISTS', and 'NOT EXISTS'. 
     * Default value is '='.
     * @param string $type Custom field type. Possible values are 
     * 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 
     * 'TIME', 'UNSIGNED'. Default value is 'CHAR'.
     * @return CommentQuery
     */
    public function metaQuery($key, $value, $compare = '=', $type = 'CHAR'){
        $taxQuery = array(
            'key' => $key,
            'value' => $value,
            'compare' => $compare,
            'type' => $type,
        );
        
        $this->vars['meta_query'][]=$taxQuery;
        
        return $this;
    }

    /**
     * Set relation for multiple meta_query handling
     * Should come first before metaQuery() call
     *  
     * @param string $relation
     * @return CommentQuery
     */
    public function metaQueryRelation($relation){
        $this->vars['meta_query']['relation']=$relation;
        
        return $this;
    }
    
    public function metaQueryRelation_AND(){
        return $this->metaQueryRelation('AND');
    }
    
    public function metaQueryRelation_OR(){
        return $this->metaQueryRelation('OR');
    }
    
}
