<?php

namespace Chayka\WP\Queries;

use Chayka\WP\Models\UserModel;

class UserQuery{

    protected $vars = array();
    
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
    
    public function role_Administrator(){
        return $this->role('Administrator');
    }
    
    public function role_Editor(){
        return $this->role('Editor');
    }
    
    public function role_Author(){
        return $this->role('Author');
    }
    
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
     * Set list of database table columns to matches the search string across multiple columns.
     *
     * @param array $columns
     * @return UserQuery
     */
    public function searchColumns($columns){
        return $this->setVar('search_columns', $columns);
    }
    
    public function searchColumns_ID(){
        $this->vars['search_columns'][]='ID';
        return $this;
    }
    
    public function searchColumns_Login(){
        $this->vars['search_columns'][]='user_login';
        return $this;
    }
    
    public function searchColumns_Nicname(){
        $this->vars['search_columns'][]='user_nicename';
        return $this;
    }
    
    public function searchColumns_Email(){
        $this->vars['search_columns'][]='user_email';
        return $this;
    }
    
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
     * @param string $order
     * @return UserQuery
     */
    public function order($order){
        return $this->setVar('order', $order);
    }
    
    public function order_ASC(){
        return $this->order('ASC');
    }
   
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
    
    public function orderBy_ID(){
        return $this->orderBy('ID');
    }
    
    public function orderBy_DisplayName(){
        return $this->orderBy('display_name');
    }
   
    public function orderBy_Name(){
        return $this->orderBy('user_name');
    }
    
    public function orderBy_Login(){
        return $this->orderBy('user_login');
    }
    
    public function orderBy_Nicename(){
        return $this->orderBy('user_nicename');
    }
    
    public function orderBy_Email(){
        return $this->orderBy('user_email');
    }
    
    public function orderBy_Url(){
        return $this->orderBy('user_url');
    }
    
    public function orderBy_DateRegistered(){
        return $this->orderBy('user_registered');
    }
    
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
    
    public function metaQueryRelation_AND(){
        return $this->metaQueryRelation('AND');
    }
    
    public function metaQueryRelation_OR(){
        return $this->metaQueryRelation('OR');
    }
    
    /**
     * Set return values.
     * 
     * @param string|array(string) $fields
     * @return UserQuery
     */
    public function fields($fields){
        return $this->setVar('fields', $fields);
    }
    
    public function fields_All(){
        return $this->fields('all');
    }
    
    public function fields_AllWithMeta(){
        return $this->fields('all_with_meta');
    }

}
