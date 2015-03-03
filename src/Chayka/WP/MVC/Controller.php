<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 05.12.14
 * Time: 14:40
 */

namespace Chayka\WP\MVC;

use Chayka\Helpers\HttpHeaderHelper;
use Chayka\MVC;
use Chayka\WP;
use Chayka\WP\Helpers\AngularHelper;
use Chayka\WP\Helpers\ResourceHelper;
use Chayka\WP\Models\PostModel;

class Controller extends MVC\Controller{

    protected $appUrl;

    /**
     * Controller constructor
     *
     * @param $appPath
     */
    public function __construct($appPath){
        parent::__construct($appPath);
        $this->appUrl = preg_replace('%^.*wp-content%', '/wp-content', $appPath);
    }

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

    /**
     * Enqueue script. Utilizes wp_enqueue_script().
     * However if detects registered minimized and concatenated version enqueue it instead.
     *
     * @param $handle
     * @param string|bool $resRelativeSrc
     * @param array $dependencies
     * @param string|bool $ver
     * @param bool $in_footer
     */
    public function enqueueScript($handle, $resRelativeSrc = false, $dependencies = array(), $ver = false, $in_footer = false){
	    $src = false;
	    if($resRelativeSrc) {
		    $src = $this->appUrl . 'res/' . $resRelativeSrc;
	    }
        ResourceHelper::enqueueScript($handle, $src, $dependencies, $ver, $in_footer);
    }

	/**
	 * @param $handle
	 * @param bool $resRelativeSrc
	 * @param array $dependencies
	 * @param bool $ver
	 * @param bool $in_footer
	 */
	public function enqueueNgScript($handle, $resRelativeSrc = false, $dependencies = array(), $ver = false, $in_footer = false){
		$src = false;
		if($resRelativeSrc) {
			$src = $this->appUrl . 'res/' . $resRelativeSrc;
		}
		AngularHelper::enqueueScript($handle, $src, $dependencies, $ver, $in_footer);
	}

    /**
     * Enqueue style. Utilizes wp_enqueue_style().
     * However if detects registered minimized and concatenated version enqueue it instead.
     *
     * @param $handle
     * @param string|bool $resRelativeSrc
     * @param array $dependencies
     * @param string|bool $ver
     * @param bool $in_footer
     */
    public function enqueueStyle($handle, $resRelativeSrc = false, $dependencies = array(), $ver = false, $in_footer = false) {
	    $src = false;
	    if($resRelativeSrc) {
		    $src = $this->appUrl . 'res/' . $resRelativeSrc;
	    }
        ResourceHelper::enqueueStyle($handle, $src, $dependencies, $ver, $in_footer);
    }

    /**
     * Enqueue both script and style with the same $handle.
     * Uses minimized versions if detects.
     *
     * @param string $handle
     */
    public function enqueueScriptStyle($handle) {
        ResourceHelper::enqueueScriptStyle($handle);
    }

	/**
	 * @param $handle
	 */
	public function enqueueNgScriptStyle($handle) {
		AngularHelper::enqueueScriptStyle($handle);
	}

    /**
     * This helper load post for the action.
     * It checks if $id and $slug are valid, redirects to valid url otherwise.
     * If $id or $slug is omitted then loads by what is given and no consistency check is performed.
     * Assigns post for major $wp_query as current (for all those headers to work right).
     * And optionally increases post reviews count.
     *
     * @param $id
     * @param $slug
     * @param bool $incReviews
     * @return PostModel|null
     */
    public function loadActionPost($id = null, $slug = null, $incReviews = true){
        $post = $id?PostModel::selectById($id):null;
        if($post && $post->getId()){
            if($slug && urldecode($post->getSlug()) != $slug){
                HttpHeaderHelper::redirect($post->getHref(), 301);
            }
        }else if($slug){
            $post = PostModel::selectBySlug($slug);
            if($post && $post->getId() && $id && $post->getId() != $id){
                HttpHeaderHelper::redirect($post->getHref(), 301);
            }
        }

        if($post){
            if($incReviews){
                $post->incReviewsCount();
            }
            $this->setPost($post);
        }else{
            WP\Query::setIs404(true);
        }

        return $post;
    }

    /**
     * Setup pagination by WP_Query
     *
     * @param \WP_Query $wpQuery
     * @return $this
     */
    public function setupPaginationByWpQuery($wpQuery = null){
        if(!$wpQuery){
            $wpQuery = PostModel::getWpQuery();
        }
        return $this->getPagination()
            ->setCurrentPage($wpQuery->get('paged'))
            ->setTotalPages($wpQuery->max_num_pages);
    }

    /**
     * Set WP page template
     *
     * @param $template
     */
    public function setWpTemplate($template){
        WP\Query::setTemplate($template);
    }

}