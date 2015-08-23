Chayka\WP\Queries\PostTermQuery
===============

Class PostTermQuery is a helper that allows to build $arguments array
for wp_get_object_terms()
For more details see https://codex.wordpress.org/Function_Reference/wp_get_object_terms

Note that this class is used to query terms in the context of some post,
nad not for overall site statistics.

To get overall site terms stats, use TermQuery


* Class name: PostTermQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $vars

    protected array $vars = array()

Array that holds arguments for wp_get_post_terms()



* Visibility: **protected**


### $taxonomies

    protected array $taxonomies = null

List of taxonomies under which terms should be acquired



* Visibility: **protected**


### $post

    protected \Chayka\WP\Queries\POstModel $post = null

The post whose terms we are going to retrive



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\PostTermQuery::__construct($post, string|\Chayka\WP\Queries\array(string) $taxonomies)

The query constructor allows to specify the post and the list of
taxonomies for the terms to retrieve



* Visibility: **public**


#### Arguments
* $post **mixed**
* $taxonomies **string|Chayka\WP\Queries\array(string)**



### getVars

    array Chayka\WP\Queries\PostTermQuery::getVars()

Get all vars



* Visibility: **public**




### setVars

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::setVars(array $vars)

Add vars to the set



* Visibility: **public**


#### Arguments
* $vars **array**



### setVar

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::setVar(string $key, mixed $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **mixed**



### query

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::query(\Chayka\WP\Models\PostModel $post, string|\Chayka\WP\Queries\array(string) $taxonomies)

Create instance of query object



* Visibility: **public**
* This method is **static**.


#### Arguments
* $post **[Chayka\WP\Models\PostModel](Chayka-WP-Models-PostModel.md)**
* $taxonomies **string|Chayka\WP\Queries\array(string)**



### select

    \Chayka\WP\Queries\array(TermModel) Chayka\WP\Queries\PostTermQuery::select(\Chayka\WP\Models\PostModel $post, string|\Chayka\WP\Queries\array(string) $taxonomies)

Load terms into the post defined.

You can omit $post and $taxonomies if this params where provided for constructor.

* Visibility: **public**


#### Arguments
* $post **[Chayka\WP\Models\PostModel](Chayka-WP-Models-PostModel.md)**
* $taxonomies **string|Chayka\WP\Queries\array(string)**



### selectOne

    \Chayka\WP\Models\TermModel Chayka\WP\Queries\PostTermQuery::selectOne(string $taxonomy, \Chayka\WP\Models\PostModel $post)

Select first matching term for the taxonomy



* Visibility: **public**


#### Arguments
* $taxonomy **string**
* $post **[Chayka\WP\Models\PostModel](Chayka-WP-Models-PostModel.md)**



### order

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::order(string $order)

Designates the ascending or descending order of the 'orderby' parameter.

Defaults to 'ASC'

* Visibility: **public**


#### Arguments
* $order **string**



### order_ASC

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::order_ASC()

Designates the ascending order of the 'orderby' parameter.



* Visibility: **public**




### order_DESC

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::order_DESC()

Designates the descending order of the 'orderby' parameter.



* Visibility: **public**




### orderBy

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy(string $orderBy)

Sort retrieved terms by parameter.



* Visibility: **public**


#### Arguments
* $orderBy **string**



### orderBy_ID

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_ID()

Sort retrieved terms by id.



* Visibility: **public**




### orderBy_Count

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_Count()

Sort retrieved terms by number of posts associated with the term.



* Visibility: **public**




### orderBy_Name

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_Name()

Sort retrieved terms by name (not a slug).



* Visibility: **public**




### orderBy_Slug

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_Slug()

Sort retrieved terms by slug.



* Visibility: **public**




### orderBy_TermOrder

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_TermOrder()

Sort retrieved terms by term order.



* Visibility: **public**




### orderBy_TermGroup

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_TermGroup()

Sort retrieved terms by term group.



* Visibility: **public**




### orderBy_None

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::orderBy_None()

Do not sort retrieved terms.



* Visibility: **public**




### fields

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::fields(string|\Chayka\WP\Queries\array(string) $fields)

Set return values.



* Visibility: **public**


#### Arguments
* $fields **string|Chayka\WP\Queries\array(string)**



### fields_All

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::fields_All()

all - returns an array of term objects - Default



* Visibility: **public**




### fields_AllWithObjectId

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::fields_AllWithObjectId()

all - returns an array of term objects - Default



* Visibility: **public**




### fields_Ids

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::fields_Ids()

ids - returns an array of integers



* Visibility: **public**




### fields_Names

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::fields_Names()

names - returns an array of strings



* Visibility: **public**




### fields_Slugs

    \Chayka\WP\Queries\PostTermQuery Chayka\WP\Queries\PostTermQuery::fields_Slugs()

slugs - returns an array of strings



* Visibility: **public**



