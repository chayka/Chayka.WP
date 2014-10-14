<?php

namespace Chayka\WP\Models;

class MenuModel{
    protected $id;
    protected $title;
    protected $classes;
    protected $items;
    protected $isCurrent = null;


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function setClasses($classes) {
        $this->classes = $classes;
    }
    
    public function addClass($cls){
        if(!in_array($cls, $this->classes)){
            $this->classes[]=$cls;
        }
    }
    
    public function removeClass($cls){
        $pos = array_search($cls, $this->classes);
        if($pos!==false){
            unset($this->classes[$pos]);
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }
    
    public function addItem($item){
        if($item->getId()){
            $this->items[$item->getId()] = $item;
        }else{
            $this->items[] = $item;
        }
    }
    
    public function removeItem($itemId){
        unset($this->items[$itemId]);
    }
    
    public function setIsCurrent($value){
        $this->isCurrent = $value;
    }
    
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

class MenuItemModel{
    protected $id;
    protected $title;
    protected $href;
    protected $classes;
    protected $subMenu;
    protected $isCurrent = null;
    protected $isParent = null;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getHref() {
        return $this->href;
    }

    public function setHref($href) {
        $this->href = $href;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function setClasses($classes) {
        $this->classes = $classes;
    }
    
    public function addClass($cls){
        if(!in_array($cls, $this->classes)){
            $this->classes[]=$cls;
        }
    }
    
    public function removeClass($cls){
        $pos = array_search($cls, $this->classes);
        if($pos!==false){
            unset($this->classes[$pos]);
        }
    }

    public function getSubMenu() {
        return $this->subMenu;
    }

    public function setSubMenu($subMenu) {
        $this->subMenu = $subMenu;
    }
    
    public function setIsCurrent($value){
        $this->isCurrent = $value;
    }
    
    public function setIsCurrentParent($value){
        $this->isParent = $value;
    }
    
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

    public function isCurrentParent(){
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