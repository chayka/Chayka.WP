Chayka\WP\Models\MenuItemModel
===============

Class MenuItemModel implements menu item wrapper model




* Class name: MenuItemModel
* Namespace: Chayka\WP\Models





Properties
----------


### $id

    protected integer $id

Menu item id



* Visibility: **protected**


### $title

    protected string $title

Menu item title



* Visibility: **protected**


### $href

    protected string $href

Menu item link



* Visibility: **protected**


### $classes

    protected \Chayka\WP\Models\array(sting) $classes

Menu item css classes



* Visibility: **protected**


### $subMenu

    protected \Chayka\WP\Models\MenuItemModel $subMenu

Sub menu



* Visibility: **protected**


### $isCurrent

    protected boolean $isCurrent = null

Flag that is true if this menu item corresponds to current page



* Visibility: **protected**


### $isParent

    protected boolean $isParent = null

Flag that is true if this item has sub items



* Visibility: **protected**


Methods
-------


### getId

    integer Chayka\WP\Models\MenuItemModel::getId()

Get menu item id



* Visibility: **public**




### setId

    mixed Chayka\WP\Models\MenuItemModel::setId(integer $id)

Set menu item id



* Visibility: **public**


#### Arguments
* $id **integer**



### getTitle

    string Chayka\WP\Models\MenuItemModel::getTitle()

Get menu item title



* Visibility: **public**




### setTitle

    mixed Chayka\WP\Models\MenuItemModel::setTitle(string $title)

Set menu item title



* Visibility: **public**


#### Arguments
* $title **string**



### getHref

    string Chayka\WP\Models\MenuItemModel::getHref()

Get menu item link



* Visibility: **public**




### setHref

    mixed Chayka\WP\Models\MenuItemModel::setHref(string $href)

Set menu item link



* Visibility: **public**


#### Arguments
* $href **string**



### getClasses

    array Chayka\WP\Models\MenuItemModel::getClasses()

Get menu item css classes



* Visibility: **public**




### setClasses

    mixed Chayka\WP\Models\MenuItemModel::setClasses($classes)

Set menu item css classes



* Visibility: **public**


#### Arguments
* $classes **mixed**



### addClass

    mixed Chayka\WP\Models\MenuItemModel::addClass($cls)

Add css class to menu item



* Visibility: **public**


#### Arguments
* $cls **mixed**



### removeClass

    mixed Chayka\WP\Models\MenuItemModel::removeClass($cls)

Remove css class from menu item



* Visibility: **public**


#### Arguments
* $cls **mixed**



### getSubMenu

    \Chayka\WP\Models\MenuItemModel Chayka\WP\Models\MenuItemModel::getSubMenu()

Get sub menu



* Visibility: **public**




### setSubMenu

    mixed Chayka\WP\Models\MenuItemModel::setSubMenu($subMenu)

Set sub menu



* Visibility: **public**


#### Arguments
* $subMenu **mixed**



### setIsCurrent

    mixed Chayka\WP\Models\MenuItemModel::setIsCurrent($value)

Set $isCurrent flag



* Visibility: **public**


#### Arguments
* $value **mixed**



### setIsParent

    mixed Chayka\WP\Models\MenuItemModel::setIsParent($value)

Set $isParent flag



* Visibility: **public**


#### Arguments
* $value **mixed**



### isCurrent

    boolean|null Chayka\WP\Models\MenuItemModel::isCurrent()

Check is this item is current



* Visibility: **public**




### isParent

    boolean|null Chayka\WP\Models\MenuItemModel::isParent()

Check if this item is parent



* Visibility: **public**



