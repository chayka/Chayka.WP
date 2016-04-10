<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP;

use Chayka\Helpers\Util;
use Chayka\Helpers\InputHelper;
use Chayka\Helpers\FsHelper;
use Chayka\MVC\ApplicationDispatcher;
use Chayka\MVC\Application;
use Chayka\MVC\View;
use Chayka\WP\Helpers\AngularHelper;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\OptionHelper;
use Chayka\WP\Helpers\ResourceHelper;
use Chayka\WP\Models\PostModel;
use Exception;
use WP_Post;

/**
 * Class Plugin implements plugin bootstrap file in Chayka.Framework.
 *
 * When you create a new plugin you define a bootstrap object class
 * that is a descendant of this class
 *
 * All the plugin configuration and hooking must be done in such
 * a bootstrap class implementation
 *
 * @package Chayka\WP
 */
abstract class Plugin{

    /**
     * A variable that defines whether black admin bar should be shown outside admin area
     *  false - always hide
     *  true - always show if the user is logged in
     *  'admin' - show only if logged in user is administrator
     *
     * @var string|bool
     */
    protected static $adminBar;

    /**
     * This variable can be used to block plugin's styles if you want to apply custom styles
     * E.g. you can call \Chayka\Comments\Plugin::blockStyles(true) out of your theme definition
     *
     * @deprecated
     *
     * @var bool
     */
    protected $needStyles = true;

    /**
     * Current DB version, used in dbUpdate() method.
     * TODO: Need DB install/update script guide
     *
     * @var string
     */
    protected $currentDbVersion = '1.0';

    /**
     * Array of console pages params used for console page creation hooking
     *
     * @var array
     */
    protected $consolePageUris = array();

    /**
     * Array of meta boxes params used for meta boxes creation hooking
     *
     * @var array
     */
    protected $metaboxUris = array();

    /**
     * Array of short codes params used for shortcodes creation hooking
     *
     * @var array
     */
    protected $shortcodeUris = array();

    /**
     * Base URL for this application (plugin or theme)
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Base path for this application (plugin or theme)
     *
     * @var string
     */
    protected $basePath;

    /**
     * Variable to keep 'res/src' folder path, when using src & dist folders to keep your resources
     *
     * @var string
     */
    protected $resSrcDir = "";

    /**
     * Variable to keep 'res/dist' folder path, when using src & dist folders to keep your resources
     *
     * @var string
     */
    protected $resDistDir = "";

    /**
     * Flag that defines usage of minimized sources when available
     *
     * @var bool
     */
    protected $mediaMinimized = false;

    /**
     * Application id
     *
     * @var string
     */
    protected $appId;

    /**
     * Instance of Chayka\MVC\Application that is gonna be used for MVC url processing
     *
     * @var Application
     */
    protected $application;

    /**
     * Hash map that holds bower configs (bower.json, .bowerrc, etc.)
     *
     * @var array|null
     */
    protected $bower = null;

    /**
     * Hash map that holds composer configs (composer.json, composer.lock, etc.)
     *
     * @var array|null
     */
    protected $composer = null;

    /**
     * Flag that enables uri processing
     *
     * @var bool
     */
    protected static $uriProcessing = false;

    /**
     * Singleton instance to current application (plugin or theme)
     *
     * @var self
     */
    protected static $instance = null;

    /**
     * Required classes to check before loading plugin
     * 
     * @var array
     */
    protected static $requiredClasses = [
    //    "Chayka\\WP\\Plugin" => 'Chayka.Framework functionality is required in order for '.__NAMESPACE__.' to work properly',
    ];

    /**
     * Plugin Constructor.
     *
     * @param string $__file__ pass __FILE__
     * @param array $routes
     */
    public function __construct($__file__, $routes = array()) {
        $this->basePath = self::dirPath( $__file__ );
        $this->baseUrl = self::dirUrl($__file__);
        $namespace = $this->getNamespace();
        $this->appId = $namespace ? $namespace : $this->getClassName();
        $this->application = new Application($this->basePath.'app', $this->appId);
        set_include_path($this->basePath.PATH_SEPARATOR.get_include_path());
        ApplicationDispatcher::registerApplication($this->application, $routes);

	    $minimize = OptionHelper::getOption('MinimizeMedia');
        ResourceHelper::setMediaMinimized($minimize);
        $this->setMediaMinimized($minimize);
        $this->getBower($minimize);
        $this->registerResources($minimize);
        $this->addRoute('default');
        $this->registerRoutes();
        $this->registerCustomPostTypes();
        $this->registerTaxonomies();
        $this->registerSidebars();
        $this->registerShortcodes();
        $this->registerActions();
        $this->registerFilters();
    }

    /**
     * Get singleton instance
     *
     * @return static
     */
    public static function getInstance(){
        if(!static::$instance){
            static::$instance = static::init();
        }
        return static::$instance;
    }

    /**
     * Output notification in admin area
     *
     * @param $message
     * @param string $type allowed types are 'info', 'warning', 'error'
     */
    public static function addAdminNotice($message, $type = 'info'){
        add_action( 'admin_notices', function () use ($message, $type){
            ?>
            <div class="notice notice-<?php echo $type?>">
                <p><?php echo $message; ?></p>
            </div>
            <?php
        });

    }

    /**
     * Check if required classes are available.
     * 
     * @param array $requiredClasses array [className => errorMessage]
     *
     * @return bool
     */
    public static function areRequiredClassesAvailable($requiredClasses = []){
        if(!empty($requiredClasses)){
            static::$requiredClasses = array_merge(static::$requiredClasses, $requiredClasses);
        }
        $requirementsMet = true;
        foreach(static::$requiredClasses as $cls => $message){
            if(!class_exists($cls)){
                $requirementsMet &= false;
                self::addAdminNotice($message, 'error');
            }
        }
        return $requirementsMet;
    }

    /**
     * Returns file's dir path
     *
     * @param string $__file__ use __FILE__
     * @return string
     */
    protected static function dirPath($__file__){
        return realpath(dirname($__file__)).'/';
    }

    /**
     * Returns file's dir url
     *
     * @param string $__file__ use __FILE__
     * @return string
     */
    protected static function dirUrl($__file__){
        $path = self::dirPath($__file__);

        if(strpos($path, ABSPATH) === 0){
            $relPath = str_replace(ABSPATH, '/', $path);
        }else{
            $relPath = preg_replace('%^.*wp-content%', '/wp-content', $path);
        }
        return str_replace(DIRECTORY_SEPARATOR, '/', $relPath);
    }

    /**
     * Get view with set up basepathh
     * @return View
     */
    public static function getView(){
        $view = new View();
        $view->addBasePath(static::getInstance()->getBasePath().'app/views');
        return $view;
    }

    /**
     * Get App Id
     *
     * @return string
     */
    public function getAppId(){
        return $this->appId;
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClassName(){
        return get_class($this);
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNamespace(){
        $cls = get_class($this);
        return substr($cls, 0, strrpos($cls, '\\'));
    }

    /**
     * Get callback method defined in this class
     *
     * @param string $method
     * @return callable
     */
    public function getCallbackMethod($method){
        return array($this, $method);
    }

    /**
     * Get base path
     *
     * @return string
     */
    public function getBasePath(){
        return $this->basePath;
    }

    /**
     * Get abs path for relative path
     * @param String $path
     * @return String
     */
    public function getPath($path = ''){
        return $this->getBasePath().$path;
    }

    /**
     * Get abs res for path relative to '/res'
     *
     * @param $relativeResPath
     * @return String
     */
    public function getPathRes($relativeResPath){
        return $this->getPath('res/'.$relativeResPath);
    }

    /**
     * Get base url
     *
     * @return string
     */
    public function getBaseUrl(){
        return $this->baseUrl;
    }

    /**
     * Output base url.
     * Can use in templates
     */
    public static function baseUrl(){
        echo static::getInstance()->getBaseUrl();
    }
    
    /**
     * Get abs url for relative path
     * @param String $path
     * @return String
     */
    public function getUrl($path = ''){
        return $this->getBaseUrl().$path;
    }

    /**
     * Get abs url for path relative to '/res'
     *
     * @param $relativeResPath
     * @return String
     */
    public function getUrlRes($relativeResPath = ''){
        return $this->getUrl('res/'.$relativeResPath);
    }

    /**
     * Perform update based on db structure version history
     *
     * DB Version history
     * @param array(String) $versionHistory
     */
    public function dbUpdate($versionHistory = array('1.0')){
        if(count($versionHistory)){
            $this->currentDbVersion = end($versionHistory);
            reset($versionHistory);
            DbHelper::dbUpdate($this->currentDbVersion, $this->getAppId().'.dbVersion', $this->getBasePath().'app/sql', $versionHistory);
        }
    }

    /**
     * This function instantiates Plugin's singleton and returns it
     *
     * @return self
     */
    public static function init(){

    }

    /**
     * Enables plugin ability to process request uris, based on the registered routes
     */
    public function addSupport_UriProcessing(){
        if(!self::$uriProcessing){
            self::$uriProcessing = true;
            $this->addAction('parse_request', array('Chayka\\WP\\Query', 'parseRequest'));
        }
    }

    /**
     * Routes are to be added here via $this->addRoute();
     */
    public function registerRoutes(){

    }

    /**
     * Add route mapping that can be served using this application (plugin or theme)
     *
     * Here are some samples of url pattern
     * :controller/?action/*
     * my_controller/some_action/:some_part/*
     * my/act/?some/*
     *
     * prefix ':' - means obligatory param
     * prefix '?' - means optional param
     * trailing '*' - means that all the rest params (/param1/value1/param2/value2) should be captured
     *
     * @param string $label
     * @param string $urlPattern
     * @param array $defaults
     * @param array $paramPatterns
     */
    public function addRoute($label = 'default', $urlPattern = '?controller/?action/*', $defaults = array('controller' => 'index', 'action'=>'index'), $paramPatterns = array()){
        $this->application->getRouter()->addRoute($label, $urlPattern, $defaults, $paramPatterns);
    }

	/**
	 * Add set of route to rest controller
	 *
	 * @param string $modelSlug e.g. 'post-model'
	 * @param string $restUrlPattern e.g '/?id'
	 * @param array $restParamPatterns e.g. ['id'=>'/^\d+$/']
	 * @param string $modelClassName e.g. '\\Chayka\\WP\\Models\\PostModel'
	 * @param string $controller e.g. 'post-model'
	 * @param array $defaults
	 */
	public function addRestRoute($modelSlug, $restUrlPattern = '/?id', $restParamPatterns = array(), $modelClassName = '', $controller = 'rest', $defaults = array()){
		$this->application->getRouter()->addRestRoute($modelSlug, $restUrlPattern, $restParamPatterns, $modelClassName, $controller, $defaults);
	}

	/**
	 * Add set of routes to rest controller
	 *
	 * @param string $modelSlug e.g. 'post-model'
	 * @param string $modelsSlug e.g. 'post-models'
	 * @param string $restUrlPattern e.g '/?id'
	 * @param array $restParamPatterns e.g. ['id'=>'/^\d+$/']
	 * @param string $modelClassName e.g. '\\Chayka\\WP\\Models\\PostModel'
	 * @param string $controller e.g. 'post-model'
	 * @param string $listAction
	 * @param array $defaults
	 */
	public function addRestRoutes($modelSlug, $modelsSlug='', $restUrlPattern = '/:id', $restParamPatterns = array(), $modelClassName = '', $controller = 'rest', $listAction='list', $defaults = array()) {
		$this->application->getRouter()->addRestRoutes($modelSlug, $modelsSlug, $restUrlPattern, $restParamPatterns, $modelClassName, $controller, $listAction, $defaults);
	}
    /**
     * Processes request uri and returns response
     *
     * @param $requestUri
     * @return string
     * @throws Exception
     */
    public function processRequest($requestUri){
        return $this->application->dispatch($requestUri);
    }

    /**
     * Processes request uri and outputs response
     *
     * @param $requestUri
     */
    public function renderRequest($requestUri){
        echo $this->processRequest($requestUri);
    }

    /**
     * Custom post type are to be added here
     */
    public function registerCustomPostTypes(){

    }

    /**
     * Custom Taxonomies are to be added here
     */
    public function registerTaxonomies(){

    }

    /**
     * Custom Sidebars are to be added here via $this->registerSidbar();
     */
    public function registerSidebars(){

    }

    /**
     * Register custom sidbar
     *
     * @param string $name
     * @param string $id
     */
    public function registerSidebar($name, $id){
        register_sidebar(array('name' => $name, 'id'=>$id));
    }

    /**
     * Enables post modofication processing.
     * You need to implement savePost(), deletePost() [and trashedPost()].
     *
     * @param integer $priority
     */
    public function addSupport_PostProcessing($priority = 100){
        $this->addAction('save_post', 'savePost', $priority, 2);
        $this->addAction('delete_post', 'deletePost', $priority, 1);
        $this->addAction('trashed_post', 'trashedPost', $priority, 1);
    }
    
    /**
     * This is a hook for save_post
     *
     * @param integer $postId
     * @param \WP_Post $post
     */
    public function savePost($postId, $post){
        
    }
    
    /**
     * This is a hook for delete_post
     *
     * @param integer $postId
     */
    public function deletePost($postId){
        
    }
    
    /**
     * This is a hook for trashed_post
     *
     * @param integer $postId
     */
    public function trashedPost($postId){
        $this->deletePost($postId);
    }

    /**
     * Enables custom link formats.
     * You need to implement postPermalink(), termLink(), userLink() and commentPermalink() methods.
     */
    public function addSupport_CustomPermalinks(){
        $this->addFilter('post_type_link', 'postPermalink', 1, 3);
        $this->addFilter('post_link', 'postPermalink', 1, 3);
        $this->addFilter('term_link', 'termLink', 1, 3);
        $this->addFilter('author_link', 'userLink', 1, 3);
        $this->addFilter('get_comment_link', 'commentPermalink', 1, 2);
    }
    
    /**
     * This is a hook for post_link and post_type_link
     *
     * @param string    $permalink
     * @param \WP_Post   $post
     * @param boolean   $leaveName
     *
     * @return string
     */
    public function postPermalink($permalink, $post, $leaveName = false){
        switch($post->post_type){
            case 'post':
                return '/entry/'.$post->ID.'/' . ($leaveName?'%postname%':$post->post_name);
        }
        return $permalink;
    }
    
    /**
     * This is a hook for term_link
     *
     * @param string $link
     * @param object $term
     * @param string $taxonomy
     * @return string
     */
    public function termLink($link, $term, $taxonomy){
        return $link;
    }

    /**
     * This is a hook for author_link
     *
     * @param string $link
     * @param integer $userId
     * @param string $nicename
     * @return string
     */
    public function userLink($link, $userId, $nicename){
        return sprintf('/user/%s/', $nicename);
    }

    /**
     * This is a hook for get_comment_link
     *
     * @param string $permalink
     * @param object $comment
     * @return string
     */
    public function commentPermalink($permalink, $comment){
        return $permalink;
    }

    /**
     * Check if media minimization is enabled
     *
     * @return boolean
     */
    public function isMediaMinimized() {
        return $this->mediaMinimized;
    }

    /**
     * Enable or disable media minimization
     *
     * @param boolean $mediaMinimized
     */
    public function setMediaMinimized($mediaMinimized) {
        $this->mediaMinimized = $mediaMinimized;
    }

    /**
     * Get /res relative 'src' dir
     *
     * @return string
     */
    public function getResSrcDir() {
        return $this->resSrcDir;
    }

    /**
     * Set /res relative 'src' dir
     *
     * @param string $resSrcDir
     */
    public function setResSrcDir($resSrcDir) {
        $this->resSrcDir = $resSrcDir;
    }

    /**
     * Get /res relative 'dist' dir
     *
     * @return mixed
     */
    public function getResDistDir() {
        return $this->resDistDir;
    }

    /**
     * Set /res relative 'dist' dir
     *
     * @param mixed $resDistDir
     */
    public function setResDistDir($resDistDir) {
        $this->resDistDir = $resDistDir;
    }

    /**
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false){

    }

	/**
	 * Set script rendering location (head|footer)
	 *
	 * @param $handle
	 * @param bool $inFooter
	 */
	public function setScriptLocation($handle, $inFooter = false){
		ResourceHelper::setScriptLocation($handle, $inFooter);
	}

    /**
     * Store application resource folder url for future use
     *
     * @param string $appId
     */
    public function populateResUrl($appId = ''){
        ResourceHelper::setApplicationResourceFolderUrl($appId?$appId:$this->appId, $this->getUrlRes($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()));
    }


    /**
	 * Alias to wp_enqueue_style, but the path is relative to '/res'
	 *
	 * @param string $handle
	 * @param string|bool $relativeResPath
	 * @param array $dependencies
	 * @param bool $version
	 * @param string $media
	 */
    public function enqueueStyle($handle, $relativeResPath = false, $dependencies = array(), $version = false, $media = 'all'){
	    if($relativeResPath){
		    $relativeResPath = $this->getUrlRes(($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath);
	    }
        ResourceHelper::enqueueStyle($handle, $relativeResPath, $dependencies, $version, $media);
    }

	/**
	 * Alias to wp_register_style, but the path is relative to '/res'
	 *
	 * @param string $handle
	 * @param string $relativeResPath
	 * @param array $dependencies
	 * @param bool $version
	 * @param string $media
	 */
	public function registerStyle($handle, $relativeResPath, $dependencies = array(), $version = false, $media = 'all'){
		$relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
		ResourceHelper::registerStyle($handle, $this->getUrlRes($relativeResPath), $dependencies, $version, $media);
	}

    /**
     * A little helper to update already registered style.
     *
     * @param $handle
     * @param $relativeResPath
     * @param null|array $dependencies
     * @param string|bool $version
     *
     * @return \_WP_Dependency|null
     */
    public function updateStyle($handle, $relativeResPath, $dependencies = null, $version = false){
        $relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
        return ResourceHelper::updateStyle($handle, $this->getUrlRes($relativeResPath), $dependencies, $version);
    }

    /**
     * Enqueue style. Utilizes wp_enqueue_style().
     * However if detects registered minimized and concatenated version enqueue it instead.
     * Ensures 'angular' as dependency
     *
     * @param $handle
     * @param string|bool $relativeResPath
     * @param array $dependencies
     * @param string|bool $ver
     * @param bool $in_footer
     */
    public function enqueueNgStyle($handle, $relativeResPath = false, $dependencies = array(), $ver = false, $in_footer = false) {
        $src = false;
        if($relativeResPath) {
            $relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
            $src = $this->getUrlRes($relativeResPath);
        }
        AngularHelper::enqueueStyle($handle, $src, $dependencies, $ver, $in_footer);
    }

    /**
     * Alias to wp_register_style, but the path is relative to '/res'.
     * Ensures 'angular' as dependency.
     *
     * @param string $handle
     * @param string $relativeResPath
     * @param array $dependencies
     * @param bool $version
     * @param string $media
     */
    public function registerNgStyle($handle, $relativeResPath, $dependencies = array(), $version = false, $media = 'all'){
        $relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
        AngularHelper::registerStyle($handle, $this->getUrlRes($relativeResPath), $dependencies, $version, $media);
    }

    /**
	 * Register minimized style file that contains all the min-cat styles defined by $handles.
	 *
	 * @param string $minHandle
	 * @param string $relativeResDistPath
	 * @param array $handles
	 * @param bool $version
	 * @param string $media
	 */
    public function registerMinimizedStyle($minHandle, $relativeResDistPath, $handles, $version = false, $media = 'all'){
        $relativeResPath = $this->getResDistDir() . $relativeResDistPath;
        ResourceHelper::registerMinimizedStyle($minHandle, $this->getUrlRes($relativeResPath), $handles, $version, $media);
    }

	/**
	 * Alias to wp_deregister_style
	 *
	 * @param $handle
	 */
	public function unregisterStyle($handle){
		ResourceHelper::unregisterStyle($handle);
	}

	/**
	 * Alias to wp_register_script, but the path is relative to '/res'
	 *
	 * @param string $handle
	 * @param string|bool $relativeResPath
	 * @param array $dependencies
	 * @param bool $version
	 * @param bool $inFooter
	 */
	public function enqueueScript($handle, $relativeResPath = false, $dependencies = array(), $version = false, $inFooter = true){
		if($relativeResPath){
			$relativeResPath = $this->getUrlRes(($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath);
		}
		ResourceHelper::enqueueScript($handle, $relativeResPath, $dependencies, $version, $inFooter);
	}

	/**
	 * Alias to wp_register_script, but the path is relative to '/res'
	 *
	 * @param string $handle
	 * @param string $relativeResPath
	 * @param array $dependencies
	 * @param bool $version
	 * @param bool $inFooter
	 */
	public function registerScript($handle, $relativeResPath, $dependencies = array(), $version = false, $inFooter = true){
		$relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
		ResourceHelper::registerScript($handle, $this->getUrlRes($relativeResPath), $dependencies, $version, $inFooter);
	}

    /**
     * A little helper to update already registered script.
     *
     * @param $handle
     * @param $relativeResPath
     * @param null|array $dependencies
     * @param string|bool $version
     *
     * @return \_WP_Dependency|null
     */
    public function updateScript($handle, $relativeResPath, $dependencies = null, $version = false){
        $relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
        return ResourceHelper::updateScript($handle, $this->getUrlRes($relativeResPath), $dependencies, $version);
    }

    /**
     * Enqueue angular script. Utilizes AngularHelper::enqueueScript().
     * See AngularHelper::enqueueScript() for more details.
     *
     * @param $handle
     * @param bool $relativeResPath
     * @param array $dependencies
     * @param callable|null $enqueueCallback
     * @param bool $ver
     * @param bool $in_footer
     */
    public function enqueueNgScript($handle, $relativeResPath = false, $dependencies = array(), $enqueueCallback = null, $ver = false, $in_footer = false){
        $src = false;
        if($relativeResPath) {
            $relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
            $src = $this->getUrlRes($relativeResPath);
        }
        AngularHelper::enqueueScript($handle, $src, $dependencies, $enqueueCallback, $ver, $in_footer);
    }

    /**
     * Alias to AngularHelper::registerScript(), but the path is relative to '/res'
     * See AngularHelper::registerScript() for more details
     *
     * @param $handle
     * @param $relativeResPath
     * @param array $dependencies
     * @param callable|null $enqueueCallback
     * @param bool $version
     * @param bool $inFooter
     */
	public function registerNgScript($handle, $relativeResPath, $dependencies = array(), $enqueueCallback = null, $version = false, $inFooter = true){
		$relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
		AngularHelper::registerScript($handle, $this->getUrlRes($relativeResPath), $dependencies, $enqueueCallback, $version, $inFooter);
	}

	/**
	 * Register minimized script file that contains all the min-cat scripts defined by $handles.
	 *
	 * @param string $minHandle
	 * @param string $relativeResDistPath
	 * @param array $handles
	 * @param bool $version
	 * @param bool $inFooter
	 */
    public function registerMinimizedScript($minHandle, $relativeResDistPath, $handles, $version = false, $inFooter = true){
        $relativeResPath = $this->getResDistDir() . $relativeResDistPath;
        ResourceHelper::registerMinimizedScript($minHandle, $this->getUrlRes($relativeResPath), $handles, $inFooter);
    }

	/**
	 * Alias to wp_deregister_script
	 *
	 * @param string $handle
	 */
	public function unregisterScript($handle){
        ResourceHelper::unregisterScript($handle);
	}

    /**
     * Not implemented yet and to be revised
     *
     * @param $handle
     * @param $relativeResPath
     * @param array $dependencies
     */
    public function registerScriptNls($handle, $relativeResPath, $dependencies = array()){
//        TODO: Zend independent NslHelper
//        NlsHelper::registerScriptNls($handle, $relativeResJsPath, $dependencies, null, null, $this->basePath);
    }

	/**
     * Alias to ResourceHelper::enqueueScriptStyle()
     *
	 * @param string $handle
	 * @param bool $scriptInFooter
	 */
	public function enqueueScriptStyle($handle, $scriptInFooter = true){
		ResourceHelper::enqueueScriptStyle($handle, $scriptInFooter);
	}

    /**
     * Enqueue both script and style with the same $handle.
     * Uses minimized versions if detects.
     *
     * Should be used to enqueue angular scripts to bootstrap them correctly
     *
     * @param $handle
     */
    public function enqueueNgScriptStyle($handle) {
        AngularHelper::enqueueScriptStyle($handle);
    }

    /**
     * Read and parse bower config
     *
     * @param bool $minimize
     * @return array|bool
     */
    public function getBower($minimize = false){
        if($this->bower === null){
            $this->bower = false;
            $bowerFile = $this->basePath.'/bower.json';
            if(file_exists($bowerFile)){
                $json = FsHelper::readFile($bowerFile);
                $bowerData = json_decode($json);

                $bowerDir = $this->basePath.'/bower_components/';

                $bowerRcFile = $this->basePath.'/.bowerrc';
                $bowerRcData = null;
                if(file_exists($bowerRcFile)) {
                    $json = FsHelper::readFile($bowerRcFile);
                    $bowerRcData = json_decode($json);
                    $bowerDir = Util::getItem($bowerRcData, 'directory', 'bower_components').'/';
                }
                if(is_dir($this->basePath.'/'.$bowerDir)){
                    $this->bower = array(
                        'bower.json' => $bowerData,
                        '.bowerrc' => $bowerRcData,
                        'dir' => $bowerDir,
                        'minimize' => $minimize,
                    );
                }
            }
        }
        return $this->bower;
    }

    /**
     * This function discovers installed bower packages and registers them if they are not already registered
     * if true passed, newer versions will override older ones
     *
     * @param boolean $overrideWithNew
     */
    public function registerBowerResources($overrideWithNew = true){
        $bower = $this->getBower();

        if($bower){
            $bowerData = Util::getItem($bower, 'bower.json');
            $libs = Util::getItem($bowerData, 'dependencies', array());
            $bowerDir = Util::getItem($bower, 'dir');

            foreach($libs as $lib => $ver){
                $this->registerBowerComponent($lib, $bowerDir.$lib, $overrideWithNew);
            }
        }
    }

    /**
     * Registers bower component
     * $path is relative to baseDir
     *
     * @param string $name
     * @param string $path
     * @param bool $overrideWithNew
     */
    public function registerBowerComponent($name, $path = null, $overrideWithNew = true){
        global $wp_styles, $wp_scripts;

        $bower = $this->getBower();

        if(!$path && $bower){
            $path = Util::getItem($bower, 'dir').$name;
        }

        if($path) {
	        $bowerFile = $this->basePath . '/' . $path . '/bower.json';
	        $dotBowerFile = $this->basePath . '/' . $path . '/.bower.json';
            if (file_exists($bowerFile)) {
                $json = FsHelper::readFile($bowerFile);
                $bowerData = json_decode($json, true);
	            if(file_exists($dotBowerFile)){
		            $json = FsHelper::readFile($dotBowerFile);
		            $bowerData = array_merge($bowerData, json_decode($json, true));
	            }
                $bowerVer = Util::getItem($bowerData, 'version', '0.0.0');
                $needOverrideJs = false;
                if ($overrideWithNew) {
                    $registered = Util::getItem($wp_scripts, 'registered');
                    $existing = Util::getItem($registered, $name);
                    if ($existing) {
                        $existingVer = Util::getItem($existing, 'ver', '0.0.0');
                        $needOverrideJs = Util::cmpVersion($bowerVer, $existingVer) > 0;
                    }
                }
                $needOverrideCss = false;
                if ($overrideWithNew) {
                    $registered = Util::getItem($wp_styles, 'registered');
                    $existing = Util::getItem($registered, $name);
                    if ($existing) {
                        $existingVer = Util::getItem($existing, 'ver', '0.0.0');
                        $needOverrideCss = Util::cmpVersion($bowerVer, $existingVer) > 0;
                    }
                }

                $mainFiles = Util::getItem($bowerData, 'main');
                if ($mainFiles) {
                    if (!is_array($mainFiles)) {
                        $mainFiles = array($mainFiles);
                    }
                    $dependencies = array();
                    $bowerDependencies = Util::getItem($bowerData, 'dependencies');
                    if ($bowerDependencies) {
                        $dependencies = array_keys($bowerDependencies);
                    }

                    foreach ($mainFiles as $file) {
                        $ext = FsHelper::getExtension($file);
                        $filePath = realpath($this->basePath . '/' . $path . '/' . $file);
                        $minimize = Util::getItem($bower, 'minimize');
                        if($minimize){
                            $filePathMin = FsHelper::setExtensionPrefix($filePath, 'min');
	                        if(file_exists($filePathMin)){
		                        $filePath = $filePathMin;
	                        }
                        }
	                    $bn = strtolower(basename($file, '.' . $ext));
                        if (preg_match("%(jquery[\\.\\-_])?$name([\\.\\-_]\\d+)*([\\.\\-_]min)?%iu", $bn)) {
                            $relPath = str_replace($this->basePath, '', $filePath);
                            switch ($ext) {
                                case 'css':
                                    if ($needOverrideCss) {
                                        wp_deregister_script($name);
//	                                    $this->unregisterScript($name);
                                    }
                                    ResourceHelper::registerStyle($name, $this->getUrl($relPath), [], $bowerVer, false);
//									$this->registerStyle($name, $this->getUrl($relPath), [], $bowerVer, false);
                                    break;
                                case 'js':
                                    foreach ($dependencies as $dep) {
                                        $depPath = preg_replace("%$name$%", $dep, $path);
                                        $this->registerBowerComponent($dep, $depPath, $overrideWithNew);
                                    }
                                    if ($needOverrideJs) {
                                        wp_deregister_script($name);
//	                                    $this->unregisterScript($name);
                                    }
                                    ResourceHelper::registerScript($name, $this->getUrl($relPath), $dependencies, $bowerVer, true);
//	                                $this->registerScript($name, $this->getUrl($relPath), [], $bowerVer, true);
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Read and parse composer config
     *
     * @return array|bool
     */
    public function getComposer(){
        if($this->composer === null){
            $this->composer = false;
            $composerFile = $this->basePath.'/composer.json';
            $composerLockFile = $this->basePath.'/composer.lock';
            if(file_exists($composerFile)){
                $json = FsHelper::readFile($composerFile);
                $composerData = json_decode($json);
                $composerLock = array();
                if(file_exists($composerLockFile)) {
                    $lock = FsHelper::readFile($composerLockFile);
                    $composerLock = json_decode($lock);
                }
                $composerDir = $this->basePath.'/vendor/';

                if(is_dir($composerDir)){
                    if(file_exists($composerDir.'autoload.php')){
                        require_once $composerDir.'autoload.php';
                    }
                    $this->composer = array(
                        'composer.json' => $composerData,
                        'composer.lock' => $composerLock,
                        'dir' => $composerDir,
                    );
                }
            }
        }

        return $this->composer;
    }

    /**
     * Go through vendor folder if it exists.
     * Find composer.json
     * Call "wp-init" callback.
     *
     * '$ composer install' should be called before
     *
     */
    public function registerComposerPlugins(){
        $composer = $this->getComposer();
        if($composer){
            $composerData = Util::getItem($composer, 'composer.json');
            $composerLock = Util::getItem($composer, 'composer.lock');
            $composerDir = Util::getItem($composer, 'dir');

            if($composerLock){
                $packages = Util::getItem($composerLock, 'packages', array());
                foreach($packages as $pkg){
                    $lib = $pkg->name;
                    $this->registerComposerPlugin($lib, $composerDir.$lib);
                }
            }else{
                $libs = Util::getItem($composerData, 'require', array());
                foreach($libs as $lib => $ver){
                    $this->registerComposerPlugin($lib, $composerDir.$lib);
                }
            }

        }

    }

    /**
     * Register composer Chayka plugin
     *
     * @param string $name
     * @param string $path
     */
    public function registerComposerPlugin($name, $path = null){
        $composer = $this->getComposer();

        if(!$path && $composer){
            $path = Util::getItem($composer, 'dir').$name;
        }

        if($path) {
            $chaykaFile = $path . '/chayka.json';
            if (file_exists($chaykaFile)) {
                $json = FsHelper::readFile($chaykaFile);
                $chaykaData = json_decode($json);
                $phpNamespace =Util::getItem($chaykaData, 'phpNamespace');
                if($phpNamespace){
                    call_user_func(array($phpNamespace.'\\Plugin', 'init'));
	                $sidebarFn = $chaykaFile = $path . '/Sidebar.php';
	                if(file_exists($sidebarFn)){
		                require_once $sidebarFn;
	                }

                }
            }else{
                $composerFile = $path . '/composer.json';
                if (file_exists($composerFile)) {
                    $json = FsHelper::readFile($composerFile);
                    $composerData = json_decode($json);
                    $plugin = Util::getItem($composerData, 'chayka-wp-plugin');
                    if($plugin){
                        call_user_func(array($plugin, 'init'));
                    }

                }
            }
        }

    }

    /**
     * Register your action hooks here using $this->addAction();
     */
    public function registerActions(){

    }

    /**
     * Alias to add_action, but if the $method is a string then
     * $this->getCallbackMethod($method) is used instead.
     *
     * @param $action
     * @param string|array $method
     * @param int $priority
     * @param int $numberOfArguments
     * @return bool|void
     */
    public function addAction($action, $method, $priority = 10, $numberOfArguments = 1){
        return add_action($action, is_string($method) ? $this->getCallbackMethod($method) : $method, $priority, $numberOfArguments);
    }

	/**
	 * Alias to remove_action, but if the $method is a string and method exists then
	 * $this->getCallbackMethod($callback) is used instead.
	 * @param $tag
	 * @param $callback
	 * @param int $priority
	 *
	 * @return bool
	 */
	public function removeAction($tag, $callback, $priority = 10){
		if(is_string($callback) && method_exists($this, $callback)){
			$callback = $this->getCallbackMethod($callback);
		}
		return remove_action($tag, $callback, $priority);
	}

    /**
     * Register your action hooks here using $this->addFilter();
     */
    public function registerFilters(){

    }

    /**
     * Alias to add_filter, but if the $method is a string then
     * $this->getCallbackMethod($method) is used instead.
     *
     * @param $filter
     * @param $method
     * @param int $priority
     * @param int $numberOfArguments
     * @return bool|void
     */
    public function addFilter($filter, $method, $priority = 10, $numberOfArguments = 1){
        return add_filter($filter, is_string($method) ? $this->getCallbackMethod($method) : $method, $priority, $numberOfArguments);
    }

	/**
	 * Alias to remove_filter, but if the $method is a string and method exists then
	 * $this->getCallbackMethod($callback) is used instead.
	 * @param $tag
	 * @param $callback
	 * @param int $priority
	 *
	 * @return bool
	 */
	public function removeFilter($tag, $callback, $priority = 10){
		if(is_string($callback) && method_exists($this, $callback)){
			$callback = $this->getCallbackMethod($callback);
		}
		return remove_filter($tag, $callback, $priority);
	}

	/**
     * Enables support for console pages that will be typically rendered by AdminController.
     * You should implement registerConsolePages();
     */
    public function addSupport_ConsolePages(){
        $this->addAction('admin_menu', 'registerConsolePages');
    }
    
    /**
     * Override to add addConsolePage() calls
     */
    public function registerConsolePages(){
        
    }

    /**
     * Callback method that will render console pages using AdminController
     */
    public function renderConsolePage(){
        $page = Util::getItem($_GET, 'page');
        $requestUri = Util::getItem($this->consolePageUris, $page);
        
        $this->renderRequest($requestUri);
    }

    /**
     * Add Console Page. Much like add_menu_page,
     * but instead of callback you provide some controller uri (e.g. 'admin/some-action')
     * see https://developer.wordpress.org/resource/dashicons/#wordpress for icons
     *
     * @param string $title
     * @param string $capability
     * @param string $menuSlug
     * @param string $renderUri
     * @param string $relativeResIconUrl
     * @param integer $position
     */
    public function addConsolePage($title, $capability, $menuSlug,
        $renderUri='', $relativeResIconUrl='', $position=null){

        $this->consolePageUris[$menuSlug] = $renderUri;

        if($relativeResIconUrl && !preg_match('%^dashicons-%', $relativeResIconUrl)){
            $relativeResIconUrl = $this->getUrlRes($relativeResIconUrl);
        }

        add_menu_page($title, $title, $capability, $menuSlug,
            $this->getCallbackMethod('renderConsolePage'), 
            $relativeResIconUrl, $position);
    }

    /**
     * Add Console Page. Much like add_submenu_page,
     * but instead of callback you provide some controller uri (e.g. 'admin/some-action')
     *
     * @param $parentSlug
     * @param $title
     * @param $capability
     * @param $menuSlug
     * @param string $renderUri
     */
    public function addConsoleSubPage($parentSlug, $title,
            $capability, $menuSlug, $renderUri=''){

        $this->consolePageUris[$menuSlug] = $renderUri;
        
        add_submenu_page($parentSlug, $title, $title, $capability, $menuSlug,
            $this->getCallbackMethod('renderConsolePage'));
    }
    
    /**
     * Enable metaboxes rendering using MetaboxController.
     * You should implement registerMetaboxes().
     */
    public function addSupport_Metaboxes(){
        $this->addAction('add_meta_boxes', 'addMetaboxes');
        $this->addAction('do_meta_boxes', 'unregisterMetaboxes');
        $this->addAction('save_post', 'updateMetaboxes', 50, 2);
        $this->registerMetaboxes();
    }
    
    /**
     * Override to add addMetabox() calls;
     */
    public function registerMetaboxes(){
        
    }

    /**
     * Callback for rendering metaboxes.
     *
     * @param \WP_Post $post
     * @param string $box
     */
    public function renderMetabox($post, $box){
        $boxId = Util::getItem($box, 'id');
        $params = Util::getItem($this->metaboxUris, $boxId, array());
        $requestUri = Util::getItem($params, 'renderUri');
        $this->renderRequest($requestUri);
    }

    /**
     * Callback for 'save_post' hook, updating metabox, should be revised (implement logic here).
     *
     * @param integer $postId
     * @param \WP_Post $post
     */
    public function updateMetaboxes($postId, $post){

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        foreach($this->metaboxUris as $id=>$uri){
            $action = preg_replace(array('%^\/?metabox\/%', '%\/$%'), '', $uri['renderUri']);
//            $nonce = Util::getItem($_POST, $id.'_nonce');
            $nonce = Util::getItem($_POST, $action.'_nonce');

            if (!wp_verify_nonce($nonce, $action)) {
                continue;
            }

            $params = InputHelper::getParams();
            foreach($params as $key => $value){
                $match = array();

                if(preg_match('%^metabox-([\w\d_]+)$%i', $key, $match)){
                    if($value){
                        PostModel::updatePostMeta($postId, $match[1], $value);
                    }else{
                        PostModel::deletePostMeta($postId, $match[1]);
                    }
                }
            }
        }
        return;
    }
    
    /**
     * Add Metabox
     * 
     * @param string $id
     * @param string $title
     * @param string $renderUri
     * @param string $context 'normal', 'advanced', or 'side'
     * @param string $priority 'high', 'core', 'default' or 'low'
     * @param string|array $screen post type
     */
    public function addMetabox($id, $title, $renderUri, $context = 'advanced', $priority = 'default', $screen = null){
        $this->metaboxUris[$id] = array(
            'title' => $title,
            'renderUri' => $renderUri,
            'context' => $context,
            'priority' => $priority,
            'screen' => $screen,
        );
        
    }

    /**
     * Callback method for 'add_meta_boxes' hook,
     * adding metaboxes when rendering post editor page
     */
    public function addMetaboxes(){
        foreach($this->metaboxUris as $id => $params){
            $title = Util::getItem($params, 'title');
            $context = Util::getItem($params, 'context');
            $priority = Util::getItem($params, 'priority');
            $screens = Util::getItem($params, 'screen');
            if(is_array($screens)){
                foreach($screens as $screen){
                    add_meta_box($id, $title, $this->getCallbackMethod('renderMetabox'), $screen, $context, $priority);
                }
            }else{
                add_meta_box($id, $title, $this->getCallbackMethod('renderMetabox'), $screens, $context, $priority);
            }
        }
        //  TODO: revise style naming
        wp_enqueue_style('brx-wp-admin');
    }

    /**
     * Remove registered metabox.
     * Handy for blocking metaboxes from parent themes.
     *
     * more info http://codex.wordpress.org/Function_Reference/remove_meta_box
     *
     * @param string $id
     * @param string|array $pages
     * @param string $context 'normal', 'advanced', or 'side'.
     */
    public function removeMetabox($id, $pages, $context = 'advanced'){
        if(is_string($pages)){
            $pages = preg_split('%\s+%', $pages);
        }
        foreach($pages as $page){
            remove_meta_box($id, $page, $context);
        }
    }

    /**
     * Callback method for 'add_meta_boxes' hook,
     * adding metaboxes when rendering post editor page
     */
    public function unregisterMetaboxes(){

    }

    /**
     * Override to add addShortcodes() calls;
     */
    public function registerShortcodes(){

    }

    /**
     * Alias to add_shortcode(), but instead of callback ShortcodeController renderer is used
     *
     * @param string $shortcode
     * @param string $uri
     */
    public function addShortcode($shortcode, $uri=''){
        if(!$uri){
            $uri = '/shortcode/'.str_replace('_', '-', $shortcode);
        }
        $this->shortcodeUris[$shortcode]=$uri;
        add_shortcode($shortcode, $this->getCallbackMethod('renderShortcode'));
    }

    /**
     * Callback for shortcode rendering
     *
     * @param $atts
     * @param $content
     * @param $shortcode
     * @return string
     */
    public function renderShortcode($atts, $content, $shortcode){
        if(!$atts){
            $atts = array();
        }
        $uri = Util::getItem($this->shortcodeUris, $shortcode);
        if($uri){
            if($content){
                $atts['content']=$content;
            }
            $uri.='?'.http_build_query($atts);
            return $this->processRequest($uri);
        }
        return "";
    }

    /**
     * Setup whether black top admin bar should be shown outside of admin area:
     *  false - always hide
     *  true - always show if the user is logged in
     *  'admin' - show only if logged in user is administrator
     *
     * TODO: fix it,
     *
     * @param bool|string $show
     */
    public function showAdminBar($show = true){
        self::$adminBar = $show;
        $this->addFilter('show_admin_bar', 'isAdminBarShown', 1, 1);
    }

    /**
     * Hide black top admin bar outside of admin area
     */
    public function hideAdminBar(){
        $this->showAdminBar(false);
    }

    /**
     * Show black top admin bar outside of admin area to admin only
     */
    public function showAdminBarToAdminOnly(){
        $this->showAdminBar('admin');
    }

    /**
     * Hook that is called upon filter 'show_admin_bar'
     *
     * @param bool $show
     *
     * @return bool
     */
    public function isAdminBarShown($show){
        if(isset(self::$adminBar)){
            if(self::$adminBar){
                if(self::$adminBar == 'admin'){
                    return current_user_can('administrator') || is_admin();
                }

                return true;
            }else{
                return false;
            }
        }
        return $show;
    }

}


