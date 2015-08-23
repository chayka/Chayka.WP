Chayka\WP\MVC\RestController
===============

Class RestController extends Chayka\MVC\RestController.

When extending this class you can specify $modelClassName that is a classname of
ReadyModel descendant and you can consider CRUD implemented,
have to define only list action after that.


* Class name: RestController
* Namespace: Chayka\WP\MVC
* Parent class: Chayka\MVC\RestController





Properties
----------


### $modelClassName

    protected string $modelClassName

When extending this class you can specify $modelClassName that is a classname of
ReadyModel descendant and you can consider CRUD implemented,
have to define only list action after that.



* Visibility: **protected**


Methods
-------


### getModelClassName

    string Chayka\WP\MVC\RestController::getModelClassName()

Get model classname for this RestController



* Visibility: **public**




### setModelClassName

    mixed Chayka\WP\MVC\RestController::setModelClassName(string $modelClassName)

Set model classname fot this RestController



* Visibility: **public**


#### Arguments
* $modelClassName **string**



### init

    mixed Chayka\WP\MVC\RestController::init()

Init



* Visibility: **public**




### createAction

    \Chayka\WP\Models\ReadyModel Chayka\WP\MVC\RestController::createAction(boolean $respond)

Action called on POST to controller, creates a model.



* Visibility: **public**


#### Arguments
* $respond **boolean**



### updateAction

    \Chayka\WP\Models\ReadyModel Chayka\WP\MVC\RestController::updateAction(boolean $respond)

Action called on PUT to controller, updates a model.



* Visibility: **public**


#### Arguments
* $respond **boolean**



### deleteAction

    \Chayka\WP\Models\ReadyModel Chayka\WP\MVC\RestController::deleteAction(boolean $respond)

Action called on DELETE to controller, deletes a model.



* Visibility: **public**


#### Arguments
* $respond **boolean**



### readAction

    \Chayka\WP\Models\ReadyModel Chayka\WP\MVC\RestController::readAction(boolean $respond)

Action called on GET to controller, reads a model.



* Visibility: **public**


#### Arguments
* $respond **boolean**



### listAction

    \Chayka\WP\Models\ReadyModel Chayka\WP\MVC\RestController::listAction(boolean $respond)

Action should be mapped like an ordinary controller-action route.

This one is simple but functional stub can be overridden for more
sophisticated cases (like sorting and filtering)

* Visibility: **public**


#### Arguments
* $respond **boolean**


