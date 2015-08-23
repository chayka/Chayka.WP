Chayka\WP\Theme
===============

Class Theme extends Plugin and implements theme bootstrap file in Chayka.Framework.

When you create a new theme you define a bootstrap object class
that is a descendant of this class

All the theme configuration and hooking must be done in such
a bootstrap class implementation


* Class name: Theme
* Namespace: Chayka\WP
* This is an **abstract** class
* Parent class: [Chayka\WP\Plugin](Chayka-WP-Plugin.md)





Properties
----------


### $excerptLength

    protected integer $excerptLength = 30

The length of generated post excerpt



* Visibility: **protected**


### $excerptMore

    protected string $excerptMore = '...'

The string that is appended to truncated post excerpt when generated automatically



* Visibility: **protected**


### $adminBar

    protected string $adminBar

A variable that defines whether black admin bar should be shown outside admin area
 false - always hide
 true - always show if the user is logged in
 'admin' - show only if logged in user is administrator



* Visibility: **protected**
* This property is **static**.


### $needStyles

    protected boolean $needStyles = true

This variable can be used to block plugin's styles if you want to apply custom styles
E.g. you can call \Chayka\Comments\Plugin::blockStyles(true) out of your theme definition



* Visibility: **protected**


### $currentDbVersion

    protected string $currentDbVersion = '1.0'

Current DB version, used in dbUpdate() method.

TODO: Need DB install/update script guide

* Visibility: **protected**


### $consolePageUris

    protected array $consolePageUris = array()

Array of console pages params used for console page creation hooking



* Visibility: **protected**


### $metaBoxUris

    protected array $metaBoxUris = array()

Array of meta boxes params used for meta boxes creation hooking



* Visibility: **protected**


### $shortcodeUris

    protected array $shortcodeUris = array()

Array of short codes params used for shortcodes creation hooking



* Visibility: **protected**


### $baseUrl

    protected string $baseUrl

Base URL for this application (plugin or theme)



* Visibility: **protected**


### $basePath

    protected string $basePath

Base path for this application (plugin or theme)



* Visibility: **protected**


### $resSrcDir

    protected string $resSrcDir = ""

Variable to keep 'res/src' folder path, when using src & dist folders to keep your resources



* Visibility: **protected**


### $resDistDir

    protected string $resDistDir = ""

Variable to keep 'res/dist' folder path, when using src & dist folders to keep your resources



* Visibility: **protected**


### $mediaMinimized

    protected boolean $mediaMinimized = false

Flag that defines usage of minimized sources when available



* Visibility: **protected**


### $appId

    protected string $appId

Application id



* Visibility: **protected**


### $application

    protected \Chayka\MVC\Application $application

Instance of Chayka\MVC\Application that is gonna be used for MVC url processing



* Visibility: **protected**


### $bower

    protected array $bower = null

Hash map that holds bower configs (bower.json, .bowerrc, etc.)



* Visibility: **protected**


### $composer

    protected array $composer = null

Hash map that holds composer configs (composer.json, composer.lock, etc.)



* Visibility: **protected**


### $uriProcessing

    protected boolean $uriProcessing = false

Flag that enables uri processing



* Visibility: **protected**
* This property is **static**.


### $instance

    protected self $instance = null

Singleton instance to current application (plugin or theme)



* Visibility: **protected**
* This property is **static**.


Methods
-------


### __construct

    mixed Chayka\WP\Plugin::__construct(string $__file__, array $routes)

Plugin Constructor.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $__file__ **string** - &lt;p&gt;pass __FILE__&lt;/p&gt;
* $routes **array**



### registerNavMenus

    mixed Chayka\WP\Theme::registerNavMenus()

Register your nav menus here using $this->registerNavMenu();



* Visibility: **public**




### registerNavMenu

    mixed Chayka\WP\Theme::registerNavMenu($location, $description)

Alias of register_nav_menu



* Visibility: **public**


#### Arguments
* $location **mixed**
* $description **mixed**



### addSupport_Thumbnails

    mixed Chayka\WP\Theme::addSupport_Thumbnails(integer $width, integer $height, boolean $crop)

Enable thumbnails support



* Visibility: **public**


#### Arguments
* $width **integer**
* $height **integer**
* $crop **boolean**



### addSupport_Excerpt

    mixed Chayka\WP\Theme::addSupport_Excerpt(integer $length, string $more)

Setup excerpt behaviour



* Visibility: **public**


#### Arguments
* $length **integer**
* $more **string**



### excerptLength

    integer Chayka\WP\Theme::excerptLength()

This is a callback for 'excerpt_length' hook



* Visibility: **public**




### excerptMore

    string Chayka\WP\Theme::excerptMore()

This is a callback for 'excerpt_more' hook



* Visibility: **public**




### registerEditorStyle

    mixed Chayka\WP\Theme::registerEditorStyle(string|array $relativeResPath)

Add custom editor css styles



* Visibility: **public**


#### Arguments
* $relativeResPath **string|array**



### getInstance

    static Chayka\WP\Plugin::getInstance()

Get singleton instance



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### dirPath

    string Chayka\WP\Plugin::dirPath(string $__file__)

Returns file's dir path



* Visibility: **protected**
* This method is **static**.
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $__file__ **string** - &lt;p&gt;use __FILE__&lt;/p&gt;



### dirUrl

    string Chayka\WP\Plugin::dirUrl(string $__file__)

Returns file's dir url



* Visibility: **protected**
* This method is **static**.
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $__file__ **string** - &lt;p&gt;use __FILE__&lt;/p&gt;



### getView

    \Chayka\MVC\View Chayka\WP\Plugin::getView()

Get view with set up basepathh



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### getAppId

    string Chayka\WP\Plugin::getAppId()

Get App Id



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### getClassName

    string Chayka\WP\Plugin::getClassName()

Get class name



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### getNamespace

    string Chayka\WP\Plugin::getNamespace()

Get namespace



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### getCallbackMethod

    callable Chayka\WP\Plugin::getCallbackMethod(string $method)

Get callback method defined in this class



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $method **string**



### getBasePath

    string Chayka\WP\Plugin::getBasePath()

Get base path



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### getPath

    String Chayka\WP\Plugin::getPath(String $path)

Get abs path for relative path



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $path **String**



### getPathRes

    String Chayka\WP\Plugin::getPathRes($relativeResPath)

Get abs res for path relative to '/res'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $relativeResPath **mixed**



### getBaseUrl

    string Chayka\WP\Plugin::getBaseUrl()

Get base url



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### baseUrl

    mixed Chayka\WP\Plugin::baseUrl()

Output base url.

Can use in templates

* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### getUrl

    String Chayka\WP\Plugin::getUrl(String $path)

Get abs url for relative path



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $path **String**



### getUrlRes

    String Chayka\WP\Plugin::getUrlRes($relativeResPath)

Get abs url for path relative to '/res'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $relativeResPath **mixed**



### dbUpdate

    mixed Chayka\WP\Plugin::dbUpdate(\Chayka\WP\array(String) $versionHistory)

Perform update based on db structure version history

DB Version history

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $versionHistory **Chayka\WP\array(String)**



### init

    \Chayka\WP\Plugin Chayka\WP\Plugin::init()

This function instantiates Plugin's singleton and returns it



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### addSupport_UriProcessing

    mixed Chayka\WP\Plugin::addSupport_UriProcessing()

Enables plugin ability to process request uris, based on the registered routes



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerRoutes

    mixed Chayka\WP\Plugin::registerRoutes()

Routes are to be added here via $this->addRoute();



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### addRoute

    mixed Chayka\WP\Plugin::addRoute(string $label, string $urlPattern, array $defaults, array $paramPatterns)

Add route mapping that can be served using this application (plugin or theme)

Here are some samples of url pattern
:controller/?action/*
my_controller/some_action/:some_part/*
my/act/?some/*

prefix ':' - means obligatory param
prefix '?' - means optional param
trailing '*' - means that all the rest params (/param1/value1/param2/value2) should be captured

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $label **string**
* $urlPattern **string**
* $defaults **array**
* $paramPatterns **array**



### addRestRoute

    mixed Chayka\WP\Plugin::addRestRoute(string $modelSlug, string $restUrlPattern, array $restParamPatterns, string $modelClassName, string $controller, array $defaults)

Add set of route to rest controller



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $modelSlug **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $restUrlPattern **string** - &lt;p&gt;e.g &#039;/?id&#039;&lt;/p&gt;
* $restParamPatterns **array** - &lt;p&gt;e.g. [&#039;id&#039;=&gt;&#039;/^\d+$/&#039;]&lt;/p&gt;
* $modelClassName **string** - &lt;p&gt;e.g. &#039;\Chayka\WP\Models\PostModel&#039;&lt;/p&gt;
* $controller **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $defaults **array**



### addRestRoutes

    mixed Chayka\WP\Plugin::addRestRoutes(string $modelSlug, string $modelsSlug, string $restUrlPattern, array $restParamPatterns, string $modelClassName, string $controller, string $listAction, array $defaults)

Add set of routes to rest controller



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $modelSlug **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $modelsSlug **string** - &lt;p&gt;e.g. &#039;post-models&#039;&lt;/p&gt;
* $restUrlPattern **string** - &lt;p&gt;e.g &#039;/?id&#039;&lt;/p&gt;
* $restParamPatterns **array** - &lt;p&gt;e.g. [&#039;id&#039;=&gt;&#039;/^\d+$/&#039;]&lt;/p&gt;
* $modelClassName **string** - &lt;p&gt;e.g. &#039;\Chayka\WP\Models\PostModel&#039;&lt;/p&gt;
* $controller **string** - &lt;p&gt;e.g. &#039;post-model&#039;&lt;/p&gt;
* $listAction **string**
* $defaults **array**



### processRequest

    string Chayka\WP\Plugin::processRequest($requestUri)

Processes request uri and returns response



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $requestUri **mixed**



### renderRequest

    mixed Chayka\WP\Plugin::renderRequest($requestUri)

Processes request uri and outputs response



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $requestUri **mixed**



### registerCustomPostTypes

    mixed Chayka\WP\Plugin::registerCustomPostTypes()

Custom post type are to be added here



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerTaxonomies

    mixed Chayka\WP\Plugin::registerTaxonomies()

Custom Taxonomies are to be added here



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerSidebars

    mixed Chayka\WP\Plugin::registerSidebars()

Custom Sidebars are to be added here via $this->registerSidbar();



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerSidebar

    mixed Chayka\WP\Plugin::registerSidebar(string $name, string $id)

Register custom sidbar



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $name **string**
* $id **string**



### addSupport_PostProcessing

    mixed Chayka\WP\Plugin::addSupport_PostProcessing(integer $priority)

Enables post modofication processing.

You need to implement savePost(), deletePost() [and trashedPost()].

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $priority **integer**



### savePost

    mixed Chayka\WP\Plugin::savePost(integer $postId, \WP_Post $post)

This is a hook for save_post



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $postId **integer**
* $post **WP_Post**



### deletePost

    mixed Chayka\WP\Plugin::deletePost(integer $postId)

This is a hook for delete_post



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $postId **integer**



### trashedPost

    mixed Chayka\WP\Plugin::trashedPost(integer $postId)

This is a hook for trashed_post



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $postId **integer**



### addSupport_CustomPermalinks

    mixed Chayka\WP\Plugin::addSupport_CustomPermalinks()

Enables custom link formats.

You need to implement postPermalink(), termLink(), userLink() and commentPermalink() methods.

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### postPermalink

    string Chayka\WP\Plugin::postPermalink(string $permalink, \WP_Post $post, boolean $leavename)

This is a hook for post_link and post_type_link



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $permalink **string**
* $post **WP_Post**
* $leavename **boolean**



### termLink

    string Chayka\WP\Plugin::termLink(string $link, object $term, string $taxonomy)

This is a hook for term_link



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $link **string**
* $term **object**
* $taxonomy **string**



### userLink

    string Chayka\WP\Plugin::userLink(string $link, integer $userId, string $nicename)

This is a hook for author_link



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $link **string**
* $userId **integer**
* $nicename **string**



### commentPermalink

    string Chayka\WP\Plugin::commentPermalink(string $permalink, object $comment)

This is a hook for get_comment_link



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $permalink **string**
* $comment **object**



### isMediaMinimized

    boolean Chayka\WP\Plugin::isMediaMinimized()

Check if media minimization is enabled



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### setMediaMinimized

    mixed Chayka\WP\Plugin::setMediaMinimized(boolean $mediaMinimized)

Enable or disable media minimization



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $mediaMinimized **boolean**



### getResSrcDir

    string Chayka\WP\Plugin::getResSrcDir()

Get /res relative 'src' dir



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### setResSrcDir

    mixed Chayka\WP\Plugin::setResSrcDir(string $resSrcDir)

Set /res relative 'src' dir



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $resSrcDir **string**



### getResDistDir

    mixed Chayka\WP\Plugin::getResDistDir()

Get /res relative 'dist' dir



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### setResDistDir

    mixed Chayka\WP\Plugin::setResDistDir(mixed $resDistDir)

Set /res relative 'dist' dir



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $resDistDir **mixed**



### registerResources

    mixed Chayka\WP\Plugin::registerResources(boolean $minimize)

Register scripts and styles here using $this->registerScript() and $this->registerStyle()



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $minimize **boolean**



### setScriptLocation

    mixed Chayka\WP\Plugin::setScriptLocation($handle, boolean $inFooter)

Set script rendering location (head|footer)



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **mixed**
* $inFooter **boolean**



### enqueueStyle

    mixed Chayka\WP\Plugin::enqueueStyle(string $handle, string|boolean $relativeResPath, array $dependencies, boolean $version, string $media)

Alias to wp_enqueue_style, but the path is relative to '/res'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**
* $relativeResPath **string|boolean**
* $dependencies **array**
* $version **boolean**
* $media **string**



### registerStyle

    mixed Chayka\WP\Plugin::registerStyle(string $handle, string $relativeResPath, array $dependencies, boolean $version, string $media)

Alias to wp_register_style, but the path is relative to '/res'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**
* $relativeResPath **string**
* $dependencies **array**
* $version **boolean**
* $media **string**



### registerNgStyle

    mixed Chayka\WP\Plugin::registerNgStyle(string $handle, string $relativeResPath, array $dependencies, boolean $version, string $media)

Alias to wp_register_style, but the path is relative to '/res'.

Ensures 'angular' as dependency.

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**
* $relativeResPath **string**
* $dependencies **array**
* $version **boolean**
* $media **string**



### registerMinimizedStyle

    mixed Chayka\WP\Plugin::registerMinimizedStyle(string $minHandle, string $relativeResDistPath, array $handles, boolean $version, string $media)

Register minimized style file that contains all the min-cat styles defined by $handles.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $minHandle **string**
* $relativeResDistPath **string**
* $handles **array**
* $version **boolean**
* $media **string**



### unregisterStyle

    mixed Chayka\WP\Plugin::unregisterStyle($handle)

Alias to wp_deregister_style



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **mixed**



### enqueueScript

    mixed Chayka\WP\Plugin::enqueueScript(string $handle, string|boolean $relativeResPath, array $dependencies, boolean $version, boolean $inFooter)

Alias to wp_register_script, but the path is relative to '/res'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**
* $relativeResPath **string|boolean**
* $dependencies **array**
* $version **boolean**
* $inFooter **boolean**



### registerScript

    mixed Chayka\WP\Plugin::registerScript(string $handle, string $relativeResPath, array $dependencies, boolean $version, boolean $inFooter)

Alias to wp_register_script, but the path is relative to '/res'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**
* $relativeResPath **string**
* $dependencies **array**
* $version **boolean**
* $inFooter **boolean**



### registerNgScript

    mixed Chayka\WP\Plugin::registerNgScript($handle, $relativeResPath, array $dependencies, callable|null $enqueueCallback, boolean $version, boolean $inFooter)

Alias to AngularHelper::registerScript(), but the path is relative to '/res'
See AngularHelper::registerScript() for more details



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **mixed**
* $relativeResPath **mixed**
* $dependencies **array**
* $enqueueCallback **callable|null**
* $version **boolean**
* $inFooter **boolean**



### registerMinimizedScript

    mixed Chayka\WP\Plugin::registerMinimizedScript(string $minHandle, string $relativeResDistPath, array $handles, boolean $version, boolean $inFooter)

Register minimized script file that contains all the min-cat scripts defined by $handles.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $minHandle **string**
* $relativeResDistPath **string**
* $handles **array**
* $version **boolean**
* $inFooter **boolean**



### unregisterScript

    mixed Chayka\WP\Plugin::unregisterScript(string $handle)

Alias to wp_deregister_script



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**



### registerScriptNls

    mixed Chayka\WP\Plugin::registerScriptNls($handle, $relativeResPath, array $dependencies)

Not implemented yet and to be revised



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **mixed**
* $relativeResPath **mixed**
* $dependencies **array**



### enqueueScriptStyle

    mixed Chayka\WP\Plugin::enqueueScriptStyle(string $handle, boolean $scriptInFooter)

Alias to ResourceHelper::enqueueScriptStyle()



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $handle **string**
* $scriptInFooter **boolean**



### getBower

    array|boolean Chayka\WP\Plugin::getBower(boolean $minimize)

Read and parse bower config



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $minimize **boolean**



### registerBowerResources

    mixed Chayka\WP\Plugin::registerBowerResources(boolean $overrideWithNew)

This function discovers installed bower packages and registers them if they are not already registered
if true passed, newer versions will override older ones



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $overrideWithNew **boolean**



### registerBowerComponent

    mixed Chayka\WP\Plugin::registerBowerComponent(string $name, string $path, boolean $overrideWithNew)

Registers bower component
$path is relative to baseDir



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $name **string**
* $path **string**
* $overrideWithNew **boolean**



### getComposer

    array|boolean Chayka\WP\Plugin::getComposer()

Read and parse composer config



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerComposerPlugins

    mixed Chayka\WP\Plugin::registerComposerPlugins()

Go through vendor folder if it exists.

Find composer.json
Call "wp-init" callback.

'$ composer install' should be called before

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerComposerPlugin

    mixed Chayka\WP\Plugin::registerComposerPlugin(string $name, string $path)

Register composer Chayka plugin



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $name **string**
* $path **string**



### registerActions

    mixed Chayka\WP\Plugin::registerActions()

Register your action hooks here using $this->addAction();



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### addAction

    boolean|void Chayka\WP\Plugin::addAction($action, string|array $method, integer $priority, integer $numberOfArguments)

Alias to add_action, but if the $method is a string then
$this->getCallbackMethod($method) is used instead.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $action **mixed**
* $method **string|array**
* $priority **integer**
* $numberOfArguments **integer**



### removeAction

    boolean Chayka\WP\Plugin::removeAction($tag, $callback, integer $priority)

Alias to remove_action, but if the $method is a string and method exists then
$this->getCallbackMethod($callback) is used instead.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $tag **mixed**
* $callback **mixed**
* $priority **integer**



### registerFilters

    mixed Chayka\WP\Plugin::registerFilters()

Register your action hooks here using $this->addFilter();



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### addFilter

    boolean|void Chayka\WP\Plugin::addFilter($filter, $method, integer $priority, integer $numberOfArguments)

Alias to add_filter, but if the $method is a string then
$this->getCallbackMethod($method) is used instead.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $filter **mixed**
* $method **mixed**
* $priority **integer**
* $numberOfArguments **integer**



### removeFilter

    boolean Chayka\WP\Plugin::removeFilter($tag, $callback, integer $priority)

Alias to remove_filter, but if the $method is a string and method exists then
$this->getCallbackMethod($callback) is used instead.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $tag **mixed**
* $callback **mixed**
* $priority **integer**



### addSupport_ConsolePages

    mixed Chayka\WP\Plugin::addSupport_ConsolePages()

Enables support for console pages that will be typically rendered by AdminController.

You should implement registerConsolePages();

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerConsolePages

    mixed Chayka\WP\Plugin::registerConsolePages()

Override to add addConsolePage() calls



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### renderConsolePage

    mixed Chayka\WP\Plugin::renderConsolePage()

Callback method that will render console pages using AdminController



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### addConsolePage

    mixed Chayka\WP\Plugin::addConsolePage(string $title, string $capability, string $menuSlug, string $renderUri, string $relativeResIconUrl, integer $position)

Add Console Page. Much like add_menu_page,
but instead of callback you provide some controller uri (e.g. 'admin/some-action')
see https://developer.wordpress.org/resource/dashicons/#wordpress for icons



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $title **string**
* $capability **string**
* $menuSlug **string**
* $renderUri **string**
* $relativeResIconUrl **string**
* $position **integer**



### addConsoleSubPage

    mixed Chayka\WP\Plugin::addConsoleSubPage($parentSlug, $title, $capability, $menuSlug, string $renderUri)

Add Console Page. Much like add_submenu_page,
but instead of callback you provide some controller uri (e.g. 'admin/some-action')



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $parentSlug **mixed**
* $title **mixed**
* $capability **mixed**
* $menuSlug **mixed**
* $renderUri **string**



### addSupport_Metaboxes

    mixed Chayka\WP\Plugin::addSupport_Metaboxes()

Enable metaboxes rendering using MetaboxController.

You should implement registerMetaBoxes().

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerMetaBoxes

    mixed Chayka\WP\Plugin::registerMetaBoxes()

Override to add addMetaBox() calls;



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### renderMetaBox

    mixed Chayka\WP\Plugin::renderMetaBox(\WP_Post $post, string $box)

Callback for rendering metaboxes.



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $post **WP_Post**
* $box **string**



### updateMetaBoxes

    mixed Chayka\WP\Plugin::updateMetaBoxes(integer $postId, \WP_Post $post)

Callback for 'save_post' hook, updating metabox, should be revised (implement logic here).



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $postId **integer**
* $post **WP_Post**



### addMetaBox

    mixed Chayka\WP\Plugin::addMetaBox(string $id, string $title, string $renderUri, string $context, string $priority, string|array $screen)

Add Metabox



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $id **string**
* $title **string**
* $renderUri **string**
* $context **string** - &lt;p&gt;&#039;normal&#039;, &#039;advanced&#039;, or &#039;side&#039;&lt;/p&gt;
* $priority **string** - &lt;p&gt;&#039;high&#039;, &#039;core&#039;, &#039;default&#039; or &#039;low&#039;&lt;/p&gt;
* $screen **string|array** - &lt;p&gt;post type&lt;/p&gt;



### addMetaBoxes

    mixed Chayka\WP\Plugin::addMetaBoxes()

Callback method for 'add_meta_boxes' hook,
adding metaboxes when rendering post editor page



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### removeMetaBox

    mixed Chayka\WP\Plugin::removeMetaBox(string $id, string|array $pages, string $context)

Remove registered metabox.

Handy for blocking metaboxes from parent themes.

more info http://codex.wordpress.org/Function_Reference/remove_meta_box

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $id **string**
* $pages **string|array**
* $context **string** - &lt;p&gt;&#039;normal&#039;, &#039;advanced&#039;, or &#039;side&#039;.&lt;/p&gt;



### unregisterMetaBoxes

    mixed Chayka\WP\Plugin::unregisterMetaBoxes()

Callback method for 'add_meta_boxes' hook,
adding metaboxes when rendering post editor page



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### registerShortcodes

    mixed Chayka\WP\Plugin::registerShortcodes()

Override to add addShortcodes() calls;



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### addShortcode

    mixed Chayka\WP\Plugin::addShortcode(string $shortcode, string $uri)

Alias to add_shortcode(), but instead of callback ShortcodeController renderer is used



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $shortcode **string**
* $uri **string**



### renderShortcode

    string Chayka\WP\Plugin::renderShortcode($atts, $content, $shortcode)

Callback for shortcode rendering



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $atts **mixed**
* $content **mixed**
* $shortcode **mixed**



### showAdminBar

    mixed Chayka\WP\Plugin::showAdminBar(boolean|string $show)

Setup whether black top admin bar should be shown outside of admin area:
 false - always hide
 true - always show if the user is logged in
 'admin' - show only if logged in user is administrator

TODO: fix it,

* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $show **boolean|string**



### hideAdminBar

    mixed Chayka\WP\Plugin::hideAdminBar()

Hide black top admin bar outside of admin area



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### showAdminBarToAdminOnly

    mixed Chayka\WP\Plugin::showAdminBarToAdminOnly()

Show black top admin bar outside of admin area to admin only



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)




### isAdminBarShown

    boolean Chayka\WP\Plugin::isAdminBarShown(boolean $show)

Hook that is called upon filter 'show_admin_bar'



* Visibility: **public**
* This method is defined by [Chayka\WP\Plugin](Chayka-WP-Plugin.md)


#### Arguments
* $show **boolean**


