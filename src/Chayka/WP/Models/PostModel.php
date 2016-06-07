<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Models;

use Chayka\Helpers\Util;
use Chayka\Helpers\JsonReady;
use Chayka\Helpers\InputReady;
use Chayka\Helpers\InputHelper;
use Chayka\Helpers\DateHelper;
use Chayka\WP\Helpers\AclHelper;
use Chayka\WP\Helpers\AclReady;
use Chayka\WP\Helpers\DbReady;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Queries\PostQuery;
use Chayka\WP\Queries\CommentQuery;
use Chayka\WP\Queries\PostTermQuery;
use DateTime;
use WP_Query;
use WP_Post;
use WP_Error;

/**
 * Class PostModel is a wrapper for WP_Post object
 *
 * @package Chayka\WP\Models
 */
class PostModel implements DbReady, JsonReady, InputReady, AclReady{

    /**
     * The WP_Query object that was used last to fetch posts from DB
     *
     * @var WP_Query
     */
    static $wpQuery;

    /**
     * The number of entries found using last query, that is used to render pagination
     *
     * @var int
     */
    static $postsFound;

    /**
     * The hash map of validation errors, part of InputReady interface implementation
     *
     * @var array
     */
    protected static $validationErrors = array();

    /**
     * Post id
     *
     * @var int
     */
    protected $id;

    /**
     * Post author user id
     *
     * @var int
     */
    protected $userId;

    /**
     * Parent post id
     *
     * @var int
     */
    protected $parentId;

    /**
     * Post guid
     *
     * @var string
     */
    protected $guid;

    /**
     * Post type
     *
     * @var string
     */
    protected $type;

    /**
     * Post slug (name) that is used as a part of the post url
     *
     * @var string
     */
    protected $slug;

    /**
     * Post title
     *
     * @var string
     */
    protected $title;

    /**
     * Post content
     *
     * @var string
     */
    protected $content;

    /**
     * Post content with applied filters
     * @deprecated
     * @var string
     */
    protected $contentFiltered;

    /**
     * Post excerpt (description)
     *
     * @var string
     */
    protected $excerpt;

    /**
     * Post status
     *
     * @var string
     */
    protected $status;

    /**
     * Post ping status
     *
     * @var string
     */
    protected $pingStatus;

    /**
     * Post password (for password protected posts)
     *
     * @var
     */
    protected $password;

    /**
     * URLs that need to be pinged.
     *
     * @var string
     */
    protected $toPing;

    /**
     * Get URLs pinged
     *
     * @var string
     */
    protected $pinged;

    /**
     * Get menu order property (used for ordering)
     *
     * @var int
     */
    protected $menuOrder;

    /**
     * Get mime type for 'attachment' post type
     *
     * @var string
     */
    protected $mimeType;

    /**
     * Post commenting status
     *
     * @var string
     */
    protected $commentStatus;

    /**
     * The number of post comments
     *
     * @var int
     */
    protected $commentCount;

    /**
     * An array to store post comments
     *
     * @var array
     */
    protected $comments;

    /**
     * Number of post reviews
     *
     * @var int
     */
    protected $reviewsCount;

    /**
     * An array to cache loaded post terms (tags / categories / other taxonomies)
     *
     * @var array
     */
    protected $terms;

    /**
     * An array to cache loaded meta values
     *
     * @var array
     */
    protected $meta;

    /**
     * An array to cache loaded image data in case of 'attachment' post type
     *
     * @var array
     */
    protected $imageData;

    /**
     * The id of thumbnail (post with 'attachment' type)
     *
     * @var int
     */
    protected $thumbnailId;

    /**
     * The date when post was created
     *
     * @var DateTime
     */
    protected $dtCreated;

    /**
     * The date when post was last modified
     *
     * @var DateTime
     */
    protected $dtModified;

    /**
     * Original WP post that is being wrapped by this model
     *
     * @var WP_Post
     */
    protected $wpPost;

    /**
     * An array that contains posts cached by post ids
     *
     * @var array
     */
    protected static $postsCacheById = array();

    /**
     * An array that contains posts cached by post slugs
     *
     * @var array
     */
    protected static $postsCacheBySlug = array();

    /**
     * An array that contains set of meta field name
     * that should be published when post is outputted in json
     *
     * @var array
     */
    protected static $jsonMetaFields = array();

    /**
     * PostModel constructor
     */
    public function __construct(){
        $void         = new \stdClass();
        $this->wpPost = new WP_Post($void);
        $this->init();
    }

    /**
     * Post model initializer
     */
    public function init(){
        $this->setId(0);
        $this->setDtCreated(new DateTime());
        $this->setStatus('draft');
        $this->setCommentStatus('open');
        $this->setPingStatus('closed');
    }

    /**
     * Get post id
     *
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set post id
     *
     * @param integer $id
     *
     * @return PostModel
     */
    public function setId($id){
        $this->id = $id;

        return $this;
    }

    /**
     * Get author user id
     *
     * @return integer
     */
    public function getUserId(){
        return $this->userId;
    }

    /**
     * Set author user id
     *
     * @param integer $userId
     *
     * @return PostModel
     */
    public function setUserId($userId){
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get parent-post id
     *
     * @return integer
     */
    public function getParentId(){
        return $this->parentId;
    }

    /**
     * Set parent-post id
     *
     * @param integer $parentId
     *
     * @return PostModel
     */
    public function setParentId($parentId){
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get post guid
     *
     * @return string
     */
    public function getGuid(){
        return $this->guid;
    }

    /**
     * Set post guid
     *
     * @param string $guid
     *
     * @return PostModel
     */
    public function setGuid($guid){
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get post type
     *
     * @return string
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Set post type
     *
     * @param string $type
     *
     * @return PostModel
     */
    public function setType($type){
        $this->type = $type;

        return $this;
    }

    /**
     * Get post name (slug)
     *
     * @return string
     */
    public function getSlug(){
        return $this->slug;
    }

    /**
     * Set post name (slug)
     *
     * @param string $slug
     *
     * @return PostModel
     */
    public function setSlug($slug){
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get post title
     *
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * Set post title
     *
     * @param string $title
     *
     * @return PostModel
     */
    public function setTitle($title){
        $this->title = $title;

        return $this;
    }

    /**
     * Get post content
     *
     * @param boolean $applyFilters
     *
     * @return string HTML content
     */
    public function getContent($applyFilters = true){
        return $applyFilters ? apply_filters('the_content', $this->content) : $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return PostModel
     */
    public function setContent($content){
        $this->content = $content;

        return $this;
    }

    /**
     * Get content with all registered 'the_content' filters applied
     *
     * @return string
     */
    public function getContentFiltered(){
        return apply_filters('the_content', $this->content);
    }

    /**
     * Get post excerpt that was set or generated one
     *
     * @param bool $generate set to true if you need excerpt autogeneration
     * @param bool $stripShortcodes set to true will strip shortcodes while generating excerpt
     *
     * @return string
     */
    public function getExcerpt($generate = true, $stripShortcodes = true){
        if($generate && ! $this->excerpt && $this->content){
            $text = $this->getContent();

            if($stripShortcodes){
                $text = strip_shortcodes($text);
            }

            $text           = apply_filters('the_content', $text);
            $text           = str_replace(']]>', ']]&gt;', $text);
            $excerpt_length = apply_filters('excerpt_length', 55);
            $excerpt_more   = apply_filters('excerpt_more', ' ' . '[...]');
            $text           = wp_trim_words($text, $excerpt_length, $excerpt_more);
            $this->excerpt  = wp_trim_excerpt($text);
        }

        return $this->excerpt;
    }

    /**
     * Set post excerpt
     *
     * @param string $excerpt
     *
     * @return PostModel
     */
    public function setExcerpt($excerpt){
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get post status
     *
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * Set post status (publish|draft|deleted|future)
     *
     * @param string $status
     *
     * @return PostModel
     */
    public function setStatus($status){
        $this->status = $status;

        return $this;
    }

    /**
     * Get post ping status
     *
     * @return string
     */
    public function getPingStatus(){
        return $this->pingStatus;
    }

    /**
     * Set ping status (closed|open)
     *
     * @param string $pingStatus
     *
     * @return PostModel
     */
    public function setPingStatus($pingStatus){
        $this->pingStatus = $pingStatus;

        return $this;
    }

    /**
     * Get post password
     *
     * @return string
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Set post password
     *
     * @param string $password
     *
     * @return PostModel
     */
    public function setPassword($password){
        $this->password = $password;

        return $this;
    }

    /**
     * Get the list of URLs to ping
     *
     * @return string
     */
    public function getToPing(){
        return $this->toPing;
    }

    /**
     * Set the list of URLs to ping
     *
     * @param string $toPing
     *
     * @return PostModel
     */
    public function setToPing($toPing){
        $this->toPing = $toPing;

        return $this;
    }

    /**
     * Get the list of URLs pinged
     *
     * @return string
     */
    public function getPinged(){
        return $this->pinged;
    }

    /**
     * Set the list of URLs pinged
     *
     * @param string $pinged
     *
     * @return PostModel
     */
    public function setPinged($pinged){
        $this->pinged = $pinged;

        return $this;
    }

    /**
     * Get post order mark
     *
     * @return integer
     */
    public function getMenuOrder(){
        return $this->menuOrder;
    }

    /**
     * Set post order mark
     *
     * @param integer $menuOrder
     *
     * @return PostModel
     */
    public function setMenuOrder($menuOrder){
        $this->menuOrder = $menuOrder;

        return $this;
    }

    /**
     * Get attachment mime type
     *
     * @return string
     */
    public function getMimeType(){
        return $this->mimeType;
    }

    /**
     * Set attachment mime type
     *
     * @param string $mimeType
     *
     * @return PostModel
     */
    public function setMimeType($mimeType){
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Check if post is attachment image
     *
     * @return boolean
     */
    public function isAttachmentImage(){
        return preg_match('%^image%', $this->getMimeType());
    }

    /**
     * Get comment status
     *
     * @return string
     */
    public function getCommentStatus(){
        return $this->commentStatus;
    }

    /**
     * Set comment status  (open|closed)
     *
     * @param string $commentStatus
     *
     * @return PostModel
     */
    public function setCommentStatus($commentStatus){
        $this->commentStatus = $commentStatus;

        return $this;
    }

    /**
     * Get comment count
     *
     * @return integer
     */
    public function getCommentCount(){
        return $this->commentCount;
    }

    /**
     * Set comment count
     *
     * @param integer $commentCount
     *
     * @return PostModel
     */
    public function setCommentCount($commentCount){
        $this->commentCount = $commentCount;

        return $this;
    }

    /**
     * Get post creation datetime
     *
     * @return DateTime
     */
    public function getDtCreated(){
        return $this->dtCreated;
    }

    /**
     * Set date creation datetime
     *
     * @param DateTime $dtCreated
     *
     * @return PostModel
     */
    public function setDtCreated($dtCreated){
        $this->dtCreated = $dtCreated;

        return $this;
    }

    /**
     * Get post creation datetime (GMT)
     *
     * @return DateTime
     */
    public function getDtCreatedGMT(){
        // TODO Check this GMT issue closely
        return new DateTime(get_gmt_from_date(DateHelper::datetimeToDbStr($this->getDtCreated())));
    }

    /**
     * Get post modification datetime
     *
     * @return DateTime
     */
    public function getDtModified(){
        return $this->dtModified;
    }

    /**
     * Set post modification datetime
     *
     * @param DateTime $dtModified
     *
     * @return PostModel
     */
    public function setDtModified($dtModified){
        $this->dtModified = $dtModified;

        return $this;
    }

    /**
     * Get post modification datetime (GMT)
     *
     * @return DateTime
     */
    public function getDtModifiedGMT(){
        return new DateTime(get_gmt_from_date(DateHelper::datetimeToDbStr($this->getDtModified())));
    }

    /**
     * Get count of page reviews.
     * Should be used in couple with incReviewsCount()
     *
     * @return integer
     */
    public function getReviewsCount(){
        if( ! $this->reviewsCount){
            $this->reviewsCount = $this->getMeta('reviews_count');
        }

        return $this->reviewsCount ? $this->reviewsCount : 0;
    }

    /**
     * Set post reviews count. Used for model only, no database modification made.
     * Use incReviewsCount() instead.
     *
     * @param integer $value
     *
     * @return PostModel
     */
    public function setReviewsCount($value){
        $this->reviewsCount = $value;

        return $this;
    }

    /**
     * Increases post reviews count by one.
     * Should be called in PostController::viewAction() for example.
     *
     * @return int
     */
    public function incReviewsCount(){
        if( ! $this->getId()){
            return 0;
        }
        $visited = Util::getItem($_SESSION, 'visited', array());
        if( ! isset($_SESSION['visited'])){
            $_SESSION['visited'] = array();
        }
        $today = date('Y-m-d');
        foreach($visited as $date => $posts){
            if($date != $today){
                unset($_SESSION['visited'][ $date ]);
            }
        }
        if( ! isset($_SESSION['visited'][ $today ])){
            $_SESSION['visited'][ $today ] = array();
        }

        $visit = Util::getItem($_SESSION['visited'][ $today ], $this->getId(), false);

        if( ! $visit){
            $this->getReviewsCount();
            $this->reviewsCount ++;
            $this->updateMeta('reviews_count', $this->reviewsCount);
            $_SESSION['visited'][ $today ][ $this->getId() ] = true;
        }

        return $this->reviewsCount;
    }

    /**
     * Checks if post has been already visited today.
     * Works in couple with incReviewsCount() only
     *
     * @return boolean
     */
    public function isVisited(){
        if( ! isset($_SESSION['visited'])){
            $_SESSION['visited'] = array();
        }
        $today = date('Y-m-d');
        if( ! isset($_SESSION['visited'][ $today ])){
            $_SESSION['visited'][ $today ] = array();
        }

        $visit = Util::getItem($_SESSION['visited'][ $today ], $this->getId(), false);

        return $visit;
    }

    /**
     * Get original WP_Post object if the one is preserved
     *
     * @return WP_Post
     */
    public function getWpPost(){
        return $this->wpPost;
    }

    /**
     * Set original WP_Post object to be preserved
     *
     * @param WP_Post|object $wpPost
     *
     * @return PostModel
     */
    public function setWpPost($wpPost){
        $this->wpPost = $wpPost;

        return $this;
    }

    /**
     * Magic getter that allows to use PostModel where wpPost should be used
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name){
        return Util::getItem($this->wpPost, $name);
    }

    /**
     * Get post href. Utilizes get_permalink().
     *
     * @return string
     */
    public function getHref(){
        return get_permalink($this->getId());
    }

    /**
     * Get href to the next WP post
     *
     * @param boolean $in_same_cat
     *
     * @return string
     */
    public function getHrefNext($in_same_cat = true){
        $post = get_next_post($in_same_cat);

        return $post && $post->ID ? get_permalink($post->ID) : null;
    }

    /**
     * Get href to the previous WP post
     *
     * @param boolean $in_same_cat
     *
     * @return string
     */
    public function getHrefPrev($in_same_cat = true){
        $post = get_previous_post($in_same_cat);

        return $post && $post->ID ? get_permalink($post->ID) : null;
    }

    /**
     * Get post edit link
     *
     * @param bool $checkPermissions
     * @param string $context
     *
     * @return string
     */
    public function getHrefEdit($checkPermissions = false, $context = 'display'){
        if('revision' === $this->getType()){
            $action = '';
        }elseif('display' == $context){
            $action = '&amp;action=edit';
        }else{
            $action = '&action=edit';
        }
        $post_type_object = get_post_type_object($this->getType());
        if( ! $post_type_object){
            return '';
        }
        if($checkPermissions && ! current_user_can('edit_post', $this->getId())){
            return '';
        }

        return apply_filters('get_edit_post_link', admin_url(sprintf($post_type_object->_edit_link . $action, $this->getId())), $this->getId(), $context);

    }

    /**
     * DbReady method, returns corresponding DB Table ID column name
     *
     * @return string
     */
    public static function getDbIdColumn(){
        return 'ID';
    }

    /**
     * DbReady method, returns corresponding DB Table name
     *
     * @return string
     */
    public static function getDbTable(){
        global $wpdb;

        return $wpdb->posts;
    }

    /**
     * Unpacks db record while fetching model from DB
     *
     * @param object $wpRecord
     *
     * @return PostModel
     */
    public static function unpackDbRecord($wpRecord){
        $obj = new self();
//		Util::print_r($wpRecord);
        $obj->setId(Util::getItem($wpRecord, 'ID'));
        $obj->setUserId(Util::getItem($wpRecord, 'post_author'));
        $obj->setParentId(Util::getItem($wpRecord, 'post_parent'));
        $obj->setGuid(Util::getItem($wpRecord, 'guid'));
        $obj->setType(Util::getItem($wpRecord, 'post_type'));
        $obj->setSlug(Util::getItem($wpRecord, 'post_name'));
        $obj->setTitle(Util::getItem($wpRecord, 'post_title'));
        $obj->setContent(Util::getItem($wpRecord, 'post_content'));
//        $obj->setContentFiltered(Util::getItem($wpRecord, 'post_content_filtered'));
        $obj->setExcerpt(Util::getItem($wpRecord, 'post_excerpt'));
        $obj->setStatus(Util::getItem($wpRecord, 'post_status'));
        $obj->setPingStatus(Util::getItem($wpRecord, 'ping_status'));
        $obj->setPinged(Util::getItem($wpRecord, 'pinged'));
        $obj->setToPing(Util::getItem($wpRecord, 'to_ping'));
        $obj->setPassword(Util::getItem($wpRecord, 'post_password'));
        $obj->setDtCreated(DateHelper::dbStrToDatetime(Util::getItem($wpRecord, 'post_date')));
        $obj->setDtModified(DateHelper::dbStrToDatetime(Util::getItem($wpRecord, 'post_modified')));
        $obj->setMenuOrder(Util::getItem($wpRecord, 'menu_order'));
        $obj->setMimeType(Util::getItem($wpRecord, 'post_mime_type'));
        $obj->setCommentStatus(Util::getItem($wpRecord, 'comment_status'));
        $obj->setCommentCount(Util::getItem($wpRecord, 'comment_count'));

        $obj->setWpPost($wpRecord);

        self::$postsCacheById[ $obj->getId() ]     = $obj;
        self::$postsCacheBySlug[ $obj->getSlug() ] = $obj->getId();

        return $obj;
    }

    /**
     * Packs model into assoc array before committing to DB
     *
     * @param boolean $forUpdate
     *
     * @return array
     */
    public function packDbRecord($forUpdate = true){
        $dbRecord = array();
        if($forUpdate){
            $dbRecord['ID'] = $this->getId();
        }
        if( ! empty($this->password)){
            $dbRecord['post_password'] = $this->getPassword();
        }
        $dbRecord['post_author']    = $this->getUserId();
        $dbRecord['post_parent']    = $this->getParentId();
        $dbRecord['post_type']      = $this->getType();
        $dbRecord['post_name']      = $this->getSlug();
        $dbRecord['post_title']     = $this->getTitle();
        $dbRecord['post_content']   = $this->getContent(false);
        $dbRecord['post_excerpt']   = $this->getExcerpt(false);
        $dbRecord['post_status']    = $this->getStatus();
        $dbRecord['post_date']      = DateHelper::datetimeToDbStr($this->getDtCreated());
        $dbRecord['post_date_gmt']  = DateHelper::datetimeToDbStr($this->getDtCreatedGMT());
        $dbRecord['ping_status']    = $this->getPingStatus();
        $dbRecord['to_ping']        = $this->getToPing();
        $dbRecord['pinged']         = $this->getPinged();
        $dbRecord['menu_order']     = $this->getMenuOrder();
        $dbRecord['comment_status'] = $this->getCommentStatus();

        return $dbRecord;
    }

    /**
     * Inserts new model to DB, returns autogenerated ID
     *
     * @return integer
     */
    public function insert(){
        $this->setDtCreated(new DateTime());
        $dbRecord = $this->packDbRecord(false);
        $id       = wp_insert_post($dbRecord);
        $this->setId($id);

        return $id;
    }

    /**
     * Update db record
     *
     * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
     */
    public function update(){
        $this->setDtModified(new DateTime());
        $dbRecord = $this->packDbRecord(true);
        unset($dbRecord['post_created']);
        unset($dbRecord['post_created_gmt']);

        return wp_update_post($dbRecord);
    }

    /**
     * Delete record form DB
     *
     * @param bool $forceDelete Whether to bypass trash and force deletion. Defaults to false.
     *
     * @return boolean False on failure
     */
    public function delete($forceDelete = false){
        return self::deleteById($this->getId(), $forceDelete);
    }

    /**
     * Deletes post with the specified $post from db table
     *
     * @param integer $postId
     * @param bool $forceDelete Whether to bypass trash and force deletion. Defaults to false.
     *
     * @return mixed False on failure
     */
    public static function deleteById($postId = 0, $forceDelete = false){
        $item = Util::getItem(self::$postsCacheById, $postId);
        if($item){
            unset(self::$postsCacheBySlug[ $item->getSlug() ]);
            unset(self::$postsCacheById[ $postId ]);
        }

        return wp_delete_post($postId, $forceDelete);
    }

    /**
     * Select model from DB by ID
     *
     * @param integer $id
     * @param boolean $useCache
     *
     * @return PostModel
     */
    public static function selectById($id = 0, $useCache = true){
        if($useCache && $id){
            $item = Util::getItem(self::$postsCacheById, $id);
            if($item){
                return $item;
            }
        }
        $wpRecord = get_post($id);

        return $wpRecord ? self::unpackDbRecord($wpRecord) : null;
    }


    /**
     * Select model from DB by slug
     *
     * @param string $slug
     * @param string $postType
     * @param bool $useCache
     * @param bool $isPreview
     *
     * @return PostModel
     */
    public static function selectBySlug($slug, $postType = 'ANY', $useCache = true, $isPreview = false){
        if($useCache){
            $id   = Util::getItem(self::$postsCacheBySlug, $slug);
            $item = Util::getItem(self::$postsCacheById, $id);
            if($item){
                return $item;
            }
        }
        $args = array('name' => $slug);
        if($postType){
            $args['post_type'] = $postType;
        }
        if($isPreview){
            $args['post_status'] = 'any';
            $args['preview']     = true;
        }else{
            $args['post_status'] = ['publish'];
            if(AclHelper::isAdmin()){
                $args['post_status'][] = 'private';
            }
        }
        $posts = self::selectPosts($args);

        return count($posts) ? reset($posts) : null;
    }

    /**
     * Selects post of specified post type by title.
     * The use of this function is not recommended as WP
     *
     * @global object $wpdb
     *
     * @param string $title
     * @param string $postType
     *
     * @return PostModel
     */
    public static function selectByTitle($title, $postType = 'ANY'){
        global $wpdb;
        $sql = $postType == 'ANY' ?
            DbHelper::prepare("
                SELECT * FROM $wpdb->posts
                WHERE post_title = %s AND post_status = 'publish'", $title
            ) :
            DbHelper::prepare("
                SELECT * FROM $wpdb->posts
                WHERE post_title = %s AND post_type = %s AND post_status = 'publish'", $title, $postType
            );

        $posts = self::selectSql($sql);

        return count($posts) ? reset($posts) : null;
    }

    /**
     * Get PostQuery object to create a query.
     * Call ->select() to fetch queried models;
     * The count of found rows can be found by calling postsFound() aftermath.
     *
     * @param boolean $globalImport set to true if you need import from $wp_query
     *
     * @return PostQuery
     */
    public static function query($globalImport = false){
        $query = new PostQuery($globalImport);

        return $query;
    }

    /**
     * Select models using WP_Query syntax.
     * The count of found rows can be found by calling postsFound() aftermath.
     *
     * @param array $wpPostsQueryArgs
     *
     * @return array(PostModel)
     */
    public static function selectPosts($wpPostsQueryArgs = array()){

        global $wp_query;

        if(empty($wpPostsQueryArgs)){
            if( ! self::$wpQuery){
                self::$wpQuery = $wp_query;
            }
        }else{
            self::$wpQuery = new WP_Query($wpPostsQueryArgs);
        }
        $posts            = array();
        self::$postsFound = self::$wpQuery->found_posts;
        while(self::$wpQuery->have_posts()){
            $dbRecord = self::$wpQuery->next_post();
            $posts[]  = self::unpackDbRecord($dbRecord);

        }

        return $posts;

    }

    /**
     * Select models using SQL query.
     * Should start with 'SELECT * FROM {$wpdb->posts}'
     * The count of found rows can be found by calling postsFound() aftermath.
     *
     * @global object $wpdb
     *
     * @param string $sql
     *
     * @return array(PostModel)
     */
    public static function selectSql($sql){
        $posts            = DbHelper::selectSql($sql, __CLASS__);
        self::$postsFound = DbHelper::rowsFound();

        return $posts;
    }

    /**
     * Get associated $wp_query if set
     *
     * @return WP_Query
     */
    public static function getWpQuery(){
        return self::$wpQuery;
    }

    /**
     * Get number of posts found using last mass fetch from DB
     *
     * @return integer
     */
    public static function postsFound(){
        return (int) max(self::$wpQuery ? self::$wpQuery->found_posts : 0, self::$postsFound);
    }

    /**
     * Get post meta single key-value pair or all key-values
     *
     * @param int $postId Post ID.
     * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
     * @param bool $single Whether to return a single value.
     *
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     */
    public static function getPostMeta($postId, $key = '', $single = true){
        $meta = get_post_meta($postId, $key, $single);
        if( ! $key && $single && $meta && is_array($meta)){
            $m = array();
            foreach($meta as $k => $values){
                $m[ $k ] = is_array($values) ? reset($values) : $values;
            }

            return $m;
        }

        return $meta;
    }

    /**
     * Update post meta value for the specified key in the DB
     *
     * @param integer $postId
     * @param string $key
     * @param string $value
     * @param string $oldValue
     *
     * @return bool False on failure, true if success.
     */
    public static function updatePostMeta($postId, $key, $value, $oldValue = ''){
        return update_post_meta($postId, $key, $value, $oldValue);
    }

    /**
     * Delete post meta value
     *
     * @param integer $postId
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public static function deletePostMeta($postId, $key, $value = ''){
        return delete_post_meta($postId, $key, $value);
    }

    /**
     * Get post meta single key-value pair or all key-values
     *
     * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
     * @param bool $single Whether to return a single value.
     *
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     */
    public function getMeta($key = '', $single = true){
        if($key){
            $k = $single ? $key : $key . '_arr';
            if( ! isset($this->meta[ $k ])){
                $this->meta[ $k ] = self::getPostMeta($this->getId(), $key, $single);
            }

            return $this->meta[ $k ];
        }else{
            return $this->meta = self::getPostMeta($this->getId(), $key, $single);
        }
    }

    /**
     * Update post meta value for the specified key in the DB
     * If value is empty then delete it
     *
     * @param string $key
     * @param string $value
     * @param string $oldValue
     *
     * @return bool False on failure, true if success.
     */
    public function updateOrDeleteMeta($key, $value = '', $oldValue = ''){
        if($value){
            return $this->updateMeta($key, $value, $oldValue);
        }else{
            return $this->deleteMeta($key, $oldValue);
        }
    }

    /**
     * Update post meta value for the specified key in the DB
     *
     * @param string $key
     * @param string $value
     * @param string $oldValue
     *
     * @return bool False on failure, true if success.
     */
    public function updateMeta($key, $value, $oldValue = ''){
        if($oldValue){
            unset($this->meta[ $key ]);
            unset($this->meta[ $key . '_arr' ]);
        }else{
            $this->meta[ $key ] = $value;
        }

        return self::updatePostMeta($this->getId(), $key, $value, $oldValue);
    }

    /**
     * Delete post meta value
     *
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function deleteMeta($key, $value = ''){
        unset($this->meta[ $key ]);
        unset($this->meta[ $key . '_arr' ]);

        return self::deletePostMeta($this->getId(), $key, $value);
    }

    /**
     * Get post terms. Should be set first by setTerms() or load by loadTerms() or queryTerms()
     * If taxonomy not set returns
     *
     * @param string|array $taxonomy
     *
     * @return array(string|TermModel)
     */
    public function getTerms($taxonomy = ''){
        return $taxonomy ? Util::getItem($this->terms, $taxonomy) : $this->terms;
    }

    /**
     * Set post terms
     *
     * @param array(string|WP_Term|TermModel) $terms
     * @param string $taxonomy taxonomy
     *
     * @return PostModel
     */
    public function setTerms($terms, $taxonomy = null){
        if($taxonomy){
            $this->terms[ $taxonomy ] = $terms;
        }else{
            $this->terms = $terms;
        }

        return $this;
    }

    /**
     * Select terms for the specified postId and taxonomy
     *
     * @param integer $postId
     * @param string|array(string) $taxonomy
     * @param array|PostTermQuery $args
     *
     * @return array(TermModel)
     */
    public static function selectTerms($postId, $taxonomy = 'post_tag', $args = array()){
        if($args instanceof PostTermQuery){
            $args = $args->getVars();
        }
        $terms = wp_get_post_terms($postId, $taxonomy, $args);
        if(in_array(Util::getItem($args, 'fields', 'names'), array('all', 'all_with_object_id'))){
            $dbRecords = $terms;
            $terms     = array();
            foreach($dbRecords as $dbRecord){
                $term = TermModel::unpackDbRecord($dbRecord);
                if($term){
                    $terms[] = $term;
                }
            }
        }

        return $terms;
    }

    /**
     * Load terms for this post and taxonomy.
     * Utilizes selectTerms
     *
     * @param string|array(string) $taxonomies
     * @param array|PostTermQuery $args
     *
     * @return array(TermModel)
     */
    public function loadTerms($taxonomies = '', $args = array('fields' => 'names')){
        if($taxonomies){
            if(is_string($taxonomies) && strpos($taxonomies, ',')){
                $taxonomies = preg_split('%\s*,\s*%', $taxonomies);
            }
            if(is_array($taxonomies)){
                foreach($taxonomies as $t){
                    $this->terms[ $t ] = self::selectTerms($this->getId(), $t, $args);
                }
            }else{
                $this->terms[ $taxonomies ] = self::selectTerms($this->getId(), $taxonomies, $args);
            }
        }else{
            $taxonomies = $this->getTaxonomies();
            foreach($taxonomies as $t){
                $this->terms[ $t ] = self::selectTerms($this->getId(), $t, $args);
            }
        }

        return $this->terms;
    }

    /**
     * Update set of post's terms in DB
     *
     * @param array(string|int|WP_Term|TermModel) $terms if omitted $this->getTerms($taxonomy) is taken
     * @param string $taxonomy if omitted $terms should be like array('post_tag' => ... , 'category' => ... )
     * @param boolean $append append or replace
     */
    public function updateTerms($terms = null, $taxonomy = null, $append = false){
        if( ! $terms){
            $terms = $this->getTerms($taxonomy);
            Util::print_r($terms);
        }
        if( ! $taxonomy){
            foreach($terms as $taxonomy => $trms){
                $this->updateTerms($trms, $taxonomy, $append);
            }
        }else{
            $trms = $terms;
            if(is_array($terms) && count($terms)){
                if(is_object(reset($terms))){
                    $trms = array();
                    foreach($terms as $key => $value){
                        $trms[ $key ] = is_taxonomy_hierarchical($taxonomy) ? $value->term_id : $value->name;
                    }
                }
            }
            wp_set_post_terms($this->getId(), $trms, $taxonomy, $append);
        }
    }

    /**
     * Get PostTermQuery object to query post terms.
     * Call ->select() at the end to load terms into this post
     *
     * @param string|array(string) $taxonomies
     *
     * @return PostTermQuery
     */
    public function queryTerms($taxonomies = null){
        return PostTermQuery::query($this, $taxonomies);
    }

    /**
     * Get taxonomy identifiers associated with this post type
     * @return array(string)
     */
    public function getTaxonomies(){
        $taxonomies = get_taxonomies(array(), 'objects');
        $res        = array();
        foreach($taxonomies as $name => $taxonomy){
            if(in_array($this->getType(), $taxonomy->object_type)){
                $res[] = $name;
            }
        }

        return $res;
    }

    /**
     * Get post comments. Should set first by setComments() or load by loadComments() or queryComments()
     *
     * @return array(CommentModel)
     */
    public function getComments(){
        return $this->comments;
    }

    /**
     * Set post comments
     *
     * @param array(CommentModel) $comments
     *
     * @return PostModel
     */
    public function setComments($comments){
        $this->comments = $comments;

        return $this;
    }

    /**
     * Load post comments into the post object
     *
     * @param array $args WP_Comment_Query args
     *
     * @return integer count of comments loaded
     */
    public function loadComments($args = array()){
        $args['post_id'] = $this->getId();
        $defaults        = array(
            'order'   => 'ASC',
            'orderby' => 'comment_ID'
        );
        $args            = array_merge($defaults, $args);
        $this->comments  = CommentModel::selectComments($args);

        return count($this->comments);
    }

    /**
     * Get CommentQuery object to query this post comments.
     * Call ->select() at the end to load comments into this model.
     *
     * @return CommentQuery
     */
    public function queryComments(){
        return CommentQuery::query($this)
                           ->order_ASC()
                           ->orderBy('comment_ID');
    }

    /**
     * Get the list of media attachments
     *
     * @param string $mimeType
     *
     * @return array(PostModel)
     */
    public function getAttachments($mimeType){
        $rawAttachments = get_attached_media($mimeType, $this->getId());
        $attachments    = array();
        foreach($rawAttachments as $id => $raw){
            $attachments[ $id ] = PostModel::unpackDbRecord($raw);
        }

        return $attachments;
    }

    /**
     * Get attachment url
     *
     * @return string
     */
    public function getAttachmentUrl(){
        return wp_get_attachment_url($this->getId());
    }

    /**
     * Get image data in case this post is an attachment image.
     * Should be set by setImageData or loaded by loadImageData().
     * loadImageData can be used instead
     *
     * @return mixed
     */
    public function getImageData(){
        return $this->imageData;
    }

    /**
     * Set image data in case this post is an attachment image.
     * Use within the model only.
     *
     * @param array $imageData
     *
     * @return PostModel
     */
    public function setImageData($imageData){
        $this->imageData = $imageData;

        return $this;
    }

    /**
     * Loads image data if this post is an attachment
     *
     * @param string $size thumbnail|medium|large|full
     *
     * @return array
     */
    public function loadImageData($size = ''){
        if($this->getType() == 'attachment'){
            $sizes = array();
            if($size){
                $sizes[ $size ] = wp_get_attachment_image_src($this->getId(), 'icon' == $size ? 'thumbnail' : $size, 'icon' == $size);
            }else{
                if($this->isAttachmentImage()){
                    foreach(array('thumbnail', 'medium', 'large', 'full') as $sz){
                        $d = wp_get_attachment_image_src($this->getId(), $sz);
                        if($d){
                            $sizes[ $sz ] = $d;
                        }
                    }
                }else{
                    $sizes['thumbnail'] = wp_get_attachment_image_src($this->getId(), 'thumbnail', true);
                }
//                $sizes['icon'] = wp_get_attachment_image_src( $this->getId(), "thumbnail", true);
            }

            foreach($sizes as $sz => $data){
                $this->imageData[ $sz ] = array(
                    'url'    => $data[0],
                    'width'  => $data[1],
                    'height' => $data[2],
                );
            }
        }

        return $size ? Util::getItem($this->imageData, $size) : $this->imageData;
    }

    /**
     * Get post thumbnail id (thumbnail is an attachment post associated with this post)
     *
     * @return integer
     */
    public function getThumbnailId(){
        if( ! $this->thumbnailId){
            $this->thumbnailId = get_post_thumbnail_id($this->getId());
        }

        return $this->thumbnailId ? $this->thumbnailId : 0;
    }

    /**
     * Get thumbnail image HTML code (<img src="..."/>)
     * of the specified size and with HTML attributes
     *
     * @param string $size thumbnail|post-thumbnail|medium|large|full|<custom>
     * @param array [key]=value $attrs
     *
     * @return string(html)
     */
    public function getThumbnailImage($size = 'post-thumbnail', $attrs = array()){
        return get_the_post_thumbnail($this->getId(), $size, $attrs);
    }

    /**
     * Get thumbnail image HTML code (<img src="..."/>)
     * of the specified size and with HTML attributes
     *
     * @param array [key]=value $attrs
     *
     * @return string(html)
     */
    public function getThumbnailImage_Medium($attrs = array()){
        return $this->getThumbnailImage('medium', $attrs);
    }

    /**
     * Get thumbnail image HTML code (<img src="..."/>)
     * of the specified size and with HTML attributes
     *
     * @param array [key]=value $attrs
     *
     * @return string(html)
     */
    public function getThumbnailImage_Large($attrs = array()){
        return $this->getThumbnailImage('large', $attrs);
    }

    /**
     * Get thumbnail image HTML code (<img src="..."/>)
     * of the specified size and with HTML attributes
     *
     * @param array [key]=value $attrs
     *
     * @return string(html)
     */
    public function getThumbnailImage_Full($attrs = array()){
        return $this->getThumbnailImage('full', $attrs);
    }

    /**
     * Get thumbnail image data (url, width, height, resized)
     * of the specified size
     *
     * @param string $size thumbnail|post-thumbnail|medium|large|full|<custom>
     *
     * @return array[key]=value
     */
    public function getThumbnailData($size = 'thumbnail'){
        $attId = $this->getThumbnailId();
        if( ! $attId){
            return null;
        }
        $image = wp_get_attachment_image_src($attId, $size);
        list($url, $width, $height, $resized) = $image;

        return array(
            'url'     => $url,
            'width'   => $width,
            'height'  => $height,
            'resized' => $resized,
        );
        //thumbnail, medium, large or full
    }

    /**
     * Get thumbnail image data (url, width, height, resized)
     * of the specified size
     *
     * @return array[key]=value
     */
    public function getThumbnailData_Thumbnail(){
        return $this->getThumbnailData('thumbnail');
    }

    /**
     * Get thumbnail image data (url, width, height, resized)
     * of the specified size
     *
     * @return array[key]=value
     */
    public function getThumbnailData_Medium(){
        return $this->getThumbnailData('medium');
    }

    /**
     * Get thumbnail image data (url, width, height, resized)
     * of the specified size
     *
     * @return array[key]=value
     */
    public function getThumbnailData_Large(){
        return $this->getThumbnailData('large');
    }

    /**
     * Get thumbnail image data (url, width, height, resized)
     * of the specified size
     *
     * @return array[key]=value
     */
    public function getThumbnailData_Full(){
        return $this->getThumbnailData('full');
    }

    /**
     * Populates this post for old school WP use.
     * Defines global variables $post, $authordata, $wp_the_query, etc.
     *
     * @global WP_Post $post
     * @global WP_Query $wp_the_query
     * @return null|WP_Post
     */
    public function populateWpGlobals(){
        global $post, $wp_the_query;
        $post = $this->getWpPost();
        setup_postdata($post);
        $comments = $this->getComments() ? $this->getComments() : array();
        foreach($comments as $comment){
            /**
             * @var CommentModel $comment
             */
            $wp_the_query->comments[] = $comment->getWpComment();
        }
        $wp_the_query->comment_count = $this->getCommentCount();

        return $post;
    }

    /**
     * Set meta fields that should be populated to json
     *
     * @param array(string) $metaFields
     */
    public static function setJsonMetaFields($metaFields){
        self::$jsonMetaFields = $metaFields;
    }

    /**
     * Add meta field name that should be populated to json
     *
     * @param string $fieldName
     */
    public static function addJsonMetaField($fieldName){
        if(false === array_search($fieldName, self::$jsonMetaFields)){
            self::$jsonMetaFields[] = $fieldName;
        }
    }

    /**
     * Remove meta field name that should not be populated to json
     *
     * @param string $fieldName
     */
    public static function removeJsonMetaField($fieldName){
        $i = array_search($fieldName, self::$jsonMetaFields);
        if(false !== $i){
            self::$jsonMetaFields = array_splice(self::$jsonMetaFields, $i, 1);
        }
    }

    /**
     * Packs this post into assoc array for JSON representation.
     * Used for API Output
     *
     * @return array
     */
    public function packJsonItem(){
        $jsonItem                   = array();
        $jsonItem['id']             = (int) $this->getId();
        $jsonItem['post_author']    = $this->getUserId();
        $jsonItem['post_parent']    = $this->getParentId();
        $jsonItem['post_type']      = $this->getType();
        $jsonItem['post_name']      = $this->getSlug();
        $jsonItem['post_title']     = $this->getTitle();
        $jsonItem['post_content']   = $this->getContent();
        $jsonItem['post_excerpt']   = $this->getExcerpt();
        $jsonItem['post_status']    = $this->getStatus();
        $jsonItem['post_date']      = DateHelper::datetimeToDbStr($this->getDtCreated());
        $jsonItem['post_date_gmt']  = DateHelper::datetimeToDbStr($this->getDtCreatedGMT());
        $jsonItem['ping_status']    = $this->getPingStatus();
        $jsonItem['to_ping']        = $this->getToPing();
        $jsonItem['pinged']         = $this->getPinged();
        $jsonItem['menu_order']     = $this->getMenuOrder();
        $jsonItem['comment_status'] = $this->getCommentStatus();
        $jsonItem['comment_count']  = $this->getCommentCount();
        $jsonItem['reviews_count']  = $this->getReviewsCount();
        $jsonItem['post_mime_type'] = $this->getMimeType();
        $jsonItem['terms']          = $this->getTerms();
        $jsonItem['href']           = $this->getHref();
        if('attachment' == $this->getType()){
            if(empty($this->imageData)){
                $this->loadImageData();
            }
            $jsonItem['image'] = $this->imageData;
        }
        $thumbId                  = $this->getThumbnailId();
        $jsonItem['thumbnail_id'] = $thumbId;
        if($thumbId){
            $thumb                 = array(
                'thumbnail' => $this->getThumbnailData_Thumbnail(),
                'medium'    => $this->getThumbnailData_Medium(),
                'large'     => $this->getThumbnailData_Large(),
                'full'      => $this->getThumbnailData_Full(),
            );
            $jsonItem['thumbnail'] = $thumb;
        }
        $meta = array();
        foreach(self::$jsonMetaFields as $field){
            $meta[ $field ] = $this->getMeta($field);
        }
        if($meta){
            $jsonItem['meta'] = $meta;
        }

        return $jsonItem;
    }

    /**
     * Get validation errors after unpacking from request input
     * Should be set by validateInput
     *
     * @return array[field]='Error Text'
     */
    public static function getValidationErrors(){
        return static::$validationErrors;
    }

    /**
     * Add validation errors after unpacking from request input
     *
     * @param array [field]='Error Text' $errors
     *
     * @return mixed|void all validation errors
     */
    public static function addValidationErrors($errors){
        return static::$validationErrors = array_merge(static::$validationErrors, $errors);
    }

    /**
     * Unpacks request input.
     * Used by REST Controllers.
     *
     * @param array $input
     *
     * @return PostModel
     */
    public static function unpackJsonItem($input = array()){
        if(empty($input)){
            $input = InputHelper::getParams();
        }

        $id = Util::getItem($input, 'id', 0);

        $obj = $id ? static::selectById($id) : new static();

        $valid = static::validateInput($input, $id ? $obj : null);

        if($valid){
            $input = array_merge($obj->packJsonItem(), $input);

            $obj->setUserId(Util::getItem($input, 'post_author'));
            $obj->setParentId(Util::getItem($input, 'post_parent'));
            $obj->setGuid(Util::getItem($input, 'guid'));
            $obj->setType(Util::getItem($input, 'post_type'));
            $obj->setSlug(Util::getItem($input, 'post_name'));
            $obj->setTitle(Util::getItem($input, 'post_title'));
            $obj->setContent(Util::getItem($input, 'post_content'));
            $obj->setExcerpt(Util::getItem($input, 'post_excerpt'));
            $obj->setStatus(Util::getItem($input, 'post_status'));
            $obj->setPingStatus(Util::getItem($input, 'ping_status'));
            $obj->setPinged(Util::getItem($input, 'pinged'));
            $obj->setToPing(Util::getItem($input, 'to_ping'));
            $obj->setPassword(Util::getItem($input, 'post_password'));
            $obj->setDtModified(DateHelper::jsonStrToDatetime(Util::getItem($input, 'post_modified')));
            $obj->setMenuOrder(Util::getItem($input, 'menu_order'));
            //        $obj->setMimeType(Util::getItem($input, 'post_mime_type'));
            $obj->setCommentStatus(Util::getItem($input, 'comment_status'));

            return $obj;
        }

        return null;
    }

    /**
     * Validates input and sets $validationErrors
     *
     * @param array $input
     * @param PostModel $oldState
     *
     * @return bool is input valid
     */
    public static function validateInput($input = array(), $oldState = null){
        static::$validationErrors = array();
        $valid                    = apply_filters('PostModel.validateInput', true, $input, $oldState);

        return $valid;
    }

    /**
     * Flushes cache used for selectById() and selectBySlug
     */
    public static function flushCache(){
        self::$postsCacheById   = array();
        self::$postsCacheBySlug = array();
    }

    /**
     * Get post by $id from cache.
     * It gets to cache once it was unpacked by unpackDbRecord()
     *
     * @param integer $id
     *
     * @return PostModel
     */
    public static function getPostsCacheById($id = 0){
        if($id){
            return Util::getItem(self::$postsCacheById, $id);
        }

        return null;
    }

    /**
     * Get post by $slug from cache.
     * It gets to cache once it was unpacked by unpackDbRecord()
     *
     * @param string $slug
     *
     * @return PostModel
     */
    public static function getPostsCacheBySlug($slug = ''){
        if($slug){
            $id = Util::getItem(self::$postsCacheBySlug, $slug);

            return $id ? self::getPostsCacheById($id) : null;
        }

        $ret = array();

        foreach(self::$postsCacheBySlug as $slug => $id){
            $item = $id ? self::getPostsCacheById($id) : null;
            if($item){
                $ret[ $slug ] = $id;
            }
        }

        return $ret;
    }

    /**
     * Check if current $user has $privilege over this post,
     * part of AclReady interface implementation
     *
     * @param string $privilege
     * @param \Chayka\WP\Models\UserModel|null $user
     *
     * @return boolean
     */
    public function userCan($privilege, $user = null){
        if( ! $user){
            $user = UserModel::currentUser();
        }
        $userCan = true;
        $errors  = array();

        $isOwner     = $this->getUserId() == $user->getId();
        $isPage      = $this->getType() == 'page';
        $publish     = $this->getStatus() == 'publish' || $this->getType() == 'attachment' && $this->getStatus() == 'inherit';
        $isPrivate   = $this->getStatus() == 'private';
        $isProtected = $this->getId() && post_password_required($this->getId());

        $permissions = array();

        switch($privilege){
            case 'create':
                $permissions[] = 'edit_posts';
                if($publish){
                    $permissions[] = 'publish_posts';
                }
                if( ! $isOwner){
                    $permissions[] = 'edit_others_posts';
                    if($isPrivate){
                        $permissions[] = 'edit_private_posts';
                    }
                }
                break;
            case 'read':
                if( ! $publish || $isProtected){
                    if( ! $isOwner){
                        $permissions[] = 'edit_others_posts';
                        if($isPrivate){
                            $permissions[] = 'read_private_posts';
                        }
                    }
                }
                break;
            case 'update':
                $permissions[] = 'edit_posts';
                if($publish){
                    $permissions[] = 'edit_published_posts';
                }
                if( ! $isOwner){
                    $permissions[] = 'edit_others_posts';
                    if($isPrivate){
                        $permissions[] = 'edit_private_posts';
                    }
                }
                break;
            case 'delete':
                $permissions[] = 'delete_posts';
                if($publish){
                    $permissions[] = 'delete_published_posts';
                }
                if( ! $isOwner){
                    $permissions[] = 'delete_others_posts';
                    if($isPrivate){
                        $permissions[] = 'delete_private_posts';
                    }
                }
                break;
            default:

        }

        foreach($permissions as $perm){
            if($isPage){
                $perm = str_replace('post', 'page', $perm);
            }
            $userCan &= user_can($user->getWpUser(), $perm);
            if( ! $userCan){
                $errors['permission_required'] = 'Permission ' . $perm . ' required to ' . $privilege . ' post';
                break;
            }
        }

        $userCan = apply_filters('PostModel.' . $privilege, $userCan, $this, $user);
        if( ! $userCan){
            static::addValidationErrors($errors);
        }

        return $userCan;
    }
}
