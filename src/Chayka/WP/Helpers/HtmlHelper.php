<?php

namespace Chayka\WP\Helpers;

use Chayka\Helpers;
use Chayka\WP\Models\PostModel;


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
        $pmdesc = $post->getMeta('description');
        self::setMetaDescription($pmdesc?$pmdesc:$post->getExcerpt());
        $terms = $post->loadTerms();
        $pmkw = $post->getMeta('keywords');
        if($pmkw){
            self::setMetaKeywords($pmkw);
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


