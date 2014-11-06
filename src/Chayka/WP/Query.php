<?php

namespace Chayka\WP;

use Chayka\Helpers\Util;
use Chayka\MVC\ApplicationDispatcher;
use Chayka\WP\Helpers\JsonHelper;
use Chayka\WP\Helpers\OptionHelper;
use Chayka\WP\Models\PostModel;
use WP_Query;
use WP_Post;

class Query extends WP_Query {

    protected static $options = array();

    protected static $_post = null;

    public static function checkSingleDomain(){
        $server = Util::serverName();
        switch(OptionHelper::getOption('SingleDomain')){
            case 'www':
//                die($_SERVER['SERVER_NAME'].' ! '.$server);
                if($_SERVER['SERVER_NAME'] !== 'www.'.$server){
                    header('Location: //www.'.$server.$_SERVER['REQUEST_URI'], true, 301);
                    die();
                }
                break;
            case 'no-www':
                if($_SERVER['SERVER_NAME'] === 'www.'.$server){
                    header('Location: //'.$server.$_SERVER['REQUEST_URI'], true, 301);
                    die();
                }
                break;
        }
    }

    public static function parseRequest(){
        global $wp_the_query, $wp_query;

        self::checkSingleDomain();
//  TODO: implement BlockadeHelper
//        BlockadeHelper::inspectUri($_SERVER['REQUEST_URI']);

        $requestUri = $_SERVER['REQUEST_URI'];

        $isForbidden = ApplicationDispatcher::isForbiddenRoute($requestUri);
        $canProcess = ApplicationDispatcher::canProcess($requestUri);
        $isHome = !$requestUri || $requestUri == '/';
        $isAPI = preg_match('%^\/api\/%', $requestUri);

        self::setIs404($isForbidden);

        if ($canProcess) {
            self::setIsHome($isHome);
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            if($isAPI){
                try{
                    echo ApplicationDispatcher::dispatch($requestUri);
                }catch(\Exception $e){
                    JsonHelper::respondException($e, 'exception');
                }
                die();
            }
            add_filter('single_template', array('Chayka\\WP\\Query', 'renderResponse'), 1, 2);


//            $args = array(
//                'public' => false,
//                'publicly_queryable' => false,
//                'show_ui' => false,
//                'show_in_menu' => false,
//                'query_var' => false,
//                'rewrite' => false,
//                'capability_type' => 'post',
//                'has_archive' => true,
//                'hierarchical' => false,
//                //            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
//            );
//            register_post_type('zf', $args);
//            register_post_type('zf-api', $args);
//            if($isAPI){
//                $GLOBALS['is_zf_api_call'] = true;
//            }
            remove_filter ('the_content','wpautop');
            $q = new Query();
            $q->copyFrom($wp_the_query);
            $wp_the_query = $wp_query = $q;

        }
    }

    public function copyFrom(WP_Query $wp_query) {
        $vars = get_object_vars($wp_query);
        foreach ($vars as $name => $value) {
            $this->$name = $value;
        }
    }

    public static function setPost($post){
        if(!($post instanceof PostModel)){
            $post = PostModel::unpackDbRecord($post);
        }
        return self::$_post = $post;
    }

    /**
     *
     * @return PostModel
     */
    public static function getPost(){
        if(!self::$_post){
            self::$_post = new PostModel();
            self::$_post->setStatus('publish');
            self::$_post->setCommentStatus('closed');
            self::$_post->setCommentCount(0);
            self::$_post->setPingStatus('closed');
        }
        return self::$_post;
    }

//    public static function setPostId($val){
//        self::getPost()->setId($val);
//    }
//
//    public static function getPostId(){
//        return self::getPost()->getId();
//    }
//
//    public static function setPostAuthor($val){
//        self::getPost()->setUserId($val);
//    }
//
//    public static function getPostAuthor(){
//        return self::getPost()->getUserId();
//    }
//
//    public static function setPostDate($val){
//        self::getPost()->setDtCreated($val);
//    }
//
//    public static function getPostDate(){
//        return self::getPost()->getDtCreated();
//    }
//
//    public static function setPostContent($val){
//        self::getPost()->setContent($val);
//    }
//
//    public static function getPostContent(){
//        return self::getPost()->getContent();
//    }
//
//    public static function setPostTitle($val){
//        self::getPost()->setTitle($val);
//    }
//
//    public static function getPostTitle(){
//        return self::getPost()->getTitle();
//    }
//
//    public static function setPostSlug($val){
//        self::getPost()->setSlug($val);
//    }
//
//    public static function getPostSlug(){
//        return self::getPost()->getSlug();
//    }
//
//    public static function setPostExcerpt($val){
//        self::getPost()->setExcerpt($val);
//    }
//
//    public static function getPostExcerpt(){
//        return self::getPost()->getExcerpt();
//    }
//
//    public static function setPostType($val){
//        self::getPost()->setType($val);
//    }
//
//    public static function getPostType(){
//        return self::getPost()->getType();
//    }
//
//    public static function setPostStatus($val){
//        self::getPost()->setStatus($val);
//    }
//
//    public static function getPostStatus(){
//        return self::getPost()->getStatus();
//    }
//
//    public static function setCommentStatus($val){
//        self::getPost()->setCommentStatus($val);
//    }
//
//    public static function getCommentStatus(){
//        return self::getPost()->getCommentStatus();
//    }
//
//    public static function setPingStatus($val){
//        self::getPost()->setPingStatus($val);
//    }
//
//    public static function getPingStatus(){
//        return self::getPost()->getPingStatus();
//    }
//
//    public static function getOptions(){
//        return self::$options;
//    }

    public static function getProperty($key, $default = ''){
        global $wp_the_query;
        return Util::getItem($wp_the_query, $key, $default);
    }

    public static function setProperty($key, $value){
        global $wp_the_query;
        $wp_the_query->$key = $value;
    }

    public static function getIsHome(){
        return self::getProperty('is_home');
    }

    public static function setIsHome($is = true){
        self::setProperty('is_home', $is);
    }

//    public static function getIsArchive(){
//        return self::getOption('is_archive');
//    }
//
//    public static function setIsArchive($is = true){
//        return self::setOption('is_archive', $is);
//    }
//
//    public static function getIsSearch(){
//        return self::getOption('is_search');
//    }
//
//    public static function setIsSearch($is = true){
//        return self::setOption('is_search', $is);
//    }

    public static function getIs404(){
        return self::getProperty('is_404');
    }

    public static function setIs404($notFound = true){
        self::setProperty('is_404', $notFound);
    }

//    public static function getPageTemplate() {
//        return self::getOption('page-template');
//    }
//
//    public static function setPageTemplate($pageTemplate) {
//        self::setOption('page-template', $pageTemplate);
//    }

    public function &get_posts() {
//        global $wp_the_query;
//        global $wp_query;

        $this->request = '';
//        $this->is_home = self::getIsMainPage();
//        if(self::getNotFound()){
//            $response = ApplicationDispatcher::dispatch('/not-found-404/');
//        }
//        $post_zf = array(
//            "ID" => self::getPostId(),//WpHelper::getPostId(),
//            "post_author" => self::getPostAuthor(),//WpHelper::getPostAuthor(),
//            "post_date" => '',
//            "post_date_gmt" => '',
//            "post_content" => $response,
//            "post_title" => self::getPostTitle(),//HtmlHelper::getHeadTitle(),
//            "post_excerpt" => self::getPostExcerpt(),//HtmlHelper::getMetaDescription(),
//            "post_status" => self::getPostStatus(),//"publish",
//            "comment_status" => self::getCommentStatus(),//"closed",
//            "ping_status" =>  self::getPingStatus(),//"closed",
//            "post_password" => "",
//            "post_name" => self::getPostSlug(),//"",
//            "to_ping" => "",
//            "pinged" => "",
//            "post_modified" => "",
//            "post_modified_gmt" => "",
//            "post_content_filtered" => "",
//            "post_parent" => 0,
//            "guid" => "",
//            "menu_order" => 1,
//            "post_type" => 'zf',
//            "post_mime_type" => "",
//            "comment_count" => "0",
//            "ancestors" => array(),
//            "filter" => "",
//            "page_template" => self::getPageTemplate(),
//        );

        $richPost = self::getPost();
        if(!self::getIs404()){
            $response = ApplicationDispatcher::dispatch();
            $richPost = self::getPost();
            $richPost->setContent($response);
        }
        if(!self::getIs404()){
            global $post;
            $post = new WP_Post((object)$richPost->packDbRecord());
            $this->post = $post;
            $this->is_single = true;
            $this->posts = array($post);
            $this->post_count = 1;
            $this->queried_object = $post;
            $this->queried_object_id = $post->ID;
        }else{
            $this->is_single = false;
            $this->posts = array();
            $this->post_count = 0;
            $this->queried_object = null;
            $this->queried_object_id = 0;
        }
        $this->current_post = -1;

//        $this->is_search = self::getIsSearch();
//        $this->is_page = false;
//        $this->is_404 = self::getNotFound();
//        $this->is_archive = self::getIsArchive();
//        $this->is_home = 0;//self::getIsMainPage();
//        $this->comment = null;
//        $this->comments = array();
//        $this->comment_count = 0;
//        $wp_the_query = $this;


//        global $wp_filter;
//        unset($wp_filter['template_redirect']);

        return $this->posts;
    }

    public static function renderResponse($template){
        return __DIR__.'/index.php';
    }

}

//require_once 'widgets-ZF-Core.php';
