<?php

namespace Chayka\WP;

use Chayka\MVC\Application;
use Chayka\MVC\ApplicationDispatcher;
use Chayka\WP\Helpers\OptionHelper;
use Chayka\WP\Helpers\ResourceHelper;

abstract class Theme extends Plugin{
    
    protected $excerptLength = 30;
    protected $excerptMore = '...';
//    protected $adminBar = true;

    /**
     * Theme Constructor
     *
     * @param string $__file__
     * @param array $routes
     */
    public function __construct($__file__, $routes = array()) {
        $this->basePath = self::dirPath( $__file__ );
        $this->baseUrl = self::dirUrl($__file__);

        $namespace = $this->getNamespace();
        $this->appId = $namespace ? $namespace : $this->getClassName();
        $this->application = new Application($this->basePath.'app', $this->appId);

        ApplicationDispatcher::registerApplication($this->application, $routes);

        $minimize = OptionHelper::getOption('minimizeMedia');
        ResourceHelper::setMediaMinimized($minimize);
        $this->setMediaMinimized($minimize);
        $this->registerResources($minimize);
        $this->addRoute('default');
        $this->registerRoutes();
        $this->registerCustomPostTypes();
        $this->registerTaxonomies();
        $this->registerSidebars();
        $this->registerShortcodes();
        $this->registerActions();
        $this->registerFilters();
        $this->registerNavMenus();
//        $this->addFilter('wp_nav_menu_objects', 'customizeNavMenuItems', 1, 2);
    }

    /**
     * Register your nav menus here using $this->registerNavMenu();
     */
    public function registerNavMenus(){

    }

    /**
     * Alias of register_nav_menu
     *
     * @param $location
     * @param $description
     */
    public function registerNavMenu($location, $description){
        register_nav_menu($location, $description);
    }

//    /**
//     * Override this method to customize menu
//     *
//     * @param array $items
//     * @param array $args
//     * @return array
//     */
//    public function customizeNavMenuItems($items, $args){
//        return $items;
//    }

    /**
     * Enable thumbnails support
     *
     * @param int $width
     * @param int $height
     * @param bool $crop
     */
    public function addSupport_Thumbnails($width = 0, $height = 0, $crop = false){
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size($width, $height, $crop);
    }

    /**
     * Setup excerpt behaviour
     *
     * @param int $length
     * @param string $more
     */
    public function addSupport_Excerpt($length = 30, $more = '...'){
        $this->excerptLength = $length;
        $this->excerptMore = $more;
        $this->addFilter('excerpt_length', 'excerptLength', 1, 1);
        $this->addFilter('excerpt_more', 'excerptMore');
    }

    /**
     * This is a callback for 'excerpt_length' hook
     *
     * @return int
     */
    public function excerptLength(){
        return $this->excerptLength;
    }

    /**
     * This is a callback for 'excerpt_more' hook
     *
     * @return string
     */
    public function excerptMore(){
        return $this->excerptMore;
    }

	/**
	 * Add custom editor css styles
	 *
	 * @param string|array $relativeResPath
	 */
	public function registerEditorStyle($relativeResPath){
		if(is_array($relativeResPath)){
			foreach($relativeResPath as $i=>$path){
				$relativeResPath[$i] = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $path;
			}
		}else{
			$relativeResPath = ($this->isMediaMinimized() ? $this->getResDistDir() : $this->getResSrcDir()) . $relativeResPath;
		}
		add_editor_style($relativeResPath);
	}
    
//    public function showAdminBar($show = true){
//        $this->adminBar = $show;
//    }
//
//    public function hideAdminBar(){
//        $this->adminBar = false;
//    }
//
//    public function showAdminBarToAdminOnly(){
//        $this->adminBar = 'admin';
//    }
//
//    public function isAdminBarShown($show){
//        if($this->adminBar){
//            if($this->adminBar == 'admin'){
//                return current_user_can('administrator') || is_admin();
//            }
//
//            return true;
//        }
//
//        return false;
//    }
    
}

