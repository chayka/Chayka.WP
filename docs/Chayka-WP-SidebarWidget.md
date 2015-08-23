Chayka\WP\SidebarWidget
===============

Class SidebarWidget implements our mechanism for creating sidebar widgets.

Use yo chayka generator to generate sidebar widgets.


* Class name: SidebarWidget
* Namespace: Chayka\WP
* Parent class: WP_Widget





Properties
----------


### $args

    protected array $args





* Visibility: **protected**
* This property is **static**.


Methods
-------


### __construct

    mixed Chayka\WP\SidebarWidget::__construct(string $id, string $name, string $description)

Widget constructor



* Visibility: **public**


#### Arguments
* $id **string**
* $name **string**
* $description **string**



### getView

    \Chayka\MVC\View Chayka\WP\SidebarWidget::getView()

This function should be overridden for the app.

E.g. return Plugin::getView();

* Visibility: **protected**




### getWidgetView

    \Chayka\MVC\View Chayka\WP\SidebarWidget::getWidgetView($instance)

Get instantiated view.



* Visibility: **protected**


#### Arguments
* $instance **mixed**



### getArgs

    array Chayka\WP\SidebarWidget::getArgs()

Get widget arguments



* Visibility: **public**
* This method is **static**.




### getArg

    mixed Chayka\WP\SidebarWidget::getArg($key, $default)

Get widget argument



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **mixed**
* $default **mixed**



### getTitle

    mixed Chayka\WP\SidebarWidget::getTitle()

Get widget title



* Visibility: **public**
* This method is **static**.




### widget

    mixed Chayka\WP\SidebarWidget::widget(array $args, array $instance)

Render widget view. Will be rendered using 'views/sidebar/<widget-id>/view.phtml'



* Visibility: **public**


#### Arguments
* $args **array**
* $instance **array**



### form

    string Chayka\WP\SidebarWidget::form(array $instance)

Render widget form. Will be rendered using 'views/sidebar/<widget-id>/form.phtml'



* Visibility: **public**


#### Arguments
* $instance **array**



### update

    array Chayka\WP\SidebarWidget::update(array $newInstance, array $oldInstance)

In fact this function filters entered data before update



* Visibility: **public**


#### Arguments
* $newInstance **array**
* $oldInstance **array**



### flush

    mixed Chayka\WP\SidebarWidget::flush()

Flush widget cache.



* Visibility: **public**




### registerWidget

    mixed Chayka\WP\SidebarWidget::registerWidget()

Register Widget Class



* Visibility: **public**
* This method is **static**.



