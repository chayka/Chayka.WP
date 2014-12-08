<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 05.12.14
 * Time: 14:40
 */

namespace Chayka\WP\MVC;

use Chayka\MVC;
use Chayka\WP;

class Controller extends MVC\Controller{

    /**
     * This function should be used to set post data,
     * that will be use later in Chayka\WP\Query and Chayka\WP|Helpers\HtmlHelper.
     * Long story short, data from the post provide will be used for html title, keywords, description.
     *
     * @param $post
     */
    public function setPost($post){
        WP\Helpers\HtmlHelper::setPost($post);
        WP\Query::setPost($post);
    }

    /**
     * Set html title.
     * Use Chayka\WP|Helpers\HtmlHelper::getHeadTitle() to fetch it in your template.
     *
     * @param $title
     */
    public function setTitle($title){
        WP\Helpers\HtmlHelper::setHeadTitle($title);
        WP\Query::getPost()->setTitle($title);
    }

    /**
     * Set html description.
     * Use Chayka\WP|Helpers\HtmlHelper::getMetaDescription() to fetch it in your template.
     *
     * @param $description
     */
    public function setDescription($description){
        WP\Helpers\HtmlHelper::setMetaDescription($description);
    }

    /**
     * Set html keywords.
     * Use Chayka\WP|Helpers\HtmlHelper::getMetaDescription() to fetch it in your template.
     *
     * @param $keywords
     */
    public function setKeywords($keywords){
        WP\Helpers\HtmlHelper::setMetaKeywords($keywords);
    }
}