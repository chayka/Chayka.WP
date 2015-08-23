<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Queries;

use Chayka\WP\Models\UserModel;

/**
 * Class UserQuery is a helper that allows to build $arguments array
 * for WP_User_Query
 * For more details see https://codex.wordpress.org/Class_Reference/WP_User_Query
 *
 * @package Chayka\WP\Queries
 */
class UserQuery{

    /**
     * Holds an array that is formed using helper methods and passed to WP_User_Query()
     * to fetch users form DB.
     *
     * @var array
     */
    protected $vars = array();

    /**
     * UserQuery constructor. Does nothing for now.
     */
    public function __construct() {
        ;
    }

    /**
     * Get all vars
     *
     * @return array
     */
    public function getVars(){
        return $this->vars;
    }

	/**
	 * Add vars to the set
	 *
	 * @param array $vars
	 *
	 * @return $this
	 */
	public function setVars($vars){
		foreach($vars as $key=>$value){
			$this->vars[$key] = $value;
		}
		return $this;
	}

	/**
     * Set query filter var
     *
     * @param string $key
     * @param string $value
     * @return UserQuery
     */
    public function setVar($key, $value){
        $this->vars[$key] = $value;
        return $this;
    }

    /**
     * Create instance of query object
     *
     * @return UserQuery
     */
    public static function query(){
        return new self();
    }
    
    /**
     * Select all matching users
     * 
     * @return array(UserModel)
     */
    public function select(){
        return UserModel::selectUsers($this->getVars());
    }
    
    /**
     * Select first matching user
     * 
     * @return UserModel
     */
    public function selectOne(){
        $users = $this->select();
        return count($users)?reset($users):null;
    }
    
    /**
     * Show users associated with certain role.
     * 
     * @param string $role
     * @return UserQuery
     */
    public function role($role){
        return $this->setVar('role', $role);
    }

    /**
     * Show users associated with 'administrator' role.
     *
     * @return UserQuery
     */
    public function role_Administrator(){
        return $this->role('Administrator');
    }

    /**
     * Show users associated with 'editor' role.
     *
     * @return UserQuery
     */
    public function role_Editor(){
        return $this->role('Editor');
    }

    /**
     * Show users associated with 'author' role.
     *
     * @return UserQuery
     */
    public function role_Author(){
        return $this->role('Author');
    }

    /**
     * Show users associated with 'subscriber' role.
     *
     * @return UserQuery
     */
    public function role_Subscriber(){
        return $this->role('Subscriber');
    }
    
    /**
     * Show specific users.
     * 
     * @param string|array(int) $userIds
     * @return UserQuery
     */
    public function includeUserIds($userIds){
        return $this->setVar('include', $userIds);
    }

    /**
     * Show specific users.
     * 
     * @param string|array(int) $userIds
     * @return UserQuery
     */
    public function excludeUserIds($userIds){
        return $this->setVar('exclude', $userIds);
    }

    /**
     * Show users associated with certain blog on the network.
     * 
     * @param int $blogId
     * @return UserQuery
     */
    public function blogId($blogId){
        return $this->setVar('blog_id', $blogId);
    }
    
    /**
     * Searches for possible string matches on columns
     * 
     * @param string $search String to match
     * @param array(string) $columns List of database table columns to matches the search string across multiple columns.
     * @return UserQuery
     */
    public function search($search, $columns = null){
        if($columns){
            $this->searchColumns($columns);
        }
        return $this->setVar('search', $search);
    }
    
    /**
     * Set list of database table columns to match the search string across multiple columns.
     *
     * @param array $columns
     * @return UserQuery
     */
    public function searchColumns($columns){
        return $this->setVar('search_columns', $columns);
    }

    /**
     * Add 'ID' to the list of database table columns to match the search string across multiple columns.
     *
     * @return UserQuery
     */
    public function searchColumns_ID(){
        $this->vars['search_columns'][]='ID';
        return $this;
    }

    /**
     * Add 'user_login' to the list of database table columns to match the search string across multiple columns.
     *
     * @return UserQuery
     */
    public function searchColumns_Login(){
        $this->vars['search_columns'][]='user_login';
        return $this;
    }

    /**
     * Add 'user_nicename' to the list of database table columns to match the search string across multiple columns.
     *
     * @return UserQuery
     */
    public function searchColumns_Nicname(){
        $this->vars['search_columns'][]='user_nicename';
        return $this;
    }

    /**
     * Add 'user_email' to the list of database table columns to match the search string across multiple columns.
     *
     * @return UserQuery
     */
    public function searchColumns_Email(){
        $this->vars['search_columns'][]='user_email';
        return $this;
    }

    /**
     * Add 'user_url' to the list of database table columns to match the search string across multiple columns.
     *
     * @return UserQuery
     */
    public function searchColumns_Url(){
        $this->vars['search_columns'][]='user_url';
        return $this;
    }

    /**
     * The maximum returned number of results (needed in pagination).
     * 
     * @param int $number
     * @return UserQuery
     */
    public function number($number){
        return $this->setVar('number', $number);
    }
    
    /**
     * Offset the returned results (needed in pagination).
     * @param int $offset
     * @return UserQuery
     */
    public function offset($offset){
        return $this->setVar('offset', $offset);
    }
    
    /**
     * Designates the ascending or descending order of the 'orderby' parameter. 
     * Defaults to 'ASC'
     *
     * @param string $order
     * @return UserQuery
     */
    public function order($order){
        return $this->setVar('order', $order);
    }

    /**
     * Designates the ascending order of the 'orderby' parameter.
     *
     * @return UserQuery
     */
    public function order_ASC(){
        return $this->order('ASC');
    }

    /**
     * Designates the descending order of the 'orderby' parameter.
     *
     * @return UserQuery
     */
    public function order_DESC(){
        return $this->order('DESC');
    }
    
    /**
     * Sort retrieved users by parameter. Defaults to 'login'.
     * 
     * @param string $orderBy
     * @return UserQuery
     */
    public function orderBy($orderBy){
        return $this->setVar('orderby', $orderBy);
    }

    /**
     * Sort retrieved users by ID
     *
     * @return UserQuery
     */
    public function orderBy_ID(){
        return $this->orderBy('ID');
    }

    /**
     * Sort retrieved users by display_name
     *
     * @return UserQuery
     */
    public function orderBy_DisplayName(){
        return $this->orderBy('display_name');
    }

    /**
     * Sort retrieved users by user_name
     *
     * @return UserQuery
     */
    public function orderBy_Name(){
        return $this->orderBy('user_name');
    }

    /**
     * Sort retrieved users by user_login
     *
     * @return UserQuery
     */
    public function orderBy_Login(){
        return $this->orderBy('user_login');
    }

    /**
     * Sort retrieved users by user_nicename
     *
     * @return UserQuery
     */
    public function orderBy_Nicename(){
        return $this->orderBy('user_nicename');
    }

    /**
     * Sort retrieved users by user_email
     *
     * @return UserQuery
     */
    public function orderBy_Email(){
        return $this->orderBy('user_email');
    }

    /**
     * Sort retrieved users by user_url
     *
     * @return UserQuery
     */
    public function orderBy_Url(){
        return $this->orderBy('user_url');
    }

    /**
     * Sort retrieved users by user_registered
     *
     * @return UserQuery
     */
    public function orderBy_DateRegistered(){
        return $this->orderBy('user_registered');
    }

    /**
     * Sort retrieved users by post_count
     *
     * @return UserQuery
     */
    public function orderBy_PostCount(){
        return $this->orderBy('post_count');
    }

    /**
     * Custom field key.
     * 
     * @param string $key
     * @return UserQuery
     */
    public function metaKey($key){
        return $this->setVar('meta_key', $key);
    }
    
    /**
     * Custom field value
     * 
     * @param string $value
     * @return UserQuery
     */
    public function metaValue($value){
        return $this->setVar('meta_value', $value);
    }
    
    /**
     * Custom field numeric value
     * 
     * @param number $value
     * @return UserQuery
     */
    public function metaValueNum($value){
        return $this->setVar('meta_value_num', $value);
    }
    
    /**
     * Operator to test the 'meta_value'. 
     * Possible values are '!=', '>', '>=', '<', or '<='. Default value is '='.
     * 
     * @param string $compare
     * @return UserQuery
     */
    public function metaCompare($compare){
        return $this->setVar('meta_compare', $compare);
    }
    
    /**
     * Custom field parameters (available with Version 3.5).
     * 
     * @param string $key Custom field key
     * @param string|array $value Custom field value 
     * (Note: Array support is limited to a compare value of 
     * 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' or 'NOT EXISTS')
     * @param string $compare Operator to test. Possible values are 
     * '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 
     * 'BETWEEN', 'NOT BETWEEN', 'EXISTS', and 'NOT EXISTS'. 
     * Default value is '='.
     * @param string $type Custom field type. Possible values are 
     * 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 
     * 'TIME', 'UNSIGNED'. Default value is 'CHAR'.
     * @return UserQuery
     */
    public function metaQuery($key, $value, $compare = '=', $type = 'CHAR'){
        $metaQuery = array(
            'key' => $key,
            'value' => $value,
            'compare' => $compare,
            'type' => $type,
        );
        
        $this->vars['meta_query'][]=$metaQuery;
        
        return $this;
    }

    /**
     * Set relation for multiple meta_query handling
     * Should come first before metaQuery() call
     *  
     * @param string $relation
     * @return UserQuery
     */
    public function metaQueryRelation($relation){
        $this->vars['meta_query']['relation']=$relation;
        
        return $this;
    }

    /**
     * Set 'AND' relation for multiple meta_query handling
     * Should come first before metaQuery() call
     *
     * @return UserQuery
     */
    public function metaQueryRelation_AND(){
        return $this->metaQueryRelation('AND');
    }

    /**
     * Set 'OR' relation for multiple meta_query handling
     * Should come first before metaQuery() call
     *
     * @return UserQuery
     */
    public function metaQueryRelation_OR(){
        return $this->metaQueryRelation('OR');
    }
    
    /**
     * Set return values format.
     * 
     * @param string|array(string) $fields
     * @return UserQuery
     */
    public function fields($fields){
        return $this->setVar('fields', $fields);
    }

    /**
     * Set return values format to all fields.
     *
     * @return UserQuery
     */
    public function fields_All(){
        return $this->fields('all');
    }

    /**
     * Set return values format to all fields.
     * Codex remark: 'all_with_meta' currently returns the same fields as 'all'
     * which does not include user fields stored in wp_usermeta.
     * You must create a second query to get the user meta fields by ID
     * or use the __get PHP magic method to get the values of these fields.
     *
     * @return UserQuery
     */
    public function fields_AllWithMeta(){
        return $this->fields('all_with_meta');
    }

}
