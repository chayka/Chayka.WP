<?php

namespace Chayka\WP\Queries;

class TaxonomyQuery{

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
     * @return TermQuery
     */
    public function setVar($key, $value){
        $this->vars[$key] = $value;
        return $this;
    }
    
    /**
     * Create instance of query object
     *
     * @return TaxonomyQuery
     */
    public static function query(){
        return new self();
    }
    
    /**
     * Select by name
     * 
     * @param string $name
     * @return TaxonomyQuery
     */
    public function name($name){
        return $this->setVar('name', $name);
    }
    
    /**
     * Select by post types
     * 
     * @param string|array(string) $postTypes
     * @return TaxonomyQuery
     */
    public function postType($postTypes){
        return $this->setVar('object_type', $postTypes);
    }
    
    /**
     * Select by label
     * 
     * @param string $label
     * @return TaxonomyQuery
     */
    public function label($label){
        return $this->setVar('label', $label);
    }
    
    /**
     * Select by label
     * 
     * @param string $label
     * @return TaxonomyQuery
     */
    public function labelSingular($label){
        return $this->setVar('singular_label', $label);
    }
    
    /**
     * Select by show_ui flag
     * 
     * @param boolean $flag
     * @return TaxonomyQuery
     */
    public function showUI($flag){
        return $this->setVar('show_ui', $flag);
    }
    
    /**
     * Select by show_tag_cloud flag
     * 
     * @param boolean $flag
     * @return TaxonomyQuery
     */
    public function showTagCloud($flag){
        return $this->setVar('show_tag_cloud', $flag);
    }
    
    /**
     * Select by public flag
     * 
     * @param boolean $flag
     * @return TaxonomyQuery
     */
    public function isPublic($flag){
        return $this->setVar('public', $flag);
    }
    
    /**
     * Select by _builtin flag
     * 
     * @param boolean $flag
     * @return TaxonomyQuery
     */
    public function isBuiltIn($flag){
        return $this->setVar('_builtin', $flag);
    }
    
    /**
     * Select by update_count_callback
     * 
     * @param mixed $callback
     * @return TaxonomyQuery
     */
    public function updateCountCallback($callback){
        return $this->setVar('update_count_callback', $callback);
    }
    
    /**
     * Select by rewrite
     * 
     * @param mixed $rewrite
     * @return TaxonomyQuery
     */
    public function rewrite($rewrite){
        return $this->setVar('rewrite', $rewrite);
    }
    
    /**
     * Select by rewrite args
     * 
     * @param string $slug Used as pretty permalink text (i.e. /tag/) - defaults to $taxonomy (taxonomy's name slug)
     * @param boolean $withFront allowing permalinks to be prepended with front base - defaults to true
     * @param boolean $hierarchical true or false allow hierarchical urls (implemented in Version 3.1) - defaults to false
     * @param int $epMask Assign an endpoint mask for this taxonomy - defaults to EP_NONE. For more info see this Make WordPress Plugins summary of endpoints.
     * @return TaxonomyQuery
     */
    public function rewriteArgs($slug, $withFront = true, $hierarchical = false, $epMask = EP_NONE){
        return $this->rewrite(array(
            'slug' => $slug,
            'with_front' => $withFront,
            'hierarchical' => $hierarchical,
            'ep_mask' => $epMask,
        ));
    }
    
    /**
     * Select by query_var
     * 
     * @param mixed $queryVar
     * @return TaxonomyQuery
     */
    public function queryVar($queryVar){
        return $this->setVar('rewrite', $queryVar);
    }
    
    /**
     * Select by manage_cap
     * 
     * @param mixed $cap
     * @return TaxonomyQuery
     */
    public function manageCap($cap){
        return $this->setVar('manage_cap', $cap);
    }
    
    /**
     * Select by edit_cap
     * 
     * @param mixed $cap
     * @return TaxonomyQuery
     */
    public function editCap($cap){
        return $this->setVar('edit_cap', $cap);
    }
    
    /**
     * Select by delete_cap
     * 
     * @param mixed $cap
     * @return TaxonomyQuery
     */
    public function deleteCap($cap){
        return $this->setVar('delete_cap', $cap);
    }
    
    /**
     * Select by assign_cap
     * 
     * @param mixed $cap
     * @return TaxonomyQuery
     */
    public function assignCap($cap){
        return $this->setVar('assign_cap', $cap);
    }

}
