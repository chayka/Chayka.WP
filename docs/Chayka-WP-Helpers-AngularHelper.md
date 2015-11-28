Chayka\WP\Helpers\AngularHelper
===============

Class AngularHelper extends ResourceHelper.

The main idea of this helper is that all angular scripts should be registered and enqueued
using this helper.

This way one can get the list of modules enqueued using AngularHelper::getQueue().
And that allows us to angular.bootstrap() those modules upon html page.
In fact that is done in Chayka.Core.wpp plugin automatically.

For this feature to work consider storing each angular module in a separate js file
and register it under the same handle.

e.g. angular.module('chayka-modals') goes to AngularHelper::registerScript('chayka-modals', ...)


* Class name: AngularHelper
* Namespace: Chayka\WP\Helpers
* Parent class: [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)





Properties
----------


### $registered

    public \Chayka\WP\Helpers\[string] $registered = array()

Array of registered angular modules (scripts).



* Visibility: **public**
* This property is **static**.


### $callbacks

    public \Chayka\WP\Helpers\[callback] $callbacks = array()

Hash map of callbacks that are triggered when angular module is enqueued



* Visibility: **public**
* This property is **static**.


### $queue

    public \Chayka\WP\Helpers\[string] $queue = array()

Array of enqueued angular modules



* Visibility: **public**
* This property is **static**.


### $ngDependencies

    public array<mixed,> $ngDependencies = array()

Hash map of angular module dependencies



* Visibility: **public**
* This property is **static**.


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


### $applicationResourceFolderUrls

    protected array $applicationResourceFolderUrls = array()

Mapping of plugins and themes resource folders



* Visibility: **protected**
* This property is **static**.


Methods
-------


### registerScript

    mixed Chayka\WP\Helpers\ResourceHelper::registerScript(string $handle, string $src, array $dependencies, boolean $version, boolean $inFooter)

Alias to wp_register_script but checks if dependencies can be found inside minimized files



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **string**
* $src **string**
* $dependencies **array**
* $version **boolean**
* $inFooter **boolean**



### enqueueScript

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueScript($handle, string|boolean $src, array $dependencies, string|boolean $ver, boolean $inFooter)

Enqueue script. Utilizes wp_enqueue_script().

However if detects registered minimized and concatenated version enqueue it instead.

* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **mixed**
* $src **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $inFooter **boolean**



### registerStyle

    mixed Chayka\WP\Helpers\ResourceHelper::registerStyle(string $handle, string $src, array $dependencies, boolean $version, string $media)

Alias to wp_register_style but checks if dependencies can be found inside minimized files



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **string**
* $src **string**
* $dependencies **array**
* $version **boolean**
* $media **string**



### enqueueStyle

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueStyle($handle, string|boolean $src, array $dependencies, string|boolean $ver, string $media)

Enqueue style. Utilizes wp_enqueue_style().

However if detects registered minimized and concatenated version enqueue it instead.

* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **mixed**
* $src **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $media **string**



### getQueue

    array Chayka\WP\Helpers\AngularHelper::getQueue()

Get the list of angular modules that should be bootstrapped



* Visibility: **public**
* This method is **static**.




### isMediaMinimized

    boolean Chayka\WP\Helpers\ResourceHelper::isMediaMinimized()

Check if we are working in the minimized media mode



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)




### setMediaMinimized

    mixed Chayka\WP\Helpers\ResourceHelper::setMediaMinimized(boolean $isMediaMinimized)

Set minimized media mode



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


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
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $callback **Closure**



### unregisterScript

    mixed Chayka\WP\Helpers\ResourceHelper::unregisterScript($handle)

Alias to wp_deregister_script



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **mixed**



### registerMinimizedScript

    mixed Chayka\WP\Helpers\ResourceHelper::registerMinimizedScript(string $minHandle, string $src, array $handles, boolean $version, boolean $inFooter)

Register script that contains minimized and concatenated scripts



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


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
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


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
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **mixed**
* $inFooter **boolean**



### addEnqueueStylesCallback

    mixed Chayka\WP\Helpers\ResourceHelper::addEnqueueStylesCallback(\Closure $callback)

Add callback to enqueue or register style at an appropriate moment.

Scripts and styles should not be registered or enqueued until the
<code>wp_enqueue_styles</code>, <code>admin_enqueue_styles</code>, or
<code>login_enqueue_styles</code> hooks.

* Visibility: **protected**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $callback **Closure**



### unregisterStyle

    mixed Chayka\WP\Helpers\ResourceHelper::unregisterStyle($handle)

Alias to wp_deregister_style



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **mixed**



### registerMinimizedStyle

    mixed Chayka\WP\Helpers\ResourceHelper::registerMinimizedStyle(string $minHandle, string $src, array $handles, boolean $version, string $media)

Register script that contains minimized and concatenated styles



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


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
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **mixed**
* $src **mixed**
* $dependencies **null|array**
* $version **string|boolean**



### enqueueScriptStyle

    mixed Chayka\WP\Helpers\ResourceHelper::enqueueScriptStyle(string $handle, boolean $scriptInFooter)

Enqueue both script and style with the same $handle.

Uses minimized versions if detects.

* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $handle **string**
* $scriptInFooter **boolean**



### setApplicationResourceFolderUrl

    mixed Chayka\WP\Helpers\ResourceHelper::setApplicationResourceFolderUrl($appId, $url)

Store application resource folder url for future use



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $appId **mixed**
* $url **mixed**



### getApplicationResourceFolderUrl

    string Chayka\WP\Helpers\ResourceHelper::getApplicationResourceFolderUrl($appId)

Get application resource folder url by application id



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)


#### Arguments
* $appId **mixed**



### getApplicationResourceFolderUrls

    array Chayka\WP\Helpers\ResourceHelper::getApplicationResourceFolderUrls()

Get application resource folder urls mapping by application id



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\ResourceHelper](Chayka-WP-Helpers-ResourceHelper.md)



