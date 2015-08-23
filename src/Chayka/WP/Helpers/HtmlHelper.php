<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

use Chayka\Helpers;
use Chayka\WP\Models\PostModel;

/**
 * Class HtmlHelper is a static container for HTML > HEAD > META values.
 * Also contains a set of handy methods for outputting html attrs
 * to hide|show|disable|enable|check|uncheck elements based on provided condition.
 *
 * This class differs from the base one with the setPost($post) method mostly
 * that allows to set all the meta at once.
 *
 * @package Chayka\Helpers
 */
class HtmlHelper extends Helpers\HtmlHelper{
    
    /**
     * Set all the html page title and meta data, based on post information
     *
     * @param int|object|PostModel $post
     */
    public static function setPost($post){
        if(is_object($post)){
            if(!($post instanceof PostModel)){
                $post = PostModel::unpackDbRecord($post);
            }
        }else{
            $post = PostModel::selectById($post);
        }
        
        self::setHeadTitle($post->getTitle());
        $postMetaDescription = $post->getMeta('description');
        self::setMetaDescription($postMetaDescription?$postMetaDescription:$post->getExcerpt());
        $terms = $post->loadTerms();
        $postMetaKeywords = $post->getMeta('keywords');
        if($postMetaKeywords){
            self::setMetaKeywords($postMetaKeywords);
        }else{
            $keywords = array();
            if($terms){
                foreach($terms as $taxonomy=>$ts){
                    $keywords = array_merge($keywords, $ts);
                }
            }
            $keywords = array_unique($keywords);
            self::setMetaKeywords(join(', ', $keywords));
        }
    }

    /**
     * Set sidebar id for the page responded
     *
     * @param $id
     */
    public static function setSidebarId($id){
        self::setMeta('sidebar-id', $id);
    }

    /**
     * Set sidebar id for the page responded
     *
     * @return mixed|string
     */
    public static function getSidebarId(){
        return self::getMeta('sidebar-id');
    }
}
