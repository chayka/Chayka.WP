Chayka\WP\Queries\TermQuery
===============

Class TermQuery is a helper that allows to build $arguments array
for get_terms()
For more details see https://codex.wordpress.org/Function_Reference/get_terms

Note that this class is used to query terms not in the context of some post,
but for overall site statistics.
E.g. for building a tag cloud, or showing most used tags.

To get post terms, use PostTermQuery


* Class name: TermQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $vars

    protected array $vars = array()

Array that holds arguments for get_terms()



* Visibility: **protected**


### $taxonomies

    protected array $taxonomies = null

List of taxonomies under which terms should be acquired



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\TermQuery::__construct(string|\Chayka\WP\Queries\array(string) $taxonomies)

The query constructor allows to specify taxonomies for the terms to retrieve



* Visibility: **public**


#### Arguments
* $taxonomies **string|Chayka\WP\Queries\array(string)**



### getVars

    array Chayka\WP\Queries\TermQuery::getVars()

Get all vars



* Visibility: **public**




### setVars

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::setVars(array $vars)

Add vars to the set



* Visibility: **public**


#### Arguments
* $vars **array**



### setVar

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::setVar(string $key, mixed $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **mixed**



### query

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::query(string|\Chayka\WP\Queries\array(string) $taxonomies)

Create instance of query object



* Visibility: **public**
* This method is **static**.


#### Arguments
* $taxonomies **string|Chayka\WP\Queries\array(string)**



### select

    \Chayka\WP\Queries\array(TermModel) Chayka\WP\Queries\TermQuery::select(string|\Chayka\WP\Queries\array(string) $taxonomies)

Select all matching terms



* Visibility: **public**


#### Arguments
* $taxonomies **string|Chayka\WP\Queries\array(string)**



### selectOne

    \Chayka\WP\Models\TermModel Chayka\WP\Queries\TermQuery::selectOne(string|\Chayka\WP\Queries\array(string) $taxonomy)

Select first matching term



* Visibility: **public**


#### Arguments
* $taxonomy **string|Chayka\WP\Queries\array(string)**



### order

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::order(string $order)

Designates the ascending or descending order of the 'orderby' parameter.

Defaults to 'ASC'

* Visibility: **public**


#### Arguments
* $order **string**



### order_ASC

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::order_ASC()

Designates the ascending order of the 'orderby' parameter.



* Visibility: **public**




### order_DESC

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::order_DESC()

Designates the descending order of the 'orderby' parameter.



* Visibility: **public**




### orderBy

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy(string $orderBy)

Sort retrieved terms by parameter.



* Visibility: **public**


#### Arguments
* $orderBy **string**



### orderBy_ID

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy_ID()

Sort retrieved terms by id.



* Visibility: **public**




### orderBy_Count

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy_Count()

Sort retrieved terms by the number of associated posts.



* Visibility: **public**




### orderBy_Name

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy_Name()

Sort retrieved terms by name (not the slug).



* Visibility: **public**




### orderBy_Slug

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy_Slug()

Sort retrieved terms by slug.



* Visibility: **public**




### orderBy_TermGroup

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy_TermGroup()

Sort retrieved terms by parameter.

Codex remark: Not fully implemented (avoid using)

* Visibility: **public**




### orderBy_None

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::orderBy_None()

Do not sort retrieved terms.



* Visibility: **public**




### hideEmpty

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::hideEmpty(boolean $hide)

Whether to return empty $terms



* Visibility: **public**


#### Arguments
* $hide **boolean**



### showEmpty

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::showEmpty(boolean $show)

Whether to return empty $terms



* Visibility: **public**


#### Arguments
* $show **boolean**



### excludeIds

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::excludeIds(integer|string|array $termIds)

An array of term ids to exclude. Also accepts a string of comma-separated ids.



* Visibility: **public**


#### Arguments
* $termIds **integer|string|array**



### excludeTreeIds

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::excludeTreeIds(integer|string|array $parentTermIds)

An array of parent term ids to exclude



* Visibility: **public**


#### Arguments
* $parentTermIds **integer|string|array**



### includeIds

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::includeIds(integer|string|array $termIds)

An array of term ids to include. Empty returns all.



* Visibility: **public**


#### Arguments
* $termIds **integer|string|array**



### number

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::number(integer $number)

The maximum number of terms to return. Default is to return them all.



* Visibility: **public**


#### Arguments
* $number **integer**



### fields

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::fields(string|\Chayka\WP\Queries\array(string) $fields)

Set return values.



* Visibility: **public**


#### Arguments
* $fields **string|Chayka\WP\Queries\array(string)**



### fields_All

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::fields_All()

all - returns an array of term objects - Default



* Visibility: **public**




### fields_Ids

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::fields_Ids()

ids - returns an array of integers



* Visibility: **public**




### fields_Names

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::fields_Names()

names - returns an array of strings



* Visibility: **public**




### fields_Count

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::fields_Count()

count - (3.2+) returns the number of terms found



* Visibility: **public**




### fields_ID_ParentId

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::fields_ID_ParentId()

id=>parent - returns an associative array where
the key is the term id and
the value is the parent term id if present or 0



* Visibility: **public**




### slug

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::slug(string $slug)

Returns terms whose "slug" matches this value. Default is empty string.



* Visibility: **public**


#### Arguments
* $slug **string**



### parentId

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::parentId(integer $parentTermId)

Get direct children of this term (only terms whose explicit parent is this value).

If 0 is passed, only top-level terms are returned. Default is an empty string.

* Visibility: **public**


#### Arguments
* $parentTermId **integer**



### hierarchical

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::hierarchical(boolean $hierarchical)

Whether to include terms that have non-empty descendants
(even if 'hide_empty' is set to true).



* Visibility: **public**


#### Arguments
* $hierarchical **boolean**



### hierarchical_Yes

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::hierarchical_Yes()

Include terms that have non-empty descendants
(even if 'hide_empty' is set to true).



* Visibility: **public**




### hierarchical_No

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::hierarchical_No()

Do not to include terms that have non-empty descendants
(if 'hide_empty' is set to true).



* Visibility: **public**




### childOf

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::childOf(integer $parentTermId)

Get all descendants of this term. Default is 0.



* Visibility: **public**


#### Arguments
* $parentTermId **integer**



### get

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::get(string $value)

Default is nothing . Allow for overwriting 'hide_empty' and 'child_of',
which can be done by setting the value to 'all'.



* Visibility: **public**


#### Arguments
* $value **string**



### nameLike

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::nameLike(string $name)

The term name you wish to match. It does a LIKE 'term_name%' query.

This matches terms that begin with the

* Visibility: **public**


#### Arguments
* $name **string**



### padCounts

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::padCounts(boolean $count)

If true, count all of the children along with the $terms.



* Visibility: **public**


#### Arguments
* $count **boolean**



### offset

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::offset(integer $offset)

The number by which to offset the terms query.



* Visibility: **public**


#### Arguments
* $offset **integer**



### search

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::search(string $name)

The term name you wish to match. It does a LIKE '%term_name%' query.

This matches terms that contain the 'search'

* Visibility: **public**


#### Arguments
* $name **string**



### cacheDomain

    \Chayka\WP\Queries\TermQuery Chayka\WP\Queries\TermQuery::cacheDomain(string $domain)

Version 3.2 and above. The 'cache_domain' argument enables a unique cache key
to be produced when the query produced by get_terms() is stored in object cache.

For instance, if you are using one of this function's filters to modify the query
(such as 'terms_clauses'), setting 'cache_domain' to a unique value will not
overwrite the cache for similar queries. Default value is 'core'.

* Visibility: **public**


#### Arguments
* $domain **string**


