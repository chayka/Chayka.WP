Chayka\WP\Helpers\HtmlHelper
===============

Class HtmlHelper is a static container for HTML &gt; HEAD &gt; META values.

Also contains a set of handy methods for outputting html attrs
to hide|show|disable|enable|check|uncheck elements based on provided condition.

This class differs from the base one with the setPost($post) method mostly
that allows to set all the meta at once.


* Class name: HtmlHelper
* Namespace: Chayka\WP\Helpers
* Parent class: Chayka\Helpers\HtmlHelper







Methods
-------


### setPost

    mixed Chayka\WP\Helpers\HtmlHelper::setPost(integer|object|\Chayka\WP\Models\PostModel $post)

Set all the html page title and meta data, based on post information



* Visibility: **public**
* This method is **static**.


#### Arguments
* $post **integer|object|[integer](Chayka-WP-Models-PostModel.md)**



### setSidebarId

    mixed Chayka\WP\Helpers\HtmlHelper::setSidebarId($id)

Set sidebar id for the page responded



* Visibility: **public**
* This method is **static**.


#### Arguments
* $id **mixed**



### getSidebarId

    mixed|string Chayka\WP\Helpers\HtmlHelper::getSidebarId()

Set sidebar id for the page responded



* Visibility: **public**
* This method is **static**.



