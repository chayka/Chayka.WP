<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Models;

/**
 * Class MenuModel implements menu wrapper model.
 *
 * @package Chayka\WP\Models
 */
class MenuModel{

    /**
     * Menu ID
     *
     * @var integer
     */
    protected $id;

    /**
     * Menu title
     *
     * @var string
     */
    protected $title;

    /**
     * Menu UL classes
     *
     * @var array(string)
     */
    protected $classes;

    /**
     * Menu items
     *
     * @var array(MenuItemModel)
     */
    protected $items;

    /**
     * Flag that is true when one of menu items is current
     *
     * @var null
     */
    protected $isCurrent = null;


    /**
     * Get menu id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set menu id
     *
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get menu title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set menu title
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get menu UL classes
     *
     * @return array(string)
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * Set menu UL classes
     *
     * @param array(string) $classes
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }

    /**
     * Add class to UL classes
     *
     * @param $cls
     */
    public function addClass($cls){
        if(!in_array($cls, $this->classes)){
            $this->classes[]=$cls;
        }
    }

    /**
     * Remove class from UL classes
     *
     * @param $cls
     */
    public function removeClass($cls){
        $pos = array_search($cls, $this->classes);
        if($pos!==false){
            unset($this->classes[$pos]);
        }
    }

    /**
     * Get menu items
     *
     * @return array(MenuItemModel)
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Set menu items
     *
     * @param array(MenuItemModel) $items
     */
    public function setItems($items) {
        $this->items = $items;
    }

    /**
     * Add menu item
     *
     * @param MenuItemModel $item
     */
    public function addItem($item){
        if($item->getId()){
            $this->items[$item->getId()] = $item;
        }else{
            $this->items[] = $item;
        }
    }

    /**
     * Remove menu item by id
     *
     * @param integer $itemId
     */
    public function removeItem($itemId){
        unset($this->items[$itemId]);
    }

    /**
     * Set current flag
     *
     * @param bool $value
     */
    public function setIsCurrent($value){
        $this->isCurrent = $value;
    }

    /**
     * Check if menu is current
     *
     * @return bool|null
     */
    public function isCurrent(){
        if($this->isCurrent === null){
            $this->isCurrent = false;
            foreach($this->items as $item){
                if($item->isCurrent() || $item->isCurrentParent()){
                    $this->isCurrent = true;
                    break;
                }
            }
        }
        
        return $this->isCurrent;
    }

}

/**
 * Class MenuItemModel implements menu item wrapper model
 *
 * @package Chayka\WP\Models
 */
class MenuItemModel{

    /**
     * Menu item id
     *
     * @var integer
     */
    protected $id;

    /**
     * Menu item title
     *
     * @var string
     */
    protected $title;

    /**
     * Menu item link
     *
     * @var string
     */
    protected $href;

    /**
     * Menu item css classes
     *
     * @var array(sting)
     */
    protected $classes;

    /**
     * Sub menu
     *
     * @var MenuItemModel
     */
    protected $subMenu;

    /**
     * Flag that is true if this menu item corresponds to current page
     *
     * @var bool|null
     */
    protected $isCurrent = null;

    /**
     * Flag that is true if this item has sub items
     *
     * @var bool|null
     */
    protected $isParent = null;

    /**
     * Get menu item id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set menu item id
     *
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get menu item title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set menu item title
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get menu item link
     *
     * @return string
     */
    public function getHref() {
        return $this->href;
    }

    /**
     * Set menu item link
     *
     * @param string $href
     */
    public function setHref($href) {
        $this->href = $href;
    }

    /**
     * Get menu item css classes
     *
     * @return array
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * Set menu item css classes
     *
     * @param $classes
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }

    /**
     * Add css class to menu item
     *
     * @param $cls
     */
    public function addClass($cls){
        if(!in_array($cls, $this->classes)){
            $this->classes[]=$cls;
        }
    }

    /**
     * Remove css class from menu item
     *
     * @param $cls
     */
    public function removeClass($cls){
        $pos = array_search($cls, $this->classes);
        if($pos!==false){
            unset($this->classes[$pos]);
        }
    }

    /**
     * Get sub menu
     *
     * @return MenuItemModel
     */
    public function getSubMenu() {
        return $this->subMenu;
    }

    /**
     * Set sub menu
     *
     * @param $subMenu
     */
    public function setSubMenu($subMenu) {
        $this->subMenu = $subMenu;
    }

    /**
     * Set $isCurrent flag
     *
     * @param $value
     */
    public function setIsCurrent($value){
        $this->isCurrent = $value;
    }

    /**
     * Set $isParent flag
     *
     * @param $value
     */
    public function setIsParent($value){
        $this->isParent = $value;
    }

    /**
     * Check is this item is current
     *
     * @return bool|null
     */
    public function isCurrent(){
        if($this->isCurrent === null){
            $this->isCurrent = false;
            $uri = $_SERVER['REQUEST_URI'];
            $x = strlen($uri)-1;
            $uri = '/' == $uri[$x]?substr($uri, 0, $x):$uri;
            $url = $this->getHref();
            $x = strlen($url)-1;
            $url = '/' == $url[$x]?substr($url, 0, $x):$url;

            $this->isCurrent = $uri == $url;
        }
        
        return $this->isCurrent;
    }

    /**
     * Check if this item is parent
     *
     * @return bool|null
     */
    public function isParent(){
        if($this->isParent === null){
            $this->isParent = 0;
            if($this->getSubMenu()->isCurrent()){
                $this->isParent = true;
            }else{
                $uri = $_SERVER['REQUEST_URI'];
                $x = strlen($uri)-1;
                $uri = '/' == $uri[$x]?substr($uri, 0, $x):$uri;
                $url = $this->getHref();
                $x = strlen($url)-1;
                $url = '/' == $url[$x]?substr($url, 0, $x):$url;
                $cmp = strpos($uri, $url);

                $this->isParent = $cmp!==false && $cmp == 0 && $uri != $url ;
            }
        }
        
        return $this->isParent;
    }

}