Chayka\WP\MVC\Controller
===============

Class Controller Extends Chayka\MVC\Controller.

It implements WP specific handy methods.


* Class name: Controller
* Namespace: Chayka\WP\MVC
* Parent class: Chayka\MVC\Controller





Properties
----------


### $appUrl

    protected string $appUrl

Current application (plugin or theme) base url



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\MVC\Controller::__construct(\Chayka\MVC\Application $application)

Controller constructor



* Visibility: **public**


#### Arguments
* $application **Chayka\MVC\Application**



### setPost

    mixed Chayka\WP\MVC\Controller::setPost($post)

This function should be used to set post data,
that will be use later in Chayka\WP\Query and Chayka\WP|Helpers\HtmlHelper.

Long story short, data from the post provide will be used for html title, keywords, description.

* Visibility: **public**


#### Arguments
* $post **mixed**



### setNotFound404

    boolean Chayka\WP\MVC\Controller::setNotFound404(boolean $notFound)

A helper to start 'not found' scenario.

Just return it from controller action at the point when post is not found.

* Visibility: **public**


#### Arguments
* $notFound **boolean**



### setTitle

    mixed Chayka\WP\MVC\Controller::setTitle($title)

Set html title.

Use Chayka\WP|Helpers\HtmlHelper::getHeadTitle() to fetch it in your template.

* Visibility: **public**


#### Arguments
* $title **mixed**



### setDescription

    mixed Chayka\WP\MVC\Controller::setDescription($description)

Set html description.

Use Chayka\WP|Helpers\HtmlHelper::getMetaDescription() to fetch it in your template.

* Visibility: **public**


#### Arguments
* $description **mixed**



### setKeywords

    mixed Chayka\WP\MVC\Controller::setKeywords($keywords)

Set html keywords.

Use Chayka\WP|Helpers\HtmlHelper::getMetaDescription() to fetch it in your template.

* Visibility: **public**


#### Arguments
* $keywords **mixed**



### enqueueScript

    mixed Chayka\WP\MVC\Controller::enqueueScript($handle, string|boolean $resRelativeSrc, array $dependencies, string|boolean $ver, boolean $in_footer)

Enqueue script. Utilizes wp_enqueue_script().

However if detects registered minimized and concatenated version enqueue it instead.

* Visibility: **public**


#### Arguments
* $handle **mixed**
* $resRelativeSrc **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $in_footer **boolean**



### enqueueNgScript

    mixed Chayka\WP\MVC\Controller::enqueueNgScript($handle, boolean $resRelativeSrc, array $dependencies, callable|null $enqueueCallback, boolean $ver, boolean $in_footer)

Enqueue angular script. Utilizes AngularHelper::enqueueScript().

See AngularHelper::enqueueScript() for more details.

* Visibility: **public**


#### Arguments
* $handle **mixed**
* $resRelativeSrc **boolean**
* $dependencies **array**
* $enqueueCallback **callable|null**
* $ver **boolean**
* $in_footer **boolean**



### enqueueStyle

    mixed Chayka\WP\MVC\Controller::enqueueStyle($handle, string|boolean $resRelativeSrc, array $dependencies, string|boolean $ver, boolean $in_footer)

Enqueue style. Utilizes wp_enqueue_style().

However if detects registered minimized and concatenated version enqueue it instead.

* Visibility: **public**


#### Arguments
* $handle **mixed**
* $resRelativeSrc **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $in_footer **boolean**



### enqueueNgStyle

    mixed Chayka\WP\MVC\Controller::enqueueNgStyle($handle, string|boolean $resRelativeSrc, array $dependencies, string|boolean $ver, boolean $in_footer)

Enqueue style. Utilizes wp_enqueue_style().

However if detects registered minimized and concatenated version enqueue it instead.
Ensures 'angular' as dependency

* Visibility: **public**


#### Arguments
* $handle **mixed**
* $resRelativeSrc **string|boolean**
* $dependencies **array**
* $ver **string|boolean**
* $in_footer **boolean**



### enqueueScriptStyle

    mixed Chayka\WP\MVC\Controller::enqueueScriptStyle(string $handle)

Enqueue both script and style with the same $handle.

Uses minimized versions if detects.

* Visibility: **public**


#### Arguments
* $handle **string**



### enqueueNgScriptStyle

    mixed Chayka\WP\MVC\Controller::enqueueNgScriptStyle($handle)

Enqueue both script and style with the same $handle.

Uses minimized versions if detects.

Should be used to enqueue angular scripts to bootstrap them correctly

* Visibility: **public**


#### Arguments
* $handle **mixed**



### loadActionPost

    \Chayka\WP\Models\PostModel|null Chayka\WP\MVC\Controller::loadActionPost($id, $slug, boolean $incReviews)

This helper load post for the action.

It checks if $id and $slug are valid, redirects to valid url otherwise.
If $id or $slug is omitted then loads by what is given and no consistency check is performed.
Assigns post for major $wp_query as current (for all those headers to work right).
And optionally increases post reviews count.

* Visibility: **public**


#### Arguments
* $id **mixed**
* $slug **mixed**
* $incReviews **boolean**



### setupPaginationByWpQuery

    \Chayka\WP\MVC\Controller Chayka\WP\MVC\Controller::setupPaginationByWpQuery(\WP_Query $wpQuery)

Setup pagination by WP_Query



* Visibility: **public**


#### Arguments
* $wpQuery **WP_Query**



### setWpTemplate

    mixed Chayka\WP\MVC\Controller::setWpTemplate($template)

Set WP page template



* Visibility: **public**


#### Arguments
* $template **mixed**


