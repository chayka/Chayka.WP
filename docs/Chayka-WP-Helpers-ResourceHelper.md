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

One more thing - this helper enables usage of minimized & concatenated (by GruntJS) scripts & styles.
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


### $minimizedStyles

    protected array $minimizedStyles = array()

Mapping of resource handles to the concatenated styles



* Visibility: **protected**
* This property is **static**.


### $minimizedScripts

    protected array $minimizedScripts = array()

Mapping of resource handles to the concatenated scripts



* Visibility: **protected**
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



### registerScript

    mixed Chayka\WP\Helpers\ResourceHelper::registerScript(string $handle, string $src, array $ngDependencies, boolean $version, boolean $inFooter)

Alias to wp_register_script but checks if dependencies can be found inside minimized files



* Visibility: **public**
* This method is **static**.


#### Arguments
* $handle **string**
* $src **string**
* $ngDependencies **array**
* $version **boolean**
* $inFooter **boolean**



### registerMinimizedScript

    mixed Chayka\WP\Helpers\ResourceHelper::registerMinimizedScript(string $minHandle, string $src, array $handles, boolean $version, boolean $inFooter)

Register script that contains minimized and concatenated scripts



* Visibility: **public**
* This method is **static**.


#### Arguments
* $minHandle **string**
* $src **string**
* $handles **array**
* $version **boolean**
* $inFooter **boolean**



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

However if detects registered minimized and concatenated version enqueue it instead.

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



### registerMinimizedStyle

    mixed Chayka\WP\Helpers\ResourceHelper::registerMinimizedStyle(string $minHandle, string $src, array $handles, boolean $version, string $media)

Register script that contains minimized and concatenated styles



* Visibility: **public**
* This method is **static**.


#### Arguments
* $minHandle **string**
* $src **string**
* $handles **array**
* $version **boolean**
* $media **string**



### enqueueStyle

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueStyle($handle, string|boolean $src, array $dependencies, string|boolean $ver, string $media)

Enqueue style. Utilizes wp_enqueue_style().

However if detects registered minimized and concatenated version enqueue it instead.

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


