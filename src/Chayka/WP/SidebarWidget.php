<?php

namespace Chayka\WP;

use Chayka\Helpers\InputHelper;
use Chayka\Helpers\Util;
use Chayka\MVC\View;
use \WP_Widget;

class SidebarWidget extends WP_Widget {

    /**
     * @var array widget arguments
     */
    protected static $args;

    /**
     * Widget constructor
     *
     * @param string $id
     * @param string $name
     * @param string $description
     */
    function __construct($id = 'sidebar-widget', $name = 'Base Widget', $description = "Sidebar Widget") {
        $widget_ops = array(
            'classname' => $id,
            'description' => $description,
        );
        parent::__construct($id, $name, $widget_ops);

        $this->alt_option_name = $id;
    }

    /**
     * This function should be overridden for the app.
     * E.g. return Plugin::getView();
     *
     * @return View
     */
    protected function getView(){
        return new View();
    }

    /**
     * Get instantiated view.
     *
     * @param $instance
     * @return View
     */
    protected function getWidgetView($instance){
        $view = $this->getView();
        $view->declareVars($instance);
        $view->assign('widget', $this);
        return $view;
    }

    /**
     * Get widget arguments
     *
     * @return array
     */
    public static function getArgs(){
        return self::$args;
    }

    /**
     * Get widget argument
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function getArg($key, $default = ''){
        return Util::getItem(self::$args, $key, $default);
    }

    /**
     * Get widget title
     *
     * @return mixed
     */
    public static function getTitle(){
        return self::getArg('title');
    }

    /**
     * Render widget view. Will be rendered using 'views/sidebar/<widget-id>/view.phtml'
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {

        self::$args = $args;

        $beforeWidget = self::getArg('before_widget');
        $beforeTitle = self::getArg('before_title');
        $afterTitle = self::getArg('after_title');
        $afterWidget = self::getArg('after_widget');
        $title = apply_filters( 'widget_title', Util::getItem($instance, 'title') );
        $view = $this->getWidgetView($instance);
        $tpl = sprintf('sidebar/%s/widget.phtml', $this->id_base);
        $content = $view->render($tpl);

        if($content){
            echo $beforeWidget;
            if ( ! empty( $title ) ){
                echo $beforeTitle . $title . $afterTitle;
            }

            echo $content;

            echo $afterWidget;
        }
    }

    /**
     * Render widget form. Will be rendered using 'views/sidebar/<widget-id>/form.phtml'
     *
     * @param array $instance
     * @return string
     */
    public function form($instance) {
        $view = $this->getWidgetView($instance);
        $tpl = sprintf('sidebar/%s/form.phtml', $this->id_base);
        try{
            echo $view->render($tpl);
        }  catch (\Exception $e){
            return parent::form($instance);
        }
    }

    /**
     * In fact this function filters entered data before update
     *
     * @param array $newInstance
     * @param array $oldInstance
     * @return array
     */
    public function update($newInstance, $oldInstance) {

        $instance = $oldInstance;
        foreach($newInstance as $key=>$value){
            $instance[$key] = InputHelper::filter($value, $key);
        }
        $this->flush();

        $allOptions = wp_cache_get('alloptions', 'options');
        if (isset($allOptions[$this->id_base])){
            delete_option($this->id_base);
        }

        return $instance;
    }

    /**
     * Flush widget cache.
     */
    public function flush() {
        wp_cache_delete($this->id_base, 'widget');
    }

    /**
     * Register Widget Class
     */
    public static function registerWidget(){
        $item = new static();
        register_widget(get_class($item));
    }

}
