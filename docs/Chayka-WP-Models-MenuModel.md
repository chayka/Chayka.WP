Chayka\WP\Models\MenuModel
===============

Class MenuModel implements menu wrapper model.




* Class name: MenuModel
* Namespace: Chayka\WP\Models





Properties
----------


### $id

    protected integer $id

Menu ID



* Visibility: **protected**


### $title

    protected string $title

Menu title



* Visibility: **protected**


### $classes

    protected \Chayka\WP\Models\array(string) $classes

Menu UL classes



* Visibility: **protected**


### $items

    protected \Chayka\WP\Models\array(MenuItemModel) $items

Menu items



* Visibility: **protected**


### $isCurrent

    protected null $isCurrent = null

Flag that is true when one of menu items is current



* Visibility: **protected**


Methods
-------


### getId

    integer Chayka\WP\Models\MenuModel::getId()

Get menu id



* Visibility: **public**




### setId

    mixed Chayka\WP\Models\MenuModel::setId(integer $id)

Set menu id



* Visibility: **public**


#### Arguments
* $id **integer**



### getTitle

    string Chayka\WP\Models\MenuModel::getTitle()

Get menu title



* Visibility: **public**




### setTitle

    mixed Chayka\WP\Models\MenuModel::setTitle(string $title)

Set menu title



* Visibility: **public**


#### Arguments
* $title **string**



### getClasses

    \Chayka\WP\Models\array(string) Chayka\WP\Models\MenuModel::getClasses()

Get menu UL classes



* Visibility: **public**




### setClasses

    mixed Chayka\WP\Models\MenuModel::setClasses(\Chayka\WP\Models\array(string) $classes)

Set menu UL classes



* Visibility: **public**


#### Arguments
* $classes **Chayka\WP\Models\array(string)**



### addClass

    mixed Chayka\WP\Models\MenuModel::addClass($cls)

Add class to UL classes



* Visibility: **public**


#### Arguments
* $cls **mixed**



### removeClass

    mixed Chayka\WP\Models\MenuModel::removeClass($cls)

Remove class from UL classes



* Visibility: **public**


#### Arguments
* $cls **mixed**



### getItems

    \Chayka\WP\Models\array(MenuItemModel) Chayka\WP\Models\MenuModel::getItems()

Get menu items



* Visibility: **public**




### setItems

    mixed Chayka\WP\Models\MenuModel::setItems(\Chayka\WP\Models\array(MenuItemModel) $items)

Set menu items



* Visibility: **public**


#### Arguments
* $items **Chayka\WP\Models\array(MenuItemModel)**



### addItem

    mixed Chayka\WP\Models\MenuModel::addItem(\Chayka\WP\Models\MenuItemModel $item)

Add menu item



* Visibility: **public**


#### Arguments
* $item **[Chayka\WP\Models\MenuItemModel](Chayka-WP-Models-MenuItemModel.md)**



### removeItem

    mixed Chayka\WP\Models\MenuModel::removeItem(integer $itemId)

Remove menu item by id



* Visibility: **public**


#### Arguments
* $itemId **integer**



### setIsCurrent

    mixed Chayka\WP\Models\MenuModel::setIsCurrent(boolean $value)

Set current flag



* Visibility: **public**


#### Arguments
* $value **boolean**



### isCurrent

    boolean|null Chayka\WP\Models\MenuModel::isCurrent()

Check if menu is current



* Visibility: **public**



