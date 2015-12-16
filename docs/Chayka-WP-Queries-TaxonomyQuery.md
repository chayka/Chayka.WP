Chayka\WP\Queries\TaxonomyQuery
===============

Class TaxonomyQuery is a helper that allows to build $arguments array
for get_taxonomies()
For more details see https://codex.wordpress.org/Function_Reference/get_taxonomies




* Class name: TaxonomyQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $vars

    protected array $vars = array()

An array that holds query params that will be passed to get_taxonomies()



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\TaxonomyQuery::__construct()

TaxonomyQuery constructor



* Visibility: **public**




### getVars

    array Chayka\WP\Queries\TaxonomyQuery::getVars()

Get all vars



* Visibility: **public**




### setVars

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::setVars(array $vars)

Add vars to the set



* Visibility: **public**


#### Arguments
* $vars **array**



### setVar

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TaxonomyQuery::setVar(string $key, string $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**



### query

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::query()

Create instance of query object



* Visibility: **public**
* This method is **static**.




### name

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::name(string $name)

Select by name



* Visibility: **public**


#### Arguments
* $name **string**



### postType

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::postType(string|\Chayka\WP\Queries\array(string) $postTypes)

Select by post types



* Visibility: **public**


#### Arguments
* $postTypes **string|Chayka\WP\Queries\array(string)**



### label

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::label(string $label)

Select by label



* Visibility: **public**


#### Arguments
* $label **string**



### labelSingular

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::labelSingular(string $label)

Select by label



* Visibility: **public**


#### Arguments
* $label **string**



### showUI

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::showUI(boolean $flag)

Select by show_ui flag



* Visibility: **public**


#### Arguments
* $flag **boolean**



### showTagCloud

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::showTagCloud(boolean $flag)

Select by show_tag_cloud flag



* Visibility: **public**


#### Arguments
* $flag **boolean**



### isPublic

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::isPublic(boolean $flag)

Select by public flag



* Visibility: **public**


#### Arguments
* $flag **boolean**



### isBuiltIn

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::isBuiltIn(boolean $flag)

Select by _builtin flag



* Visibility: **public**


#### Arguments
* $flag **boolean**



### updateCountCallback

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::updateCountCallback(mixed $callback)

Select by update_count_callback



* Visibility: **public**


#### Arguments
* $callback **mixed**



### rewrite

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::rewrite(mixed $rewrite)

Select by rewrite



* Visibility: **public**


#### Arguments
* $rewrite **mixed**



### rewriteArgs

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::rewriteArgs(string $slug, boolean $withFront, boolean $hierarchical, integer $epMask)

Select by rewrite args



* Visibility: **public**


#### Arguments
* $slug **string** - &lt;p&gt;Used as pretty permalink text (i.e. /tag/) - defaults to $taxonomy (taxonomy&#039;s name slug)&lt;/p&gt;
* $withFront **boolean** - &lt;p&gt;allowing permalinks to be prepended with front base - defaults to true&lt;/p&gt;
* $hierarchical **boolean** - &lt;p&gt;true or false allow hierarchical urls (implemented in Version 3.1) - defaults to false&lt;/p&gt;
* $epMask **integer** - &lt;p&gt;Assign an endpoint mask for this taxonomy - defaults to EP_NONE. For more info see this Make WordPress Plugins summary of endpoints.&lt;/p&gt;



### queryVar

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::queryVar(mixed $queryVar)

Select by query_var



* Visibility: **public**


#### Arguments
* $queryVar **mixed**



### manageCap

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::manageCap(mixed $cap)

Select by manage_cap



* Visibility: **public**


#### Arguments
* $cap **mixed**



### editCap

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::editCap(mixed $cap)

Select by edit_cap



* Visibility: **public**


#### Arguments
* $cap **mixed**



### deleteCap

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::deleteCap(mixed $cap)

Select by delete_cap



* Visibility: **public**


#### Arguments
* $cap **mixed**



### assignCap

    \Chayka\WP\Queries\TaxonomyQuery Chayka\WP\Queries\TaxonomyQuery::assignCap(mixed $cap)

Select by assign_cap



* Visibility: **public**


#### Arguments
* $cap **mixed**



### select

    array Chayka\WP\Queries\TaxonomyQuery::select()

Get taxonomies under query



* Visibility: **public**



