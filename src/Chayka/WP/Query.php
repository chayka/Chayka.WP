<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP;

use Chayka\Helpers\Util;
use Chayka\MVC\ApplicationDispatcher;
use Chayka\WP\Helpers\JsonHelper;
use Chayka\WP\Helpers\OptionHelper;
use Chayka\WP\Models\PostModel;
use WP_Query;
use WP_Post;

/**
 * Ok, this is where it all began. When I decided that I need to bring MVC/OOP principles to WP,
 * I was looking for the way to embed ZendFramework MVC application into WP.
 *
 * Later I replaced ZF with my own lightweight Chayka.MVC framework.
 *
 * But this class is actually the place where WP meets Chayka.Framework.
 *
 * This class overrides main WP_Query object that is stored in global $wp_query or $wp_the_query.
 * The static method of Chayka\WP\Query::parseRequest() is hooked to parse_request hook.
 * It compares the request to the list of registered MVC application routes
 * (from different Chayka powered themes and plugins) and if matched calls appropriate
 * controller & action using get_posts() method.
 *
 * The overridden get_posts() method returns one faked post that is assembled using content
 * generated by controller action. This post thereafter outputted using /src/Chayka/WP/index.php
 * template.
 *
 * And there's more to it. For example you have some controller action that serves url:
 *      /my-awesome-controller/awesome-action/awesome-param/awesome-value
 *
 * If you prepend it with /api/ prefix you'll get the pure response of the action
 * not wrapped into header & footer. Amazing! Just like that:
 *      /api/my-awesome-controller/awesome-action/awesome-param/awesome-value
 *
 * And don't forget to revise JsonHelper::respond() to wrap all your json api responses
 * into uniform json envelopes.
 *
 * @package Chayka\WP
 */
class Query extends WP_Query {

    /**
     * This is the representation of the faked post that will be returned as result of controller action.
     * It will be converted into standard WP_Post object and returned by get_posts().
     *
     * You can get it by Query::getPost() to set title, description, id, etc.
     * Or you can set all those post properties at once by Query::setPost($post) if your controller action
     * renders some custom post page.`
     *
     * @var PostModel|null
     */
    protected static $_post = null;

    /**
     * As noted before the response is outputted using /src/Chayka/WP/index.php
     * However you can specify custom output template using Query::setTemplate().
     * @var string|null
     */
    protected static $template = null;

    /**
     * Request URI to process
     *
     * @var string|null
     */
    protected $requestUri = null;

    /**
     * Query constructor
     *
     * @param string $requestUri
     * @param array|string $query
     */
    public function __construct($requestUri = '', $query = ''){
        parent::__construct($query);
        $this->requestUri = $requestUri ? $requestUri: $_SERVER['REQUEST_URI'];
    }

    /**
     * This function is called on parse_request hook.
     * If it detects url that can be processed by any registered application it replaces
     * $wp_the_query with this modified WP_Query extended instance.
     * On get_posts it will return modified $post with MVC Application rendered content.
     */
    public static function parseRequest(){
        global $wp_the_query, $wp_query;

        $requestUri = $_SERVER['REQUEST_URI'];

        $requestUri = apply_filters('Chayka.WP.Query.parseRequest', $requestUri);

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
            remove_action( 'template_redirect', 'wp_old_slug_redirect');
            remove_filter ('the_content','wpautop');
            $q = new Query($requestUri);
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
     * Get current rendered post instance.
     * Init it if one is absent.
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

    /**
     * As noted before the response is outputted using /src/Chayka/WP/index.php
     * However you can specify custom output template using Query::setTemplate().
     *
     * You can check what template is set using this function.
     * If it returns null, consider it /src/Chayka/WP/index.php
     *
     * @return null|string
     */
    public static function getTemplate() {
        return self::$template;
    }

    /**
     * As noted before the response is outputted using /src/Chayka/WP/index.php
     * However you can specify custom output template using Query::setTemplate().
     *
     * @param null|string $template
     */
    public static function setTemplate($template) {
        self::$template = $template;
    }

    /**
     * Get query property
     *
     * @param $key
     * @param string $default
     *
     * @return mixed
     */
    public static function getProperty($key, $default = ''){
        global $wp_the_query;
        return Util::getItem($wp_the_query, $key, $default);
    }

    /**
     * Set query property
     *
     * @param $key
     * @param $value
     */
    public static function setProperty($key, $value){
        global $wp_the_query;
        $wp_the_query->$key = $value;
    }

    /**
     * Check if current request is considered by WP as home page.
     * Alias to is_home()
     *
     * @return bool
     */
    public static function getIsHome(){
        return self::getProperty('is_home');
    }

    /**
     * Set is_home property
     *
     * @param bool|true $is
     */
    public static function setIsHome($is = true){
        self::setProperty('is_home', $is);
    }

    /**
     * Check if current request is considered by WP as archive page.
     * Alias to is_archive()
     * @deprecated
     *
     * @return bool
     */
    public static function getIsArchive(){
        return self::getProperty('is_archive');
    }

    /**
     * Set is_archive property
     * @deprecated
     *
     * @param bool|true $is
     */
    public static function setIsArchive($is = true){
        self::setProperty('is_archive', $is);
    }

    /**
     * Check if current request is considered by WP as archive page.
     * Alias to is_search()
     * @deprecated
     *
     * @return bool
     */
    public static function getIsSearch(){
        return self::getProperty('is_search');
    }

    /**
     * Set is_search property
     * @deprecated
     *
     * @param bool|true $is
     */
    public static function setIsSearch($is = true){
        self::setProperty('is_search', $is);
    }

    /**
     * Check if current request is considered by WP as 404 page.
     * Alias to is_search()
     *
     * @return bool
     */
    public static function getIs404(){
        return self::getProperty('is_404');
    }

    /**
     * Set is_404 property
     *
     * @param bool|true $is
     */
    public static function setIs404($is = true){
        self::setProperty('is_404', $is);
    }

    /**
     * This is our overridden get_posts() method that returns an array with one faked post,
     * generated by our MVC controller action.
     *
     * If controller action returned:
     *      return $this->setNotFound404()
     * then empty array will be returned and WP will use 404.php of current theme.
     *
     * 404.php is also used when controller action throws an uncaught exception.
     *
     * @return array
     */
    public function get_posts() {

        $this->request = '';

	    $response = '';
	    try{
		    $response = ApplicationDispatcher::dispatch($this->requestUri);
	    }catch (\Exception $e){
			self::setIs404(true);
	    }

        if(!self::getIs404()){
            $richPost = self::getPost();
            $post = $richPost->getWpPost();
            if($post){
                $post = clone $post;
            }else{
                $post = (object)$richPost->packDbRecord();
            }
            $post->post_content = $response;
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

    /**
     * This function is hooked to 'single_template' and provides template to render our response.
     * We need that unused param as apply_filters will call it with param
     *
     * @param $template
     *
     * @return null|string
     */
    public static function renderResponse($template){
        return self::$template?self::$template:__DIR__.'/index.php';
    }

}

