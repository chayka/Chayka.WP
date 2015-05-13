<?php

namespace Chayka\WP\Models;

use Chayka\Helpers\Util;
use Chayka\Helpers\JsonReady;
use Chayka\Helpers\InputReady;
use Chayka\Helpers\InputHelper;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\DbReady;
use Chayka\WP\Queries\TermQuery;
use Chayka\WP\Queries\PostTermQuery;
use Exception;
use WP_Error;

class TermModel implements DbReady, JsonReady, InputReady{

	protected static $validationErrors;
	protected $wpTerm;

    public function __construct() {
        $this->wpTerm = new \stdClass();
        $this->wpRelation = new \stdClass();
    }

    /**
     * DbReady method, returns corresponding DB Table ID column name
     *
     * @return string
     */
    public static function getDbIdColumn() {
        return 'term_id';
    }

    /**
     * DbReady method, returns corresponding DB Table name
     *
     * @return string
     */
    public static function getDbTable() {
        global $wpdb;
        return $wpdb->term_taxonomy;
    }

    /**
     * Get term id (alias of $this->getTermId())
     *
     * @return integer
     */
    public function getId() {
        return $this->getTermId();
    }

    /**
     * Set term id (alias of $this->setTermId())
     *
     * @param integer $val
     * @return TermModel
     */
    public function setId($val){
        return $this->setTermId($val);
    }

    /**
     * Get term id
     *
     * @return integer
     */
    public function getTermId() {
        return Util::getItem($this->wpTerm, 'term_id', 0);
    }

    /**
     * Set term id
     *
     * @param $val
     * @return TermModel
     */
    public function setTermId($val){
        $this->wpTerm->term_id = intval($val);
        return $this;
    }

    /**
     * Get term name
     *
     * @return string
     */
    public function getName() {
        return Util::getItem($this->wpTerm, 'name', '');
    }

    /**
     * Set term name
     *
     * @param string $val
     * @return TermModel
     */
    public function setName($val){
        $this->wpTerm->name = $val;
        return $this;
    }

    /**
     * Get term slug
     *
     * @return string
     */
    public function getSlug() {
        return Util::getItem($this->wpTerm, 'slug', '');
    }

    /**
     * Set term slug
     *
     * @param string $val
     * @return TermModel
     */
    public function setSlug($val){
        $this->wpTerm->slug = $val;
        return $this;
    }

    /**
     * Get term group
     *
     * @return integer
     */
    public function getGroup() {
        return Util::getItem($this->wpTerm, 'term_group', 0);
    }

    /**
     * Set term group
     *
     * @param integer $val
     * @return TermModel
     */
    public function setGroup($val){
        $this->wpTerm->term_group = intval($val);
        return $this;
    }

    /**
     * Get term-to-post relation id (term_taxonomy_id)
     *
     * @return integer
     */
    public function getRelationId() {
        return Util::getItem($this->wpTerm, 'term_taxonomy_id', 0);
    }

    /**
     * Set term-to-post relation id (term_taxonomy_id)
     *
     * @param integer$val
     * @return TermModel
     */
    public function setRelationId($val){
        $this->wpTerm->term_taxonomy_id = intval($val);
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return string
     */
    public function getTaxonomy() {
        return Util::getItem($this->wpTerm, 'taxonomy', '');
    }

    /**
     * Set taxonomy
     *
     * @param string $val
     * @return TermModel
     */
    public function setTaxonomy($val){
        $this->wpTerm->taxonomy = $val;
        return $this;
    }

    /**
     * Get term description
     *
     * @return string
     */
    public function getDescription() {
        return Util::getItem($this->wpTerm, 'description', '');
    }

    /**
     * Set term description
     *
     * @param string $val
     * @return TermModel
     */
    public function setDescription($val){
        $this->wpTerm->description = $val;
        return $this;
    }

    /**
     * Get parent term id
     *
     * @return integer
     */
    public function getParentId() {
        return Util::getItem($this->wpTerm, 'parent', 0);
    }

    /**
     * Set parent term id
     *
     * @param $val
     * @return $this
     */
    public function setParentId($val){
        $this->wpTerm->parent = intval($val);
        return $this;
    }

    /**
     * Get number of term occurrences across the taxonomy
     *
     * @return integer
     */
    public function getCountPerTaxonomy() {
        return Util::getItem($this->wpTerm, 'count', 0);
    }

    /**
     * Set number of term occurrences across the taxonomy
     *
     * @param integer $val
     * @return TermModel
     */
    public function setCountPerTaxonomy($val){
        $this->wpTerm->count = intval($val);
        return $this;
    }

    /**
     * Get term href
     *
     * @return string|WP_Error
     */
    public function getHref(){
        return get_term_link($this->wpTerm, $this->getTaxonomy());
    }

    /**
     * Magic getter that allows to use TermModel where wpTerm should be used
     *
     * @param $name
     * @return mixed
     */
    public function __get($name) {
        return Util::getItem($this->wpTerm, $name);
    }

    /**
     * Insert term into db, return generated id
     *
     * @return int
     * @throws Exception
     */
    public function insert() {
        if(!$this->getId() && !$this->getName()){
            throw new Exception('TermModel: no term set', 1);
        }
        if(!$this->getTaxonomy()){
            throw new Exception('TermModel: no taxonomy set', 2);
        }
        $res = wp_insert_term($this->getId()?$this->getId():$this->getName(), 
                $this->getTaxonomy(), 
                $this->packDbRecord());
        if(is_wp_error($res)){
            throw new Exception($res->get_error_message(), $res->get_error_code());
        }
        if(!is_wp_error($res)){
            $this->setTermId(Util::getItem($res, 'term_id', 0));
            $this->setRelationId(Util::getItem($res, 'term_taxonomy_id', 0));
        }
        return $this->getId();
    }

    /**
     * Update term in db
     * Returns Term ID and Taxonomy Term ID
     *
     * @return array|null|WP_Error
     * @throws Exception
     */
    public function update() {
        if(!$this->getId()){
            throw new Exception('TermModel: no term set', 1);
        }
        if(!$this->getTaxonomy()){
            throw new Exception('TermModel: no taxonomy set', 2);
        }
        $res = wp_update_term($this->getId(), 
                $this->getTaxonomy(), 
                $this->packDbRecord(true));
        if(is_wp_error($res)){
            throw new Exception($res->get_error_message()/*, $res->get_error_code()*/);
        }
        return is_wp_error($res)?null:$res;
    }

    /**
     * Removes a term from the database.
     *
     * If the term is a parent of other terms, then the children will be updated to
     * that term's parent.
     *
     * The $args 'default' will only override the terms found, if there is only one
     * term found. Any other and the found terms are used.
     *
     * The $args 'force_default' will force the term supplied as default to be
     * assigned even if the object was not going to be termless
     *
     * @param array $args
     * @return bool|null|WP_Error
     * @throws Exception
     */
    public function delete($args = array()) {
        if(!$this->getTaxonomy()){
            throw new Exception('TermModel: no taxonomy set', 2);
        }
        $res = wp_delete_term($this->getId(), $this->getTaxonomy(), $args);
        if(is_wp_error($res)){
            throw new Exception($res->get_error_message(), $res->get_error_code());
        }
        return is_wp_error($res)?null:$res;
    }

    /**
     * Packs model into assoc array before commiting to DB
     *
     * @param bool $forUpdate
     * @return array
     */
    public function packDbRecord($forUpdate = false) {
        $dbRecord = array(
            'description' => $this->getDescription(),
            'slug' => $this->getSlug(),
            'parent' => $this->getParentId(),
        );
        if($forUpdate){
            $dbRecord['name'] = $this->getName();
            $dbRecord['term_group'] = $this->getGroup();
        }
//        Util::print_r($dbRecord);
        return $dbRecord;
    }

    /**
     * Unpacks db record while fetching model from DB
     *
     * @param object|array $dbRecord
     * @return TermModel
     */
    public static function unpackDbRecord($dbRecord) {
        $obj = new self();
        $obj->setTermId(Util::getItem($dbRecord, 'term_id', 0));
        $obj->setName(Util::getItem($dbRecord, 'name'));
        $obj->setSlug(Util::getItem($dbRecord, 'slug'));
        $obj->setGroup(Util::getItem($dbRecord, 'term_group', 0));
        $obj->setRelationId(Util::getItem($dbRecord, 'term_taxonomy_id', 0));
        $obj->setTaxonomy(Util::getItem($dbRecord, 'taxonomy'));
        $obj->setDescription(Util::getItem($dbRecord, 'description'));
        $obj->setParentId(Util::getItem($dbRecord, 'parent', 0));
        $obj->setCountPerTaxonomy(Util::getItem($dbRecord, 'count', 0));
        
        return $obj;
    }

    /**
     * Selects term by term_taxonomy_id
     * 
     * @param int $id
     * @param boolean $useCache
     * @return TermModel
     */
    public static function selectById($id, $useCache = true) {
        $wpdb = DbHelper::wpdb();
        $t1 = $wpdb->term_taxonomy;
        $t2 = $wpdb->terms;
        $sql = $wpdb->prepare("
            SELECT *
            FROM $t1 LEFT JOIN $t2 USING(term_id)
            WHERE term_taxonomy_id = %d
            ", $id);
        $dbRecord = $wpdb->get_row($sql);
        return self::unpackDbRecord($dbRecord);
    }
    
    /**
     * Select term by one of the following fields:
     * 'slug', 'name', 'id' (term_id), or 'term_taxonomy_id'
     *
     * @param string $field Either 'slug', 'name', 'id' (term_id), or 'term_taxonomy_id'
     * @param string $value
     * @param string $taxonomy
     * @param string $output
     * @param string $filter
     * @return TermModel
     */
    public static function selectBy($field, $value, $taxonomy, $output = OBJECT, $filter = 'raw'){
        $dbRecord = get_term_by($field, $value, $taxonomy, $output, $filter);
        return $dbRecord?self::unpackDbRecord($dbRecord):null;
    }

    /**
     * Select term by term id
     *
     * @param int $value
     * @param string $taxonomy
     * @param string $output
     * @param string $filter
     * @return TermModel
     */
    public static function selectByTermId($value, $taxonomy, $output = OBJECT, $filter = 'raw'){
        return self::selectBy('id', $value, $taxonomy, $output, $filter);
    }

    /**
     * Select term by slug
     *
     * @param string $value
     * @param string $taxonomy
     * @param string $output
     * @param string $filter
     * @return TermModel
     */
    public static function selectBySlug($value, $taxonomy, $output = OBJECT, $filter = 'raw'){
        return self::selectBy('slug', $value, $taxonomy, $output, $filter);
    }
    
    /**
     * Select term by name
     *
     * @param string $value
     * @param string $taxonomy
     * @param string $output
     * @param string $filter
     * @return TermModel
     */
    public static function selectByName($value, $taxonomy, $output = OBJECT, $filter = 'raw'){
        return self::selectBy('name', $value, $taxonomy, $output, $filter);
    }
    
    /**
     * Select term by filtering args.
     * Use TermModel::query() or TermModel::queryPostTerms() instead.
     *
     * @param string|array $taxonomies Taxonomy name or list of Taxonomy names.
     * @param array|string $args
     * @return array(TermModel)
     */
    public static function selectTerms($taxonomies, $args){
        $dbRecords = get_terms($taxonomies, $args);
        $terms = array();
        foreach($dbRecords as $dbRecord){
            $term = self::unpackDbRecord($dbRecord);
            if($term){
                $terms[]=$term;
            }
        }
        return $terms;
    }

    /**
     * Get query helper instance
     *
     * @param string|array(string) $taxonomies
     * @return TermQuery
     */
    public static function query($taxonomies = null){
        return new TermQuery($taxonomies);
    }

    /**
     * @param PostModel $post
     * @param string|array(string) $taxonomies
     * @return PostTermQuery
     */
    public static function queryPostTerms($post, $taxonomies){
        return new PostTermQuery($post, $taxonomies);
    }

    /**
     * Get validation errors after unpacking from request input
     * Should be set by validateInput
     *
     * @return array[field]='Error Text'
     */
    public static function getValidationErrors() {
        return self::$validationErrors;
    }

	/**
	 * Add validation errors after unpacking from request input
	 *
	 * @param array[field]='Error Text' $errors
	 */
	public static function addValidationErrors($errors) {
		static::$validationErrors = array_merge(static::$validationErrors, $errors);
	}
    /**
     * Unpacks request input.
     * Used by REST Controllers.
     *
     * @param array $input
     * @return TermModel
     */
    public static function unpackJsonItem($input = array()) {
        if(empty($input)){
            $input = InputHelper::getParams();
        }
	    $id = Util::getItem($input, 'id', 0);

	    $obj = $id? static::selectById($id): new static();

	    $valid = static::validateInput($input, $id? $obj:null);

	    if($valid){
            $input = array_merge($obj->packJsonItem(), $input);

//        $this->setId(Util::getItem($input, 'id', 0));
		    $obj->setTermId(Util::getItem($input, 'term_id'));
		    $obj->setName(Util::getItem($input, 'name'));
		    $obj->setSlug(Util::getItem($input, 'slug'));
		    $obj->setGroup(Util::getItem($input, 'term_group', 0));
		    $obj->setTaxonomy(Util::getItem($input, 'taxonomy'));
		    $obj->setDescription(Util::getItem($input, 'daescription'));
		    $obj->setParentId(Util::getItem($input, 'parent', 0));
//        $obj->setCountPerTaxonomy(Util::getItem($input, 'count', 0));
            return $obj;
	    }

	    return null;
    }

	/**
	 * Validates input and sets $validationErrors
	 *
	 * @param array $input
	 * @param TermModel $oldState
	 *
	 * @return bool is input valid
	 */
    public static function validateInput($input = array(), $oldState = null) {
	    static::$validationErrors = array();
        $valid = apply_filters('TermModel.validateInput', true, $input, $oldState);
        return $valid;
    }

    /**
     * Packs this post into assoc array for JSON representation.
     * Used for API Output
     *
     * @return array
     */
    public function packJsonItem() {
        return array(
            'id' => $this->getRelationId(),
            'term_id'=>$this->getTermId(),
            'name'=>$this->getName(),
            'slug'=>$this->getSlug(),
            'term_group'=>$this->getGroup(),
            'taxonomy'=>$this->getTaxonomy(),
            'description'=>$this->getDescription(),
            'parent'=>$this->getParentId(),
            'count'=>$this->getCountPerTaxonomy(),
        );
    }

}

