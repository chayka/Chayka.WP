Chayka\WP\Helpers\ResourceHelper
===============

Class ResourceHelper is a comprehensive solution to manage your styles and scripts.

Basically it is a wrapper for all those
- wp_register_script
- wp_deregister_script
- wp_enqueue_script
- wp_register_style
- wp_enqueue_style
- wp_deregister_style

However this helper loads them carefully at the appropriate hooks.

One more thing - this helper enables usage of minimized & combined (by GruntJS|GulpJS) scripts & styles.
Do it like that:
     ResourceHelper::registerScript('jquery', 'js/jquery.js', ...);
     ResourceHelper::registerScript('angular', 'js/angular.js',...);

     ResourceHelper::registerMinimizedScript('jquery-angular', 'js/jquery-angular.min.js', ['jquery', 'angular']);

then at some point call:

     ResourceHelper::enqueueScript('jquery');

it will load 'js/jquery-angular.min.js'

the next call:

     ResourceHelper::enqueueScript('angular');

won't load a thing because it's already loaded. Amazing!


* Class name: ResourceHelper
* Namespace: Chayka\WP\Helpers





Properties
----------


### $isMediaMinimized

    protected boolean $isMediaMinimized = false

This variable stores flag whether helper should look for minimized resources
if available.



* Visibility: **protected**
* This property is **static**.


### $combinedStyles

    protected array $combinedStyles = array()

Mapping of resource handles to the combined styles



* Visibility: **protected**
* This property is **static**.


### $combinedScripts

    protected array $combinedScripts = array()

Mapping of resource handles to the combined scripts



* Visibility: **protected**
* This property is **static**.


### $applicationResourceFolderUrls

    protected array $applicationResourceFolderUrls = array()

Mapping of plugins and themes resource folders



* Visibility: **protected**
* This property is **static**.


### $callbackCounter

    public integer $callbackCounter

This counter is responsible for the right order of the callbacks called on hooks



* Visibility: **public**
* This property is **static**.


Methods
-------


### isMediaMinimized

    boolean Chayka\WP\Helpers\ResourceHelper::isMediaMinimized()

Check if we are working in the minimized media mode



* Visibility: **public**
* This method is **static**.




### setMediaMinimized

    mixed Chayka\WP\Helpers\ResourceHelper::setMediaMinimized(boolean $isMediaMinimized)

Set minimized media mode



* Visibility: **public**
* This method is **static**.


#### Arguments
* $isMediaMinimized **boolean**



### addEnqueueScriptsCallback

    mixed Chayka\WP\Helpers\ResourceHelper::addEnqueueScriptsCallback(\Closure $callback)

Add callback to enqueue or register script at an appropriate moment.

Scripts and styles should not be registered or enqueued until the
<code>wp_enqueue_scripts</code>, <code>admin_enqueue_scripts</code>, or
<code>login_enqueue_scripts</code> hooks.

* Visibility: **protected**
* This method is **static**.


#### Arguments
* $callback **Closure**



### unregisterScript

    mixed Chayka\WP\Helpers\ResourceHelper::unregisterScript($handle)

Alias to wp_deregister_script



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**



### resolveCombinedDependencies

    \_WP_Dependency Chayka\WP\Helpers\ResourceHelper::resolveCombinedDependencies(string $handle, \WP_Dependencies $wpMedia, array $combined)

Replace dependencies with combined handles
Heads up: This function should be called only if self::isMediaMinimized.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **string**
* $wpMedia **WP_Dependencies**
* $combined **array**



### registerScript

    mixed Chayka\WP\Helpers\ResourceHelper::registerScript(string $handle, string $src, array $dependencies, boolean $version, boolean $inFooter)

Alias to wp_register_script but checks if dependencies can be found inside minimized files



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **string**
* $src **string**
* $dependencies **array**
* $version **boolean**
* $inFooter **boolean**



### registerCombinedScript

    mixed Chayka\WP\Helpers\ResourceHelper::registerCombinedScript(string $minHandle, string $src, array $handles, boolean $version, boolean $inFooter)

Register script that contains minimized and combined scripts



* Visibility: **public**
* This method is **static**.


#### Arguments
* $minHandle **string**
* $src **string**
* $handles **array**
* $version **boolean**
* $inFooter **boolean**



### updateScript

    \_WP_Dependency|null Chayka\WP\Helpers\ResourceHelper::updateScript($handle, $src, null|array $dependencies, string|boolean $version)

A little helper to update already registered script.

For instance, you may want to upgrade jQuery

* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**
* $src **mixed**
* $dependencies **null|array**
* $version **string|boolean**



### setScriptLocation

    mixed Chayka\WP\Helpers\ResourceHelper::setScriptLocation($handle, boolean $inFooter)

This function can change default script rendering location: head or footer



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**
* $inFooter **boolean**



### enqueueScript

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueScript($handle, string|boolean $src, array $dependencies, string|boolean $ver, boolean $inFooter)

Enqueue script. Utilizes wp_enqueue_script().

However if detects registered minimized and combined version enqueue it instead.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**
* $src **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $inFooter **boolean**



### addEnqueueStylesCallback

    mixed Chayka\WP\Helpers\ResourceHelper::addEnqueueStylesCallback(\Closure $callback)

Add callback to enqueue or register style at an appropriate moment.

Scripts and styles should not be registered or enqueued until the
<code>wp_enqueue_styles</code>, <code>admin_enqueue_styles</code>, or
<code>login_enqueue_styles</code> hooks.

* Visibility: **protected**
* This method is **static**.


#### Arguments
* $callback **Closure**



### unregisterStyle

    mixed Chayka\WP\Helpers\ResourceHelper::unregisterStyle($handle)

Alias to wp_deregister_style



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**



### registerStyle

    mixed Chayka\WP\Helpers\ResourceHelper::registerStyle(string $handle, string $src, array $dependencies, boolean $version, string $media)

Alias to wp_register_style but checks if dependencies can be found inside minimized files



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **string**
* $src **string**
* $dependencies **array**
* $version **boolean**
* $media **string**



### registerCombinedStyle

    mixed Chayka\WP\Helpers\ResourceHelper::registerCombinedStyle(string $minHandle, string $src, array $handles, boolean $version, string $media)

Register script that contains minimized and combined styles



* Visibility: **public**
* This method is **static**.


#### Arguments
* $minHandle **string**
* $src **string**
* $handles **array**
* $version **boolean**
* $media **string**



### updateStyle

    \_WP_Dependency|null Chayka\WP\Helpers\ResourceHelper::updateStyle($handle, $src, null|array $dependencies, string|boolean $version)

A little helper to update already registered style.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**
* $src **mixed**
* $dependencies **null|array**
* $version **string|boolean**



### enqueueStyle

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueStyle($handle, string|boolean $src, array $dependencies, string|boolean $ver, string $media)

Enqueue style. Utilizes wp_enqueue_style().

However if detects registered minimized and combined version enqueue it instead.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **mixed**
* $src **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $media **string**



### enqueueScriptStyle

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueScriptStyle(string $handle, boolean $scriptInFooter)

Enqueue both script and style with the same $handle.

Uses minimized versions if detects.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **string**
* $scriptInFooter **boolean**



### setApplicationResourceFolderUrl

    mixed Chayka\WP\Helpers\ResourceHelper::setApplicationResourceFolderUrl($appId, $url)

Store application resource folder url for future use



* Visibility: **public**
* This method is **static**.


#### Arguments
* $appId **mixed**
* $url **mixed**



### getApplicationResourceFolderUrl

    string Chayka\WP\Helpers\ResourceHelper::getApplicationResourceFolderUrl($appId)

Get application resource folder url by application id



* Visibility: **public**
* This method is **static**.


#### Arguments
* $appId **mixed**



### getApplicationResourceFolderUrls

    array Chayka\WP\Helpers\ResourceHelper::getApplicationResourceFolderUrls()

Get application resource folder urls mapping by application id



* Visibility: **public**
* This method is **static**.



