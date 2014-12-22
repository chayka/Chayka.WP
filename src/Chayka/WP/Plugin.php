<?php

namespace Chayka\WP;

use Chayka\Helpers\Util;
use Chayka\Helpers\InputHelper;
use Chayka\Helpers\FsHelper;
use Chayka\MVC\ApplicationDispatcher;
use Chayka\MVC\Application;
use Chayka\MVC\View;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\OptionHelper;
use Chayka\WP\Models\PostModel;
use Exception;
use WP_Post;

abstract class Plugin{

    protected static $adminBar;

    protected $needStyles = true;
    
    protected $currentDbVersion = '1.0';
    
    protected $consolePageUris = array();

    protected $metaBoxUris = array();

    protected $shortcodeUris = array();

    protected $baseUrl;
    
    protected $basePath;
    
    protected $appId;

    protected $application;

    protected $bower = null;

    protected $composer = null;

    protected static $uriProcessing = false;

    protected static $instance = null;

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
        $APP_ID = strtoupper(str_replace('\\', '_', $this->appId));
        ApplicationDispatcher::registerApplication($this->appId, $this->application, $routes);

        defined($APP_ID.'_PATH')
            || define($APP_ID.'_PATH', $this->basePath);
        defined($APP_ID.'_URL')
            || define($APP_ID.'_URL',  $this->baseUrl);
        defined($APP_ID.'_APP_PATH')
            || define($APP_ID.'_APP_PATH', $this->basePath.'app');

        $minimize = OptionHelper::getOption('MinimizeMedia');
//        die($minimize);
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
        $relPath = preg_replace('%^.*wp-content%', '/wp-content', $path);
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
    public function getUrlRes($relativeResPath){
        return $this->getUrl('res/'.$relativeResPath);
    }

    /**
     * Perform update based on db structure version history
     *
     * DB Version history
     * @param array(String) $versionHistory
     */
    public function dbUpdate($versionHistory = array('1.0')){
        $this->currentDbVersion = end($versionHistory);
        reset($versionHistory);
        DbHelper::dbUpdate($this->currentDbVersion, $this->getAppId().'.dbVersion', $this->getBasePath().'app/sql', $versionHistory);
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
     * @param string $label
     * @param string $urlPattern
     * @param array $defaults
     * @param array $paramPatterns
     */
    public function addRoute($label = 'default', $urlPattern = '?controller/?action/*', $defaults = array('controller' => 'index', 'action'=>'index'), $paramPatterns = array()){
        $this->application->getRouter()->addRoute($label, $urlPattern, $defaults, $paramPatterns);
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
     * @param WP_Post $post
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
     * @param WP_Post   $post
     * @param boolean   $leavename
     * @return string
     */
    public function postPermalink($permalink, $post, $leavename = false){
        switch($post->post_type){
            case 'post':
                return '/entry/'.$post->ID.'/'.($leavename?'%postname%':$post->post_name);
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
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false){

    }

    /**
     * Alias to wp_register_style, but the path is relative to '/res'
     *
     * @param $handle
     * @param $relativeResPath
     * @param array $dependencies
     */
    public  function registerStyle($handle, $relativeResPath, $dependencies = array()){
        wp_register_style($handle, $this->getUrlRes($relativeResPath), $dependencies);
    }

    /**
     * Alias to wp_register_script, but the path is relative to '/res'
     *
     * @param $handle
     * @param $relativeResPath
     * @param array $dependencies
     */
    public function registerScript($handle, $relativeResPath, $dependencies = array()){
        wp_register_script($handle, $this->getUrlRes($relativeResPath), $dependencies);
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
            if (file_exists($bowerFile)) {
                $json = FsHelper::readFile($bowerFile);
                $bowerData = json_decode($json);
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
                        $dependencies = array_keys(get_object_vars($bowerDependencies));
                    }

                    foreach ($mainFiles as $file) {
                        $ext = FsHelper::getExtension($file);
                        $filePath = realpath($this->basePath . '/' . $path . '/' . $file);
                        $minimize = Util::getItem($bower, 'minimize');
                        if($minimize){
                            $filePathMin = FsHelper::setExtensionPrefix($filePath, 'min');
                            $filePath = file_exists($filePathMin)?$filePathMin:$filePath;
                        }
                        if (basename($file, '.' . $ext) == $name) {
                            $relPath = str_replace($this->basePath, '', $filePath);
                            switch ($ext) {
                                case 'css':
                                    if ($needOverrideCss) {
                                        wp_deregister_script($name);
                                    }
                                    wp_register_style($name, $this->getUrl($relPath));
                                    break;
                                case 'js':
                                    foreach ($dependencies as $dep) {
                                        $depPath = preg_replace("%$name$%", $dep, $path);
                                        $this->registerBowerComponent($dep, $depPath, $overrideWithNew);
                                    }
                                    if ($needOverrideJs) {
                                        wp_deregister_script($name);
                                    }
                                    wp_register_script($name, $this->getUrl($relPath), $dependencies, $bowerVer);
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
     * Go through vendor folder if it exests.
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
     * Register composer plugin
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

        add_menu_page($title, $title, $capability, $menuSlug,
            $this->getCallbackMethod('renderConsolePage'), 
            $relativeResIconUrl?$this->getUrlRes($relativeResIconUrl):'', $position);
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
     * You should implement registerMetaBoxes().
     */
    public function addSupport_Metaboxes(){
        $this->addAction('add_meta_boxes', 'addMetaBoxes');
        $this->addAction('save_post', 'updateMetaBoxes', 50, 2);
        $this->registerMetaBoxes();
    }
    
    /**
     * Override to add addMetaBox() calls;
     */
    public function registerMetaBoxes(){
        
    }

    /**
     * Callback for rendering metaboxes.
     *
     * @param WP_Post $post
     * @param string $box
     */
    public function renderMetaBox($post, $box){
        $boxId = Util::getItem($box, 'id');
        $params = Util::getItem($this->metaBoxUris, $boxId, array());
        $requestUri = Util::getItem($params, 'renderUri');
        $this->renderRequest($requestUri);
    }

    /**
     * Callback for 'save_post' hook, updating metabox, should be revised (implement logic here).
     *
     * @param integer $postId
     * @param WP_Post $post
     */
    public function updateMetaBoxes($postId, $post){

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        foreach($this->metaBoxUris as $id=>$uri){
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
    public function addMetaBox($id, $title, $renderUri, $context = 'advanced', $priority = 'default', $screen = null){
        $this->metaBoxUris[$id] = array(
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
     *
     */
    public function addMetaBoxes(){
        foreach($this->metaBoxUris as $id => $params){
            $title = Util::getItem($params, 'title');
            $context = Util::getItem($params, 'context');
            $priority = Util::getItem($params, 'priority');
            $screens = Util::getItem($params, 'screen');
            if(is_array($screens)){
                foreach($screens as $screen){
                    add_meta_box($id, $title, $this->getCallbackMethod('renderMetaBox'), $screen, $context, $priority);
                }
            }else{
                add_meta_box($id, $title, $this->getCallbackMethod('renderMetaBox'), $screens, $context, $priority);
            }
        }
        //  TODO: revise style naming
        wp_enqueue_style('brx-wp-admin');
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

    public function showAdminBar($show = true){
        self::$adminBar = $show;
        $this->addFilter('show_admin_bar', 'isAdminBarShown', 1, 1);
    }

    /** deprecated **/
    public function hideAdminBar(){
        self::$adminBar = false;
        $this->addFilter('show_admin_bar', 'isAdminBarShown', 1, 1);
    }

    /** deprecated **/
    public function showAdminBarToAdminOnly(){
        self::$adminBar = 'admin';
        $this->addFilter('show_admin_bar', 'isAdminBarShown', 1, 1);
    }

    /** deprecated **/
    public function isAdminBarShown($show){
        if(self::$adminBar){
            if(self::$adminBar == 'admin'){
                return current_user_can('administrator') || is_admin();
            }

            return true;
        }

        return false;
    }


}


