<?php

// TODO: implement NlsHelper logic, by now it is dummy
namespace Chayka\WP\Helpers;

class NlsHelper {

    protected static $instance;
    protected static $locale;
    protected static $lang;
    protected static $nlsDir;
    protected static $nlsScriptsDir;
    protected static $nlsJsUrl;
    protected static $nlsJsDir;
    
    /**
     * returns Zend_Translate instance
     * 
     * @return Zend_Translate
     */
    public static function getInstance() {
        if (!self::$instance) {
//            self::$instance = new Zend_Translate('array', array(
//                'yes'=>'yes',
//            ), self::getLang());
        }
        return self::$instance;
    }

    /**
     * Set current language
     * 
     * @param type $lang
     */
    public static function setLang($lang) {
        self::$lang = $lang;
    }
    
    /**
     * Function returns current language
     * 
     * @return string
     */
    public static function getLang() {
        if (!self::$lang) {
//            self::$lang = self::getLocale()->getLanguage();
        }
        return self::$lang;
    }
    
    /**
     * Function returns current locale
     * 
     * @return Zend_Locale
     */
    public static function getLocale() {
        if (!self::$locale) {
//            $locale = new Zend_Locale('en-us');
//            try {
//                $option = OptionHelper::getOption('nlsLanguage', 'auto');
//                if(!$option){
//                    $option = 'auto';
//                }
//                $locale = new Zend_Locale('auto' == $option? Zend_Locale::BROWSER: $option);
//            } catch (Zend_Locale_Exception $e) {
//                $locale = new Zend_Locale('en-us');
//            }
//            self::$locale = $locale;
//            Zend_Registry::set('Zend_Locale', $locale);
//            Util::print_r($locale);
        }
        return self::$locale;
    }
    
    /**
     * This function detects Plugin or Theme that load localization at the present time.
     * Used by load() function
     * 
     * @param string $path
     */
    public static function setCurrentPlugin($path = ''){
//        if(!$path){
//            $bt = debug_backtrace();
//            $file = '';
//            do{
//                $item = current($bt);
//                if(!next($bt) ){
//                    break;
//                }
//                $file = Util::getItem($item, 'file');
//            }while($file && $file == __FILE__);
//            $path = $file;
//        }
//        $dir = WpHelper::getRootDir($path);
//        self::$nlsDir = $dir.'/nls/' ;
//        self::$nlsJsDir = $dir.'/res/js/nls/';
//        $url = WpHelper::getRootDir(plugin_dir_url($dir.'/dummy.php'));
//        self::$nlsJsUrl = preg_replace('%^[\w\d]+\:\/\/[\w\d\.]+%', '',$url.'/res/js/nls/');
    }
    
    /**
     * Set NLS dir for php
     * 
     * @param string $dir
     */
    public static function setNlsDir($dir){
        self::$nlsDir = $dir;
    }
    
    /**
     * Get NLS dir for php
     * 
     */
    public static function getNlsDir(){
        return self::$nlsDir;
    }

    /**
     * Load nls translation for the specified module
     * 
     * @param string $module
     * @param string $nlsDir
     * @param string $pluginDir
     */
    public static function load($module, $nlsDir = null, $pluginDir = null) {
        self::setCurrentPlugin($pluginDir);
        $nlsDir = $nlsDir?$nlsDir:self::getNlsDir();
        $srcLang = $nlsDir . self::getLang() . '/' . $module . '.' .self::getLang() .'.php';
        $srcDefault = $nlsDir . '/_default/' . $module . '._default.php';
        $src = '';
        if(file_exists($srcLang)){
            $src = $srcLang;
        }elseif(file_exists($srcDefault)){
            $src = $srcDefault;
        }
        if($src){
//            self::getInstance()->addTranslation($src, self::getLang());
        }
    }

    /**
     * If you need to use localized .phtml views (templates)
     * you should call updateScriptPath(Zend_View $view) before 
     * $view->render('...') call.
     * In this case localized views will be searched in
     * <plugin_or_theme_dir>/nls/<lng|_default>/views
     * 
     * @param Zend_View $view
     */
    public static function updateScriptPath(Zend_View $view = null, $pluginDir = null) {
        self::setCurrentPlugin($pluginDir);
        $nlsScriptsDir = self::getNlsDir() . self::getLang().'/views';
        if (is_dir($nlsScriptsDir)) {
//            $view->addScriptPath($nlsScriptsDir);
        }
    }
    
    /**
     * Register localization script.
     * Keep in mind that you should add $handle to the dependecy array of the 
     * script that is being localized;
     * 
     * @param type $handle
     * @param type $script
     * @param array $deps
     * @param boolean $ver
     * @param boolean $in_footer
     * @param string $pluginDir
     * @return type
     */
    public static function registerScriptNls($handle, $script, $deps = array(), $ver = false, $in_footer = false, $pluginDir = null){
        self::setCurrentPlugin($pluginDir);
        $nlsScript = FsHelper::setExtensionPrefix($script, self::getLang());
        if(!file_exists(self::$nlsJsDir.$nlsScript)){
            $nlsScript = FsHelper::setExtensionPrefix($script, '_default');
            if(!file_exists(self::$nlsJsDir.$nlsScript)){
                $nlsScript = $script;
                if(!file_exists(self::$nlsJsDir.$nlsScript)){
                    return;
                }
            }
        }
        $src = self::$nlsJsUrl . $nlsScript;
        if(!in_array('nls', $deps)){
            $deps[]='nls';
        }
        
        wp_register_script($handle, $src, $deps, $ver, $in_footer);
    }

    /**
     * Get localized value, or value itself if localiztion is not found
     * This function can get multiple args and work like sprintf($template, $arg1, ... $argN)
     * Hint: Use $format = 'На %2$s сидят %1$d обезьян';
     * 
     * @param string $value String to translate
     * @return string
     */
    public static function _($value) {
        return $value;
//        if(func_num_args()>1){
//            $args = func_get_args();
//            $args[0] = self::getInstance()->_($value);
//            return call_user_func_array('sprintf', $args);
//        }
//        return self::getInstance()->_($value);
    }
    
    /**
     * Echo localized value, or value itself if localiztion is not found
     * This function can get multiple args and work like sprintf($template, $arg1, ... $argN)
     * Hint: Use $format = 'На %2$s сидят %1$d обезьян';
     * 
     * @param string $value String to translate
     * @return string
     */
    public static function __($value){
        if(func_num_args()>1){
            $args = func_get_args();
//            $args[0] = self::getInstance()->_($value);
            echo $res = call_user_func_array('sprintf', $args);
            return $res;
        }
//        echo $res = self::getInstance()->_($value);
//        return $res;
        echo $value;
        return $value;
    }

}