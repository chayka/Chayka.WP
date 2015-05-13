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

	public function getModelClassName() {
		return $this->modelClassName;
	}

	public function setModelClassName($modelClassName) {
		$this->modelClassName = $modelClassName;
	}

	public function init(){
		InputHelper::captureInput();
		$model = InputHelper::getParam('model');
		if($model){
			$this->setModelClassName($model);
		}
	}

	public function createAction($respond = true){
		InputHelper::setParam('action', 'create');
		$class = $this->getModelClassName();
//		$model = new $class();
//		$model = InputHelper::getModelFromInput($model);
		/**
		 * @var \Chayka\WP\Models\ReadyModel $model
		 */
		$model = call_user_func(array($this->getModelClassName(), 'unpackJsonItem'));
		$meta = InputHelper::getParam('meta', array());
		$errors = call_user_func(array($this->getModelClassName(), 'getValidationErrors'));
		if(!empty($errors)){
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
				$model = call_user_func(array($this->getModelClassName(), 'selectById'), $id, false);
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

	public function updateAction($respond = true){
		InputHelper::setParam('action', 'update');
		$id = InputHelper::getParam('id');
		$class = $this->getModelClassName();
		/**
		 * @var \Chayka\WP\Models\ReadyModel
		 */
		$model = call_user_func(array($this->getModelClassName(), 'unpackJsonItem'));
		$meta = InputHelper::getParam('meta', array());
		$errors = call_user_func(array($this->getModelClassName(), 'getValidationErrors'));
		if(!empty($errors)){
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

	public function deleteAction($respond = true){
		$id = InputHelper::getParam('id');
		$class = $this->getModelClassName();
		$model = call_user_func(array($class, 'selectById'), $id);
		if(!$model->validateInput(array(), 'delete')){
			$errors = $model->getValidationErrors();
			JsonHelper::respondErrors($errors);
		}
		$result = $model->delete();//WpDbHelper::delete($table, $key, $id);
		if($result){
			apply_filters($class.'.deleted', $model);
		}
		if($respond){
			JsonHelper::respond(null, $result?0:1);
		}
		return $result;
	}

	public function readAction($respond = true){
		$id = InputHelper::getParam('id');
		$model = call_user_func(array($this->getModelClassName(), 'selectById'), $id);
		if($respond){
			JsonHelper::respond($model, $model?0:1);
		}
		return $model;
	}

	public function listAction($respond = true){
		$limit = InputHelper::getParam('limit', 10);
		$offset = InputHelper::getParam('offset', 0);
		$page = InputHelper::getParam('page', 0);
		if(!$limit){
			$models = call_user_func(array($this->getModelClassName(), 'selectAll'));
		}elseif($page){
			$models = call_user_func(array($this->getModelClassName(), 'selectPage'), $page, $limit);
		}else{
			$models = call_user_func(array($this->getModelClassName(), 'selectLimitOffset'), $limit, $offset);
		}
		$total = DbHelper::rowsFound();
		JsonHelper::respond(array(
			'items' => $models,
			'total' => $total
		));
	}

}