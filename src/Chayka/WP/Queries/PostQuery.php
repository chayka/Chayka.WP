<?php

namespace Chayka\WP\Queries;

use Chayka\WP\Models\PostModel;
//use Chayka\WP\Models\CommentModel;

class PostQuery{
    
    protected $vars = array();

    /**
     * Create PostQuery instance.
     * If $globalImport is truthy then instance imports data from global $wp_the_query
     *
     * @param bool $globalImport
     */
    public function __construct($globalImport = false) {
        global $wp_the_query, $wp_query;
        if($globalImport){
            $q = $wp_the_query?$wp_the_query:$wp_query;
            $this->vars = $q->query_vars;
        }
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
     * @return PostQuery
     */
    public function setVar($key, $value){
        $this->vars[$key] = $value;
        return $this;
    }
    
    /**
     * Create PostQuery instance.
     * If $globalImport is truthy then instance imports data from global $wp_the_query
     *
     * @param boolean $globalImport
     * @return PostQuery
     */
    public static function query($globalImport = false){
        return new self($globalImport);
    }
    
    /**
     * Select all matching posts
     * 
     * @return array(PostModel)
     */
    public function select(){
        return PostModel::selectPosts($this->getVars());
    }
    
    /**
     * Select first matching post
     * 
     * @return PostModel
     */
    public function selectOne(){
        $posts = $this->select();
        return count($posts)?reset($posts):null;
    }
    
    /**
     * Display posts by author, using author id
     * 
     * @param int|string|array $userId (e.g 10 | '10,11,12' | -12 | array(10,11,12))
     * @return PostQuery
     */
    public function authorIdIn($userId){
        return $this->setVar('author', $userId);
    }
    
    /**
     * Display posts by author, using author nicename
     * 
     * @param string $userNicename
     * @return PostQuery
     */
    public function authorNiceName($userNicename){
        return $this->setVar('author_name', $userNicename);
    }
    
    /**
     * Show posts associated with certain categories
     * 
     * @param int $catId
     * @return PostQuery
     */
    public function categoryId($catId){
        return $this->setVar('cat', $catId);
    }
    
    /**
     * Show posts associated with certain categories
     * 
     * @param string $slug
     * @return PostQuery
     */
    public function categorySlug($slug){
        return $this->setVar('category_name', $slug);
    }
    
    /**
     * Show posts associated with certain categories
     * 
     * @param array(int) $catIds
     * @return PostQuery
     */
    public function categoryIdsAnd($catIds){
        return $this->setVar('category__and', $catIds);
    }
    
    /**
     * Show posts associated with certain categories
     * 
     * @param array(int) $catIds
     * @return PostQuery
     */
    public function categoryIdsIn($catIds){
        return $this->setVar('category__in', $catIds);
    }
    
    /**
     * Show posts associated with certain categories
     * 
     * @param array(int) $catIds
     * @return PostQuery
     */
    public function categoryIdsNotIn($catIds){
        return $this->setVar('category__not_in', $catIds);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param int $tagId
     * @return PostQuery
     */
    public function tagId($tagId){
        return $this->setVar('tag_id', $tagId);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param string|array(string) $slug
     * @return PostQuery
     */
    public function tagSlug($slug){
        return $this->setVar('tag', $slug);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param array(int) $tagIds
     * @return PostQuery
     */
    public function tagIdsAnd($tagIds){
        return $this->setVar('tag__and', $tagIds);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param array(int) $tagIds
     * @return PostQuery
     */
    public function tagIdsIn($tagIds){
        return $this->setVar('tag__in', $tagIds);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param array(int) $tagIds
     * @return PostQuery
     */
    public function tagIdsNotIn($tagIds){
        return $this->setVar('tag__not_in', $tagIds);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param array(string) $tagSlugs
     * @return PostQuery
     */
    public function tagSlugsAnd($tagSlugs){
        return $this->setVar('tag_slug__and', $tagSlugs);
    }
    
    /**
     * Show posts associated with certain tags
     * 
     * @param array(int) $tagSlugs
     * @return PostQuery
     */
    public function tagSlugsIn($tagSlugs){
        return $this->setVar('tag_slug__in', $tagSlugs);
    }
    
    /**
     * 
     * @param string $taxonomy
     * @param int|string|object|array(id|slug|object) $terms
     * @param string $field 'id' or 'slug'
     * @param bool $includeChildren
     * @param string $operator
     * @return PostQuery
     */
    public function taxonomyQuery($taxonomy, $terms, $field = 'auto', $includeChildren = true, $operator = 'IN'){
        if(is_string($terms) && strpos($terms, ',')){
            $terms = preg_split('%\s*,\s*%', $terms);
        }
        if('auto' == $field){
            if(is_numeric($terms)){
                $field = 'id';
            }else if(is_string($terms)){
                $field = 'slug';
            }else if(is_object($terms)){
                $field = 'id';
                $terms = $terms->term_id;
            }else if(is_array($terms)){
                if(count($terms)){
                    $term = reset($terms);
                    if(is_numeric($term)){
                        $field = 'id';
                    }else if(is_string($term)){
                        $field = 'slug';
                    }else if(is_object($term)){
                        $field = 'id';
                        $termIds = array();
                        foreach($terms as $term){
                            $termIds[]=$term->term_id;
                        }
                        $terms = $termIds;
                    }
                }else{
                    return $this;
                }
            }
        }
        $taxQuery = array(
            'taxonomy' => $taxonomy,
            'terms' => $terms,
            'field' => $field,
            'include_children' => $includeChildren,
            'operator' => $operator,
        );

        $this->vars['tax_query'][]=$taxQuery;
        
        return $this;
    }

    /**
     * Set relation for multiple tax_query handling
     * Should come first before taxonomyQuery() call
     *  
     * @param string $relation 'AND'|'OR'
     * @return PostQuery
     */
    public function taxonomyQueryRelation($relation){
        $this->vars['tax_query']['relation']=$relation;
        
        return $this;
    }
    
    public function taxonomyQueryRelation_AND(){
        return $this->taxonomyQueryRelation('AND');
    }
    
    public function taxonomyQueryRelation_OR(){
        return $this->taxonomyQueryRelation('OR');
    }
    
    /**
     * Show posts based on a keyword search
     * 
     * @param string $keyword
     * @return PostQuery
     */
    public function search($keyword){
        return $this->setVar('s', $keyword);
    }
    
    /**
     * Display content based on post and page parameters
     * 
     * @param int $postId
     * @return PostQuery
     */
    public function postId($postId){
        return $this->setVar('p', $postId);
    }
    
    /**
     * Display content based on post and page parameters
     * 
     * @param string $slug
     * @return PostQuery
     */
    public function postSlug($slug){
        return $this->setVar('name', $slug);
    }
    
    /**
     * Display content based on post and page parameters
     * 
     * @param int $pageId
     * @return PostQuery
     */
    public function pageId($pageId){
        return $this->setVar('page_id', $pageId);
    }
    
    /**
     * Display content based on post and page parameters
     * Display child page using the slug of the parent and the child page, separated by a slash (e.g. 'parent_slug/child_slug')
     * 
     * @param string $slug
     * @return PostQuery
     */
    public function pageSlug($slug){
        return $this->setVar('pagename', $slug);
    }
    
    /**
     * Display content based on post and page parameters
     * 
     * @param int $postId
     * @return PostQuery
     */
    public function postParentId($postId){
        return $this->setVar('post_parent', $postId);
    }
    
    public function postParentId_TopLevel(){
        return $this->postParentId(0);
    }
    
    /**
     * Display content based on post and page parameters.
     * Specify posts whose parent is in an array. NOTE: Introduced in 3.6
     * 
     * @param array(int) $postIds
     * @return PostQuery
     */
    public function postParentIdIn($postIds){
        return $this->setVar('post_parent__in', $postIds);
    }
    
    /**
     * Display content based on post and page parameters.
     * Specify posts whose parent is not in an array.
     * 
     * @param array(int) $postIds
     * @return PostQuery
     */
    public function postParentIdNotIn($postIds){
        return $this->setVar('post_parent__not_in', $postIds);
    }
    
    /**
     * Display content based on post and page parameters.
     * Specify posts to retrieve. 
     * ATTENTION If you use sticky posts, they will be included 
     * 
     * @param array(int) $postIds
     * @return PostQuery
     */
    public function postIdIn($postIds){
        return $this->setVar('post__in', $postIds);
    }
    
    /**
     * Display content based on post and page parameters.
     * Specify post NOT to retrieve.
     * 
     * @param array(int) $postIds
     * @return PostQuery
     */
    public function postIdNotIn($postIds){
        return $this->setVar('post__not_in', $postIds);
    }
    
    public function postIdIn_StickyPosts(){
        return $this->postIdIn(get_option( 'sticky_posts' ));
    }

    public function postIdNotIn_StickyPosts(){
        return $this->postIdNotIn(get_option( 'sticky_posts' ));
    }

    /**
     * Show posts associated with certain type
     * 
     * @param string|array $postType
     * @return PostQuery
     */
    public function postType($postType){
        return $this->setVar('post_type', $postType);
    }
    
    public function postType_Post(){
        return $this->postType('post');
    }
    
    public function postType_Page(){
        return $this->postType('page');
    }
    
    public function postType_Revision(){
        return $this->postType('revision');
    }
    
    public function postType_Attachment(){
        return $this->postType('attachment');
    }
    
    public function postType_Any(){
        return $this->postType('any');
    }
    
    /**
     * Show posts associated with certain status
     * 
     * @param string|array $status
     * @return PostQuery
     */
    public function postStatus($status){
        return $this->setVar('post_status', $status);
    }
    
    public function postStatus_Publish(){
        return $this->postStatus('publish');
    }
    
    public function postStatus_Pending(){
        return $this->postStatus('pending');
    }
    
    public function postStatus_Draft(){
        return $this->postStatus('draft');
    }
    
    public function postStatus_AutoDraft(){
        return $this->postStatus('auto-draft');
    }
    
    public function postStatus_Future(){
        return $this->postStatus('future');
    }
    
    public function postStatus_Private(){
        return $this->postStatus('private');
    }
    
    public function postStatus_Inherit(){
        return $this->postStatus('inherit');
    }
    
    public function postStatus_Trash(){
        return $this->postStatus('trash');
    }
    
    public function postStatus_Any(){
        return $this->postStatus('any');
    }
    
    /**
     * Show all posts or use pagination. Default value is 'false', use paging
     * 
     * @param boolean $noPaging
     * @return PostQuery
     */
    public function noPaging($noPaging = true){
        return $this->setVar('nopaging', $noPaging);
    }
    
    /**
     * Set number of post to show per page 
     * (available with Version 2.1, replaced showposts parameter). 
     * Use 'posts_per_page'=>-1 to show all posts. 
     * Set the 'paged' parameter if pagination is off after using this parameter. 
     * Note: if the query is in a feed, wordpress overwrites this parameter with the stored 'posts_per_rss' option. 
     * To reimpose the limit, try using the 'post_limits' filter, or filter 'pre_option_posts_per_rss' and return -1
     * 
     * @param int $perPage
     * @return PostQuery
     */
    public function postsPerPage($perPage){
        return $this->setVar('posts_per_page', $perPage);
    }
    
    public function postsPerPage_All(){
        return $this->postsPerPage(-1);
    }
    
    /**
     * Set number of posts to show per page - on archive pages only. 
     * Over-rides posts_per_page and showposts on pages where is_archive() or is_search() would be true
     * 
     * @param int $perPage
     * @return PostQuery
     */
    public function postsPerArchivePage($perPage){
        return $this->setVar('posts_per_archive_page', $perPage);
    }
    
    /**
     * Number of page. 
     * Show the posts that would normally show up just on page X when using the "Older Entries" link
     * 
     * @param int $page
     * @return PostQuery
     */
    public function pageNumber($page){
        return $this->setVar('paged', $page);
    }
    
    /**
     * Set number of post to displace or pass over. 
     * Warning: Setting the offset parameter overrides/ignores the paged parameter 
     * and breaks pagination 
     * @param int $offset
     * @return PostQuery
     */
    public function offset($offset){
        return $this->setVar('offset', $offset);
    }
    
    /**
     * ignore sticky posts or not 
     * (available with Version 3.1, replaced caller_get_posts parameter). 
     * Default value is 0 - don't ignore sticky posts. 
     * Note: ignore/exclude sticky posts being included at the beginning of posts returned, 
     * but the sticky post will still be returned in the natural order of that list of posts returned
     * 
     * @param boolean $ignore
     * @return PostQuery
     */
    public function ignoreStickyPosts($ignore = true){
        return $this->setVar('ignore_sticky_posts', $ignore);
    }
    
    /**
     * Designates the ascending or descending order of the 'orderby' parameter. 
     * Defaults to 'DESC'
     * @param string $order
     * @return PostQuery
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
     * Sort retrieved posts by parameter. Defaults to 'date'.
     * 
     * @param string $orderBy
     * @return PostQuery
     */
    public function orderBy($orderBy){
        return $this->setVar('orderby', $orderBy);
    }
    
    public function orderBy_None(){
        return $this->orderBy('none');
    }
   
    public function orderBy_ID(){
        return $this->orderBy('ID');
    }
   
    public function orderBy_AuthorId(){
        return $this->orderBy('author');
    }
   
    public function orderBy_Title(){
        return $this->orderBy('title');
    }
   
    public function orderBy_Slug(){
        return $this->orderBy('name');
    }
   
    public function orderBy_Date(){
        return $this->orderBy('date');
    }
   
    public function orderBy_Modified(){
        return $this->orderBy('modified');
    }
   
    public function orderBy_ParentId(){
        return $this->orderBy('parent');
    }
   
    public function orderBy_Rand(){
        return $this->orderBy('rand');
    }
   
    public function orderBy_CommentCount(){
        return $this->orderBy('comment_count');
    }
   
    /**
     * Order by Page Order. Used most often for Pages (Order field in the Edit Page Attributes box) 
     * and for Attachments (the integer fields in the Insert / Upload Media Gallery dialog), 
     * but could be used for any post type with distinct 'menu_order' values (they all default to 0).
     * 
     * @return PostQuery
     */
    public function orderBy_MenuOrder(){
        return $this->orderBy('menu_order');
    }
    
    /**
     * Note that a 'meta_key=keyname' must also be present in the query. 
     * Note also that the sorting will be alphabetical which is fine for strings 
     * (i.e. words), but can be unexpected for numbers 
     * (e.g. 1, 3, 34, 4, 56, 6, etc, rather than 1, 3, 4, 6, 34, 56 as you might naturally expect). 
     * Use 'meta_value_num' instead for numeric values.
     * 
     * @param string $metaKey
     * @return PostQuery
     */
    public function orderBy_MetaValue($metaKey = null){
        if($metaKey){
            $this->metaKey($metaKey);
        }
        return $this->orderBy('meta_value');
    }
    
    /**
     * Order by numeric meta value (available with Version 2.8). 
     * Also note that a 'meta_key=keyname' must also be present in the query. 
     * This value allows for numerical sorting as noted above in 'meta_value'.
     * 
     * @param string $metaKey
     * @return PostQuery
     */
    public function orderBy_MetaValueNum($metaKey = null){
        if($metaKey){
            $this->metaKey($metaKey);
        }
        return $this->orderBy('meta_value_num');
    }
    
    /**
     * Preserve post ID order given in the post__in array (available with Version 3.5).
     * 
     * @param string|array $postIds
     * @return PostQuery
     */
    public function orderBy_PostIn($postIds = null){
        if($postIds){
            $this->postIdIn($postIds);
        }
        return $this->orderBy('post__in');
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $year 4 digit year (e.g. 2011)
     * @return PostQuery
     */
    public function year($year){
        return $this->setVar('year', $year);
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $month Month number (from 1 to 12)
     * @return PostQuery
     */
    public function month($month){
        return $this->setVar('monthnum', $month);
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $yearMonth YearMonth (For e.g.: 201307)
     * @return PostQuery
     */
    public function yearMonth($yearMonth){
        return $this->setVar('m', $yearMonth);
    }
    
    /**
     * Show posts associated with a certain time period.
     * Uses the MySQL WEEK command. The mode is dependent on the "start_of_week" option
     * 
     * @param int $week Week of the year (from 0 to 53).
     * @return PostQuery
     */
    public function week($week){
        return $this->setVar('w', $week);
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $day Day of the month (from 1 to 31).
     * @return PostQuery
     */
    public function day($day){
        return $this->setVar('day', $day);
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $hour Hour (from 0 to 23).
     * @return PostQuery
     */
    public function hour($hour){
        return $this->setVar('day', $hour);
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $minute Minute (from 0 to 60).
     * @return PostQuery
     */
    public function minute($minute){
        return $this->setVar('minute', $minute);
    }
    
    /**
     * Show posts associated with a certain time period.
     * 
     * @param int $second Second (0 to 60).
     * @return PostQuery
     */
    public function second($second){
        return $this->setVar('second', $second);
    }
    
    /**
     * Custom field key.
     * 
     * @param string $key
     * @return PostQuery
     */
    public function metaKey($key){
        return $this->setVar('meta_key', $key);
    }
    
    /**
     * Custom field value
     * 
     * @param string $value
     * @return PostQuery
     */
    public function metaValue($value){
        return $this->setVar('meta_value', $value);
    }
    
    /**
     * Custom field numeric value
     * 
     * @param number $value
     * @return PostQuery
     */
    public function metaValueNum($value){
        return $this->setVar('meta_value_num', $value);
    }
    
    /**
     * Operator to test the 'meta_value'. 
     * Possible values are '!=', '>', '>=', '<', or '<='. Default value is '='.
     * 
     * @param string $compare
     * @return PostQuery
     */
    public function metaCompare($compare){
        return $this->setVar('meta_compare', $compare);
    }
    
    /**
     * Custom field parameters (available with Version 3.1)
     * 
     * @param string $key Custom field key
     * @param string|array $value ustom field value 
     * (Note: Array support is limited to a compare value of 
     * 'IN', 'NOT IN', 'BETWEEN', or 'NOT BETWEEN'). 
     * Note: Due to bug #23268, value is required for EXISTS and NOT EXISTS 
     * comparisons to work correctly. You may use an empty string for the value 
     * as a workaround. If empty quotes ("") doesn't work, pass in NULL for the value.
     * @param string $compare Operator to test. Possible values are 
     * '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 
     * 'EXISTS' (only in WP >= 3.5), and 'NOT EXISTS' (also only in WP >= 3.5). 
     * Default value is '='.
     * @param string $type Custom field type. Possible values are 
     * 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'. 
     * Default value is 'CHAR'.
     * @return PostQuery
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
     * @return PostQuery
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
    
    /**
     * Display posts, if the user has the appropriate capability
     * 
     * @param string $permCapability
     * @return PostQuery
     */
    public function userHasCapability($permCapability){
        return $this->setVar('perm', $permCapability);
    }
    
    /**
     * Stop the data retrieved from being added to the cache.
     * 
     * @param boolean $cache
     * @return PostQuery
     */
    public function cachePosts($cache = true){
        return $this->setVar('cache_results', $cache);
    }
   
    /**
     * Stop the data retrieved from being added to the cache.
     * 
     * @param boolean $cache
     * @return PostQuery
     */
    public function cacheMeta($cache = true){
        return $this->setVar('update_post_meta_cache', $cache);
    }
   
    /**
     * Stop the data retrieved from being added to the cache.
     * 
     * @param boolean $cache
     * @return PostQuery
     */
    public function cacheTerms($cache = true){
        return $this->setVar('update_post_term_cache', $cache);
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
        return $this->fields('all');
    }
    
    public function fields_Ids(){
        return $this->fields('ids');
    }
    
    public function fields_ID_ParentId(){
        return $this->fields('id=>parent');
    }
    
    /**
     * Get date query model available for setup
     * @return PostDateQuery
     */
    public function setupDateQuery(){
        return new PostDateQuery($this);
    }

    /**
     * Set PostDateQuery
     *
     * @param $dateQuery
     */
    public function dateQuery($dateQuery){
        $this->vars['date_query'][]=$dateQuery;
    }
    
}

class PostDateQuery{
    /**
     * @var PostQuery
     */
    protected $postQuery = null;
    protected $vars = array();
    
    public function __construct($postQuery) {
        $this->postQuery = $postQuery;
    }
    
    /**
     * Get all vars
     * 
     * @return array
     */
    public function getVars(){
        return $this->vars;
    }
    
    /**
     * Set query filter var
     *
     * @param string $key
     * @param string $value
     * @return PostDateQuery
     */
    public function setVar($key, $value){
        $this->vars[$key] = $value;
        return $this;
    }
    
    /**
     * Set year comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function year($value){
        $this->setVar('year', $value);
        return $this;
    }
    
    /**
     * Set month comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function month($value){
        $this->setVar('month', $value);
        return $this;
    }
    
    /**
     * Set week comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function week($value){
        $this->setVar('week', $value);
        return $this;
    }
    
    /**
     * Set day comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function day($value){
        $this->setVar('day', $value);
        return $this;
    }
    
    /**
     * Set hour comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function hour($value){
        $this->setVar('hour', $value);
        return $this;
    }
    
    /**
     * Set minute comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function minute($value){
        $this->setVar('minute', $value);
        return $this;
    }
    
    /**
     * Set second comparison value
     * 
     * @param int $value
     * @return PostDateQuery
     */
    public function second($value){
        $this->setVar('second', $value);
        return $this;
    }
    
    /**
     * Set after comparison value
     * 
     * @param int $year - year or string strtotime()-compatible
     * @param int $month
     * @param int $day
     * @return PostDateQuery
     */
    public function after($year, $month = null, $day = null){
        $this->setVar('after', is_string($year) && strtotime($year)?
                $year:
                array(
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                ));
        return $this;
    }
       
    /**
     * Set before comparison value
     * 
     * @param int $year - year or string strtotime()-compatible
     * @param int $month
     * @param int $day
     * @return PostDateQuery
     */
    public function before($year, $month = null, $day = null){
        $this->setVar('before', is_string($year) && strtotime($year)?
                $year:
                array(
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                ));
        return $this;
    }
    
    /**
     * Set inclusive comparison flag
     * 
     * @param boolean $value
     * @return PostDateQuery
     */
    public function inclusive($value){
        $this->setVar('second', $value);
        return $this;
    }
    
    /**
     * Set Comparison Column name
     *
     * @param string $value
     * @return PostDateQuery
     */
    public function column($value){
        $this->setVar('column', $value);
        return $this;
    }
    
    /**
     * Set Comparison type
     *
     * @param string $value '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'
     * @return PostDateQuery
     */
    public function compare($value){
        $this->setVar('compare', $value);
        return $this;
    }
    
    /**
     * Set Comparison type
     *
     * @param string $value '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'
     * @return PostDateQuery
     */
    public function relation($value){
        $this->setVar('relation', $value);
        return $this;
    }
    
    /**
     * Push date query to post query and return post query
     *
     * @return PostQuery
     */
    public function push(){
        $this->postQuery->dateQuery($this->getVars());
        return $this->postQuery;
    }
    
    /**
     * Push date query to post query and return new date query
     *
     * @return PostQuery
     */
    public function next(){
        $this->push();
        
        return new PostDateQuery($this->postQuery);
    }
    
    /**
     * Push date query to post query and return new date query
     *
     * @return PostQuery
     */
    public function pushAnd(){
        $this->relation('AND');
        $this->push();
        
        return new PostDateQuery($this->postQuery);
    }
    
    /**
     * Push date query to post query and return new date query
     *
     * @return PostQuery
     */
    public function pushOr(){
        $this->relation('OR');
        $this->push();
        
        return new PostDateQuery($this->postQuery);
    }
    
}