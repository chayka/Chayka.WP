<?php

namespace Chayka\WP\Models;

use Chayka\Helpers\Util;
use Chayka\Helpers\JsonReady;
use Chayka\Helpers\InputReady;
use Chayka\Helpers\InputHelper;
use Chayka\Helpers\DateHelper;
use Chayka\WP\Helpers\AclReady;
use Chayka\WP\Helpers\DbReady;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\AclHelper;
use Chayka\WP\Helpers\NlsHelper;
use Chayka\WP\Queries\CommentQuery;
use DateTime;

//require_once 'helpers/JsonHelper.php';
//require_once 'helpers/InputHelper.php';
//require_once 'helpers/DbHelper.php';
//require_once 'models/posts/CommentQuery.php';

/**
 * Description of CommentModel
 *
 * @author borismossounov
 */
class CommentModel implements DbReady, JsonReady, InputReady, AclReady{

    protected $id;
    protected $postId;
    protected $parentId;
    protected $userId;
    protected $author;
    protected $email;
    protected $url;
    protected $ip;
    protected $dtCreated;
    protected $dtCreatedGMT;
    protected $content;
    protected $karma;
    protected $karmaDelta;
    protected $isApproved;
    protected $agent;
    protected $type;

    protected $wpComment;

    protected static $validationErrors = array();

    protected static $commentsCacheById = array();
    protected static $commentsCacheByPostId = array();
    protected static $jsonMetaFields = array();

    public function __construct() {
        $this->init();
    }

    /**
     * Initialize comment with all the known data (user, ip, etc.)
     */
    public function init(){
        $this->setId(0);
        $date = new DateTime();
        $this->setDtCreated($date);
        $user = UserModel::currentUser();
        if($user && $user->getId()){
            $this->setUserId($user->getId());
            $this->setAuthor($user->getDisplayName());
            $this->setEmail($user->getEmail());
            $this->setUrl($user->getUrl());
        }
        $this->setIsApproved(0);
        $this->setKarma(0);
    }
    
    /**
     * Get comment id
     * 
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set comment id
     * 
     * @param integer $id
     * @return CommentModel
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get comment post id
     * 
     * @return integer
     */
    public function getPostId() {
        return $this->postId;
    }

    /**
     * Set post comment id
     * 
     * @param integer $postId
     * @return CommentModel
     */
    public function setPostId($postId) {
        $this->postId = $postId;
        return $this;
    }

    /**
     * Get post author user id
     * 
     * @return integer
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set post author user id
     * 
     * @param integer $userId
     * @return CommentModel
     */
    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }
    
    /**
     * Get author UserModel object
     * 
     * @return UserModel
     */
    public function getUser(){
        return $this->getUserId()?UserModel::selectById($this->getUserId()):null;
    }

    /**
     * Get author name.
     * If userId is set and the corresponding UserModel exists method returns
     * display name (if set) or user login.
     * If userId not set or UserModel was deleted method returns comment_author field.
     * 
     * @return string
     */
    public function getAuthor() {
        $user = $this->getUser();
        if($user && $user->getId()){
            return $user->getDisplayName()?$user->getDisplayName():$user->getLogin();
        }
        return $this->author;
    }

    /**
     * Set author name
     * 
     * @param string $author
     * @return CommentModel
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }
    
    /**
     * Get author email.
     * If userId is set and the corresponding UserModel exists method returns
     * user email.
     * If userId not set or UserModel was deleted method returns comment_author_email field.
     * 
     * @return string
     */
    public function getEmail() {
        $user = $this->getUser();
        if($user && $user->getId()){
            return $user->getEmail();
        }
        return $this->email;
    }

    /**
     * Set author email.
     * 
     * @param string $email
     * @return CommentModel
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get author url.
     * If userId is set and the corresponding UserModel exists method returns
     * user url.
     * If userId not set or UserModel was deleted method returns comment_author_url field.
     * 
     * @return string
     */
    public function getUrl() {
        $user = $this->getUser();
        if($user && $user->getId()){
            return $user->getUrl();
        }
        return $this->url;
    }

    /**
     * Set author url
     * 
     * @param string $url
     * @return CommentModel
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * Get author ip
     * 
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set comment ip
     * 
     * @param string $ip
     * @return CommentModel
     */
    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get comment creation datetime
     * 
     * @return DateTime
     */
    public function getDtCreated() {
        return $this->dtCreated;
    }

    /**
     * Set comment creation datetime
     * 
     * @param DateTime $dtCreated
     * @return CommentModel
     */
    public function setDtCreated($dtCreated) {
        $this->dtCreated = $dtCreated;
        return $this;
    }

    /**
     * Get post creation time (GMT)
     * 
     * @return DateTime
     */
    public function getDtCreatedGMT() {
        // TODO: check function
        return new DateTime(get_gmt_from_date(DateHelper::datetimeToDbStr($this->getDtCreated())));
    }

    /**
     * Get comment content
     * 
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set plain text comment content
     * 
     * @param string $content
     * @return CommentModel
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Get comment karma
     * 
     * @return integer
     */
    public function getKarma() {
        return $this->karma;
    }

    /**
     * Set karma. Used within model only.
     * If you need to adjust karma use voteUp() and voteDown() or vote() instead.
     * 
     * @param integer $karma
     * @return CommentModel
     */
    public function setKarma($karma) {
        $this->karma = $karma;
        return $this;
    }
    
    /**
     * Get current user karma delta (upvote or downvote) for today
     * 
     * @return int
     */
    public function getKarmaDelta(){
        if(!$this->getId()){
            return 0;
        }

        $votes = Util::getItem($_SESSION, 'comment_votes', array());
        $today = date('Y-m-d/').get_current_user_id();
        foreach ($votes as $date => $comments) {
            if($date != $today){
                unset($_SESSION['comment_votes'][$date]);
            }
        }
        if(empty($_SESSION['comment_votes'][$today])){
            $_SESSION['comment_votes'][$today] = array();
        }
        
        return Util::getItem($_SESSION['comment_votes'][$today], $this->getId(), 0);
    }
    
    /**
     * Set comment karma delta.
     * Used within model only.
     * Use voteUp() and voteDown() or vote() instead
     * 
     * @param integer $delta
     * @return CommentModel
     */
    public function setKarmaDelta($delta){
        if(!$this->getId()){
            return $this;
        }
        $today = date('Y-m-d/').get_current_user_id();
        $votes = Util::getItem($_SESSION, 'comment_votes', array());
        foreach ($votes as $date => $comments) {
            if($date != $today){
                unset($_SESSION['comment_votes'][$date]);
            }
        }
        if(empty($_SESSION['comment_votes'][$today])){
            $_SESSION['comment_votes'][$today] = array();
        }
        
        $_SESSION['comment_votes'][$today][$this->getId()] = $delta;
        return $this;
    }
    
    /**
     * Vote for comment.
     * 
     * @param integer $delta. Positive value auto-adjusted to +1, negative to -1
     * @return integer
     */
    public function vote($delta){
        $wpdb = DbHelper::wpdb();

        if(!$this->getId()){
            return 0;
        }
        
        $vote = $this->getKarmaDelta();
        if($delta > 1){
            $delta = 1;
        }elseif($delta < -1){
            $delta = -1;
        }
//        printf('[vote: %d, delta: %d]', $vote, $delta);
        
        if(($delta > 0 && $vote <=0) 
        || ($delta < 0 && $vote >=0)){
            $table = self::getDbTable();
            $idCol = self::getDbIdColumn();
            $sql = DbHelper::prepare("
                UPDATE $table
                SET comment_karma = comment_karma + (%d)
                WHERE $idCol = %d
                ", $delta, $this->getId());
            if($wpdb->query($sql)){
                $sqlKarma = DbHelper::prepare("
                SELECT comment_karma FROM $table
                WHERE $idCol = %d
                ", $this->getId());
                $this->setKarma($wpdb->get_var($sqlKarma));
                $this->setKarmaDelta($vote+$delta);
                return $delta;
            }
        }
        
        return 0;
    }
    
    /**
     * Vote up for comment
     * 
     * @return integer
     */
    public function voteUp(){
        return $this->vote(1);
    }
    
    /**
     * Vote down
     * 
     * @return integer
     */
    public function voteDown(){
        return $this->vote(-1);
    }
    
    /**
     * Is comment approved
     * 
     * @return int|string 0|1|'spam'
     */
    public function getIsApproved() {
        return $this->isApproved==='spam'?$this->isApproved:(int)$this->isApproved;
    }

    /**
     * Set approved flag
     * 
     * @param int|string $isApproved 0|1|'spam'
     * @return CommentModel
     */
    public function setIsApproved($isApproved) {
        $this->isApproved = $isApproved==='spam'?$isApproved:(int)$isApproved;
        return $this;
    }

    /**
     * Get comment user-agent (browser signature)
     * 
     * @return string
     */
    public function getAgent() {
        return $this->agent;
    }

    /**
     * Set comment user-agent (browser signature)
     * 
     * @param string $agent
     * @return CommentModel
     */
    public function setAgent($agent) {
        $this->agent = $agent;
        return $this;
    }

    /**
     * Get comment type (empty by default)
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set comment type
     * 
     * @param string $type
     * @return CommentModel
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * Get parent comment id
     * 
     * @return integer
     */
    public function getParentId() {
        return $this->parentId;
    }

    /**
     * Set parent comment id
     * 
     * @param integer $parentId
     * @return CommentModel
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * Get original WP_Comment (if preserved)
     * 
     * @return object
     */
    public function getWpComment() {
        return $this->wpComment;
    }

    /**
     * Preserve original WP_Comment
     * 
     * @param object $wpComment
     * @return CommentModel
     */
    public function setWpComment($wpComment) {
        $this->wpComment = $wpComment;
        return $this;
    }

	/**
	 * Magic getter that allows to use CommentModel where wpComment should be used
	 *
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
		return Util::getItem($this->wpComment, $name);
	}
    /**
     * DbReady method, returns corresponding DB Table ID column name
     * 
     * @return string
     */
    
    public static function getDbIdColumn() {
        return 'comment_ID';
    }

    /**
     * DbReady method, returns corresponding DB Table name
     * 
     * @return string
     */
    public static function getDbTable() {
        global $wpdb;
        return $wpdb->comments;
    }

    /**
     * Get validation errors after unpacking from request input
     * Should be set by validateInput
     * 
     * @return array[field]='Error Text'
     */
    public static function getValidationErrors() {
        return static::$validationErrors;
    }

	/**
	 * Add validation errors after unpacking from request input
	 *
	 * @param array[field]='Error Text' $errors
	 */
	public static function addValidationErrors($errors) {
		static::$validationErrors = array_merge(static::$validationErrors, $errors);
	}

	/**
	 * Unpacks request input.
	 * Used by REST Controllers.
	 *
	 * @param array $input
	 *
	 * @return JsonReady|void
	 */
    public static function unpackJsonItem($input = array()) {
        if(empty($input)){
            $input = InputHelper::getParams();
        }
	    $id = Util::getItem($input, 'id', 0);

	    $obj = $id? static::selectById($id): new static();

	    $valid = static::validateInput($input, $id? $obj:null);

	    if($valid) {
		    $input = array_merge( $obj->packJsonItem(), $input );

		    $obj->setContent( Util::getItem( $input, 'comment_content' ) );

		    if ( ! $obj->getId() ) {
			    $obj->setPostId( Util::getItem( $input, 'comment_post_ID' ) );

			    $parentId     = Util::getItem( $input, 'comment_parent', 0 );
			    $parentStatus = ( 0 < $parentId ) ? wp_get_comment_status( $parentId ) : '';
			    $obj->setParentId( ( 'approved' == $parentStatus || 'unapproved' == $parentStatus ) ? $parentId : 0 );

			    $user = UserModel::currentUser();
			    if ( $user && $user->getId() ) {
				    $obj->setUserId( $user->getId() );
				    $obj->setAuthor( $user->getDisplayName() ? $user->getDisplayName() : $user->getLogin() );
				    $obj->setEmail( $user->getEmail() );
				    $obj->setUrl( $user->getUrl() );
			    } else {
				    $obj->setUserId( 0 );
				    $obj->setAuthor( Util::getItem( $input, 'comment_author' ) );
				    $obj->setEmail( Util::getItem( $input, 'comment_author_email' ) );
				    $obj->setUrl( Util::getItem( $input, 'comment_author_url' ) );
			    }
			    $obj->setType( Util::getItem( $input, 'comment_type', '' ) );
			    $dbRec = $obj->packDbRecord( false );
			    unset( $dbRec['comment_approved'] );
			    $obj->setIsApproved( wp_allow_comment( $dbRec ) );
		    }

		    return $obj;
	    }

	    return null;
    }

	/**
	 * Validates input and sets $validationErrors
	 *
	 * @param array $input
	 * @param CommentModel $oldState
	 *
	 * @return bool is input valid
	 */
    public static function validateInput($input = array(), $oldState = null) {
	    static::$validationErrors = array();
	    $valid = apply_filters('CommentModel.validateInput', true, $input, $oldState);
        if(!$oldState){ // creating new comment
            $postId = Util::getItem($input, 'comment_post_ID', 0);
            $post = PostModel::selectById($postId);

            if(!$valid){
                return false; 
            }
            
            if (!($post->getCommentStatus())) {
                do_action('comment_id_not_found', $postId);
                return false;
            }

            // get_post_status() will get the parent status for attachments.
            $status = $post->getStatus();

            $status_obj = get_post_status_object($status);
            $msgCommentsClosed = NlsHelper::_('Sorry, comments are closed for this item.');
            if (!comments_open($postId)) {
                self::$validationErrors['comment_closed'] = $msgCommentsClosed;
                return false;
            } elseif ('trash' == $status) {
	            self::$validationErrors['comment_on_trash'] = $msgCommentsClosed;
                return false;
            } elseif (!$status_obj->public && !$status_obj->private) {
	            self::$validationErrors['comment_on_draft'] = $msgCommentsClosed;
                return false;
            } elseif (post_password_required($postId)) {
	            self::$validationErrors['comment_on_password_protected'] = $msgCommentsClosed;
                return false;
            } 

            // If the user is logged in
            $user = wp_get_current_user();
            if ($user->exists()) {
                if (current_user_can('unfiltered_html')) {
                    if (wp_create_nonce('unfiltered-html-comment_' . $postId) != Util::getItem($_POST, '_wp_unfiltered_html_comment')) {
                        kses_remove_filters(); // start with a clean slate
                        kses_init_filters(); // set up the filters
                        InputHelper::permitHtml('comment_content');
                    }
                }
            } else {
                if (get_option('comment_registration') || 'private' == $status){
                    AclHelper::apiAuthRequired('Sorry, you must be logged in to post a comment.');
                    return false;
                }else if(get_option('require_name_email')){
                    if (!Util::getItem($input, 'comment_author')) {
	                    self::$validationErrors['comment_author'] = 'Required field';
                    }
                    if (!Util::getItem($input, 'comment_author_email')) {
	                    self::$validationErrors['comment_author_email'] = 'Required field';
                    }
                }
            }

            if (!Util::getItem($input, 'comment_content')) {
	            self::$validationErrors['comment_content'] = 'Required field';
            }
            
            if(!empty(self::$validationErrors)){
                return false;
            }
            
        }else if(empty($input)){// deleting
	        AclHelper::apiOwnershipRequired($oldState);
        }else{ // updating comment
            AclHelper::apiOwnershipRequired($oldState);
            if (!Util::getItem($input, 'comment_content')) {
	            self::$validationErrors['comment_content'] = 'Required field';
                return false;
            }
        }
        
        return true;
    }

    /**
     * Unpacks db record while fetching model from DB 
     * 
     * @param object $wpRecord
     * @return \self
     */
    public static function unpackDbRecord( $wpRecord){
        
        $obj = new self();

        $obj->setId(Util::getItem($wpRecord, 'comment_ID'));
        $obj->setPostId(Util::getItem($wpRecord, 'comment_post_ID'));
        $obj->setUserId(Util::getItem($wpRecord, 'user_id'));
        $obj->setParentId(Util::getItem($wpRecord, 'comment_parent'));
        $obj->setAuthor(Util::getItem($wpRecord, 'comment_author'));
        $obj->setEmail(Util::getItem($wpRecord, 'comment_author_email'));
        $obj->setUrl(Util::getItem($wpRecord, 'comment_author_url'));
        $obj->setIp(Util::getItem($wpRecord, 'comment_author_IP'));
        $obj->setContent(Util::getItem($wpRecord, 'comment_content'));
        $obj->setKarma(Util::getItem($wpRecord, 'comment_karma'));
        $obj->setIsApproved(Util::getItem($wpRecord, 'comment_approved'));
        $obj->setDtCreated(DateHelper::dbStrToDatetime(Util::getItem($wpRecord, 'comment_date')));
        $obj->setAgent(Util::getItem($wpRecord, 'comment_agent'));
        $obj->setType(Util::getItem($wpRecord, 'comment_type'));
        
        $obj->setWpComment($wpRecord);
        
        self::$commentsCacheById[$obj->getId()] = $obj;
        self::$commentsCacheByPostId[$obj->getPostId()][$obj->getId()]=$obj->getId();
        
        return $obj;
    }

    /**
     * Packs model into assoc array before commiting to DB
     * 
     * @param boolean $forUpdate
     * @return array
     */
    public function packDbRecord($forUpdate = true){
        $dbRecord = array();
        if($forUpdate){
            $dbRecord['comment_ID'] = $this->getId();
        }
        $dbRecord['comment_post_ID'] = $this->getPostId();
        $dbRecord['comment_author'] = $this->getAuthor();
        $dbRecord['comment_author_email'] = $this->getEmail();
        $dbRecord['comment_author_url'] = $this->getUrl();
        $dbRecord['comment_author_IP'] = $this->getIp();
        $dbRecord['user_id'] = $this->getUserId();
        $dbRecord['comment_content'] = $this->getContent();
        $dbRecord['comment_karma'] = $this->getKarma();
        $dbRecord['comment_approved'] = $this->getIsApproved();
        $dbRecord['comment_agent'] = $this->getAgent();
        $dbRecord['comment_parent'] = $this->getParentId();
        $dbRecord['comment_type'] = $this->getType();
        $dbRecord['comment_date'] = DateHelper::datetimeToDbStr($this->getDtCreated());
        $dbRecord['comment_date_gmt'] = DateHelper::datetimeToDbStr($this->getDtCreatedGMT());
        
        return $dbRecord;
    }
    
    /**
     * Inserts new model to DB, returns autogenerated ID
     * 
     * @return integer
     */
    public function insert(){
        $this->setDtCreated(new DateTime());
        $this->setIp(preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] ));
        $this->setAgent(substr($_SERVER['HTTP_USER_AGENT'], 0, 254));
        $dbRecord = $this->packDbRecord(false);
        $id = wp_insert_comment($dbRecord);
        $this->setId($id);
        return $id;
    }
    
    /**
     * Update db record
     * 
     * @return int|\WP_Error The value 0 or WP_Error on failure. The post ID on success.
     */
    public function update(){
        $dbRecord = $this->packDbRecord(true);
        unset($dbRecord['comment_date']);
        unset($dbRecord['comment_date_gmt']);
        wp_update_comment($dbRecord);
        return true;
    }
    
    /**
     * Delete record form DB
     * 
     * @param bool $forceDelete Whether to bypass trash and force deletion. Defaults to false.
     * @return mixed False on failure
     */
    public function delete($forceDelete = false){
        return self::deleteById($this->getId(), $forceDelete);
    }
    
    /**
     * Deletes comment with the specified $commentId from db table
     *
     * @param integer $commentId
     * @param boolean $forceDelete
     * @return boolean
     */
    public static function deleteById($commentId = 0, $forceDelete = false) {
        $item = Util::getItem(self::$commentsCacheById, $commentId);
        if($item){
            unset(self::$commentsCacheByPostId[$item->getPostId()][$item->getId()]);
            unset(self::$commentsCacheById[$item->getId()]);
        }
        return wp_delete_comment( $commentId, $forceDelete );
    }

    /**
     * Select comment by id
     * 
     * @param integer $id
     * @param boolean $useCache
     * @return CommentModel 
     */
    public static function selectById($id, $useCache = true){
        if($useCache){
            $record = Util::getItem(self::$commentsCacheById, $id);
            if($record){
                return $record;
            }
        }
        $wpRecord = get_comment($id);
        return $wpRecord?self::unpackDbRecord($wpRecord):null;
    }


    /**
     * Select models using WP_Comment_Query syntax.
     * The total count of comments of the specified post is stored in post model.
     * (could be inconsistent though)
     * 
     * @param array $wpCommentsQueryArgs
     * @return array(CommentModel)
     */
    public static function selectComments($wpCommentsQueryArgs){
        $comments = array();
        $dbRecords = get_comments($wpCommentsQueryArgs);
        foreach ($dbRecords as $dbRecord) {
            $comments[] = self::unpackDbRecord($dbRecord);
        }
        
        return $comments;
    }
    
    /**
     * Get CommentQuery object to create a query.
     * Call ->select() to fetch queried models;
     * The total count of comments of the specified post is stored in post model.
     * (could be inconsistent though)
     * 
     * @param integer $postId
     * @return CommentQuery
     */
    public static function query($postId = 0){
        return CommentQuery::query($postId);
    }
    
    /**
     * Get comment meta single key-value pair or all key-values
     * 
     * @param int $commentId Comment ID.
     * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
     * @param bool $single Whether to return a single value.
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     */
    public static function getCommentMeta($commentId, $key = '', $single = true){
        $meta = get_comment_meta($commentId, $key, $single);
        if(!$key && $single && $meta && is_array($meta)){
            $m = array();
            foreach($meta as $k => $values){
                $m[$k]= is_array($values)?reset($values):$values;
            }
            
            return $m;
        }
        return $meta;
    }

    /**
     * Update comment meta value for the specified key in the DB
     * 
     * @param integer $commentId
     * @param string $key
     * @param string $value
     * @param string $oldValue
     * @return bool False on failure, true if success.
     */
    public static function updateCommentMeta($commentId, $key, $value, $oldValue = ''){
        return update_comment_meta($commentId, $key, $value, $oldValue);
    }
    
    /**
     * Get comment meta single key-value pair or all key-values
     * 
     * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
     * @param bool $single Whether to return a single value.
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     */
    public function getMeta($key = '', $single = true) {
        return  self::getCommentMeta($this->getId(), $key, $single);
    }

    /**
     * Update comment meta value for the specified key in the DB
     * 
     * @param string $key
     * @param string $value
     * @param string $oldValue
     * @return bool False on failure, true if success.
     */
    public function updateMeta($key, $value, $oldValue = '') {
        self::updateCommentMeta($this->getId(), $key, $value, $oldValue);
    }

    /**
     * Populates this comment for old school WP use.
     * Defines global variable $comment.
     *
     * @global object $comment
     * @return array|bool|null|object
     */
    public function populateWpGlobals(){
        global $comment;
        $comment = $this->getWpComment();
        return $comment;
    }

    /**
     * Set meta fields that should be populated to json
     *
     * @param array(string) $metaFields
     */
    public static function setJsonMetaFields($metaFields) {
        self::$jsonMetaFields = $metaFields;
    }

    /**
     * Add meta field name that should be populated to json
     *
     * @param string $fieldName
     */
    public static function addJsonMetaField($fieldName){
        if(false === array_search($fieldName, self::$jsonMetaFields)){
            self::$jsonMetaFields[]=$fieldName;
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
    public function packJsonItem() {
        $jsonItem = array();
        $jsonItem['id'] = (int)$this->getId();
        $jsonItem['comment_post_ID'] = $this->getPostId();
        $jsonItem['comment_author'] = $this->getAuthor();
        $jsonItem['comment_author_email'] = $this->getEmail();
        $jsonItem['comment_author_url'] = $this->getUrl();
        $jsonItem['user_id'] = $this->getUserId();
        $jsonItem['comment_content'] = $this->getContent();
        $jsonItem['comment_karma'] = $this->getKarma();
        $jsonItem['comment_karma_delta'] = $this->getKarmaDelta();
        $jsonItem['comment_approved'] = $this->getIsApproved();
        $jsonItem['comment_agent'] = $this->getAgent();
        $jsonItem['comment_parent'] = (int)$this->getParentId();
        $jsonItem['comment_type'] = $this->getType();
        $jsonItem['comment_date'] = DateHelper::datetimeToJsonStr($this->getDtCreated());
        $jsonItem['comment_date_gmt'] = DateHelper::datetimeToJsonStr($this->getDtCreatedGMT());
        $meta = array();
        foreach(self::$jsonMetaFields as $field){
            $meta[$field] = $this->getMeta($field);
        }
        if($meta){
            $jsonItem['meta']=$meta;
        }
        
        return $jsonItem;
    }

    /**
     * Flushes cache used for selectById()
     */
    public static function flushCache(){
        self::$commentsCacheById = array();
        self::$commentsCacheByPostId = array();
    }
    
    /**
     * Get comment by $id from cache.
     * It gets to cache once it was unpacked by unpackDbRecord()
     * 
     * @param integer $id
     * @return CommentModel
     */
    public static function getCommentsCacheById($id = 0){
        if($id){
            return Util::getItem(self::$commentsCacheById, $id);
        }
        return null;//self::$commentsCacheById;
    }
    
    /**
     * Get comments by $postId from cache.
     * Comments gets to cache once it was unpacked by unpackDbRecord()
     * 
     * @param integer $postId
     * @return array(CommentModel)
     */
    public static function getCommentsCacheByPostId($postId = 0){
        $ret = array();

        if($postId){
            $commentIds = Util::getItem(self::$commentsCacheByPostId, $postId);
            foreach($commentIds as $id){
                $item = Util::getItem(self::$commentsCacheById, $id);
                if($item){
                    $ret[$id] = $item;
                }
            }
            
            return $ret;
        }
        
        foreach (self::$commentsCacheByPostId as $postId=>$commentIds){
            if($postId){
                $ret[$postId] = self::getCommentsCacheByPostId($postId);
            }
        }
        
        return $ret;
    }

	/**
	 * @param string $privilege
	 * @param \Chayka\WP\Models\UserModel|null $user
	 *
	 * @return mixed
	 */
	public function userCan( $privilege, $user = null ) {
		if(!$user){
			$user = UserModel::currentUser();
		}
		$post = PostModel::selectById($this->getPostId());
		$userCan = true;
		$errors = array();
		$canModerate = user_can($user->getWpUser(), 'moderate_comments');
		$commentsAllowed = comments_open($this->getPostId()) && post_type_supports($post->getType(), 'comments' );
		$isCommentOwner = AclHelper::isOwner($this, $user);
		if(!$canModerate){
			switch($privilege){
				case 'create':
					/**
					 * User can create comment if:
					 * - comments are not blocked for post
					 * - post is readable
					 */
					if(!$commentsAllowed || !$post->userCan('read', $user)){
						$userCan = false;
						$errors['comments_closed']=NlsHelper::_('Comments are not allowed for this post');
					}
					break;
				case 'read':
					/**
					 * User can read comment if:
					 * - comments are not blocked for post
					 * - post is readable
					 */
					if(!$commentsAllowed || !$post->userCan('read', $user)){
						$userCan = false;
						$errors['comments_closed']=NlsHelper::_('Comments are not allowed for this post');
					}
					break;
				case 'update':
					/**
					 * User can update comment if:
					 * - user owns it
					 */
					if(!$isCommentOwner){
						$userCan = false;
						$errors['permission_required']=NlsHelper::_('User should own this comment');
					}
					break;
				case 'delete':
					/**
					 * User can delete comment if:
					 * - user owns it
					 * - user can edit post
					 */
					if(!$isCommentOwner && !$post->userCan('update', $user)){
						$userCan = false;
						$errors['permission_required']=NlsHelper::_('User should own this comment');
					}
					break;
				default:
					$userCan = false;
			}
		}

		$userCan = apply_filters('CommentModel.'.$privilege, $userCan, $this, $user);

		if(!$userCan){
			static::addValidationErrors($errors);
		}

		return $userCan;
	}
}
