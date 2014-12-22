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

    //
    /**
     * This function is called on parse_request hook.
     * If it detects url that can be processed by any registered application it replaces
     * $wp_the_query with this modified WP_Query extended instance.
     * On get_posts it will return modified $post with MVC Application rendered content.
     */
    public static function parseRequest(){
        global $wp_the_query, $wp_query;

//        self::checkSingleDomain();
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
            add_filter('single_template', array('Chayka\\WP\\Query', 'renderResponse'), 1, 1);

            remove_filter ('the_content','wpautop');
            $q = new Query();
            $q->copyFrom($wp_the_query);
            $wp_the_query = $wp_query = $q;

        }
    }

    /**
     * Get all the data from provided query.
     *
     * @param WP_Query $wp_query
     */
    public function copyFrom(WP_Query $wp_query) {
        $vars = get_object_vars($wp_query);
        foreach ($vars as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Set post data that will be returned by get_posts().
     * 'post_content' will be replaced by MVC Application response.
     *
     * @param PostModel|WP_Post $post
     * @return PostModel
     */
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


    public function &get_posts() {

        $this->request = '';

        $richPost = self::getPost();
        if(!self::getIs404()){
            $response = ApplicationDispatcher::dispatch();
            $richPost = self::getPost();
            $richPost->setContent($response);
            $post = (object)$richPost->packDbRecord();
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

        return $this->posts;
    }


    public static function renderResponse($template){
        return __DIR__.'/index.php';
    }

}

