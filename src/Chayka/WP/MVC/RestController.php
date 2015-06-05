<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 10.05.15
 * Time: 15:00
 */

namespace Chayka\WP\MVC;

use Chayka\Helpers\InputHelper;
use Chayka\MVC;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\JsonHelper;

class RestController extends MVC\RestController{

	protected $modelClassName;

	/**
	 * Get model classname for this RestController
	 *
	 * @return string
	 */
	public function getModelClassName() {
		return $this->modelClassName;
	}

	/**
	 * Set model classname fot this RestController
	 *
	 * @param string $modelClassName
	 */
	public function setModelClassName($modelClassName) {
		$this->modelClassName = $modelClassName;
	}

	/**
	 * Init
	 */
	public function init(){
		InputHelper::captureInput();
		InputHelper::preserveSlashes('model');
		$model = InputHelper::getParam('model');
		if($model){
			$this->setModelClassName($model);
		}
	}

	/**
	 * Action called on POST to controller, creates a model.
	 * @param bool $respond
	 *
	 * @return \Chayka\WP\Models\ReadyModel
	 */
	public function createAction($respond = true){
		InputHelper::setParam('action', 'create');
		$class = $this->getModelClassName();
		/**
		 * @var \Chayka\WP\Models\ReadyModel $model
		 */
		$model = call_user_func(array($class, 'unpackJsonItem'));
		$meta = InputHelper::getParam('meta', array());
		$userCan = $model?$model->userCan('create'):false;
		$errors = call_user_func(array($class, 'getValidationErrors'));
		if(!$userCan || !empty($errors)){
			JsonHelper::respondErrors($errors);
		}else{
			$id = $model->insert();
			if ($id) {
				if(count($meta) && method_exists($model, 'updateMeta')){
					foreach ($meta as $key=>$value){
						if(strpos($key, 'wp_')===false && !is_serialized($meta[$key])){
							$model->updateMeta($key, $value);
						}
					}
				}
				$model = call_user_func(array($class, 'selectById'), $id, false);
				apply_filters($class . '.created', $model);
				if ($respond) {
					JsonHelper::respond($model);
				}
			} else {
				JsonHelper::respondError('Failed to create entity');
			}
		}

		return $model;
	}

	/**
	 * Action called on PUT to controller, updates a model.
	 * @param bool $respond
	 *
	 * @return \Chayka\WP\Models\ReadyModel
	 */
	public function updateAction($respond = true){
		InputHelper::setParam('action', 'update');
		$id = InputHelper::getParam('id');
		$class = $this->getModelClassName();
		/**
		 * @var \Chayka\WP\Models\ReadyModel
		 */
		$model = call_user_func(array($class, 'unpackJsonItem'));
		$meta = InputHelper::getParam('meta', array());
		$userCan = $model?$model->userCan('update'):false;
		$errors = call_user_func(array($class, 'getValidationErrors'));
		if(!$userCan || !empty($errors)){
			JsonHelper::respondErrors($errors);
		}else{
			try{
				if($model->update()){
					if(count($meta) && is_array($meta) && method_exists($model, 'updateMeta')){
						foreach ($meta as $key=>$value){
							if(strpos($key, 'wp_')===false && !is_serialized($meta[$key])){
								$model->updateMeta($key, $value);
							}
						}
					}
					$model = call_user_func(array($class, 'selectById'), $id, false);
					apply_filters($class.'.updated', $model);
					if($respond){
						JsonHelper::respond($model);
					}
				}else{
					JsonHelper::respondError('failed');
				}
			}catch(\Exception $e){
				JsonHelper::respondException($e);
			}
		}

		return $model;

	}

	/**
	 * Action called on DELETE to controller, deletes a model.
	 * @param bool $respond
	 *
	 * @return \Chayka\WP\Models\ReadyModel
	 */
	public function deleteAction($respond = true){
		$id = InputHelper::getParam('id');
		$class = $this->getModelClassName();
		$model = call_user_func(array($class, 'selectById'), $id);
		if(!$model){
			JsonHelper::respondError('Entry not found');
		}
		if(!$model->userCan('delete')){
			$errors = call_user_func(array($class, 'getValidationErrors'));
			JsonHelper::respondErrors($errors);
		}
		if(!call_user_func(array($class, 'validateInput'), array(), $model)){
			$errors = $model->getValidationErrors();
			JsonHelper::respondErrors($errors);
		}
		$result = $model->delete();
		if($result){
			apply_filters($class.'.deleted', $model);
		}
		if($respond){
			JsonHelper::respond(null, $result?0:1);
		}
		return $result;
	}

	/**
	 * Action called on GET to controller, reads a model.
	 * @param bool $respond
	 *
	 * @return \Chayka\WP\Models\ReadyModel
	 */
	public function readAction($respond = true){
		$id = InputHelper::getParam('id');
		$class = $this->getModelClassName();
		$model = call_user_func(array($class, 'selectById'), $id);
		if(!$model){
			JsonHelper::respondError('Entry not found');
		}
		if(!$model->userCan('read')){
			$errors = call_user_func(array($class, 'getValidationErrors'));
			JsonHelper::respondErrors($errors);
		}
		if($respond){
			JsonHelper::respond($model, $model?0:1);
		}
		return $model;
	}

	/**
	 * Action should be mapped like an ordinary controller-action route.
	 * This one is simple but functional stub can be overridden for more
	 * sophisticated cases (like sorting and filtering)
	 *
	 * @param bool $respond
	 *
	 * @return \Chayka\WP\Models\ReadyModel
	 */
	public function listAction($respond = true){
		$limit = InputHelper::getParam('limit', 10);
		$offset = InputHelper::getParam('offset', 0);
		$page = InputHelper::getParam('page', 0);
		$payload = array();

		if(!$limit){
			$models = call_user_func(array($this->getModelClassName(), 'selectAll'));
		}elseif($page){
			$models = call_user_func(array($this->getModelClassName(), 'selectPage'), $page, $limit);
			$payload['page'] = $page;
			$payload['limit'] = $limit;
		}else{
			$models = call_user_func(array($this->getModelClassName(), 'selectLimitOffset'), $limit, $offset);
			$payload['offset'] = $offset;
			$payload['limit'] = $limit;
		}
		$total = DbHelper::rowsFound();
		$payload['items']=$models;
		$payload['total']=$total;
		JsonHelper::respond($payload);
	}

}