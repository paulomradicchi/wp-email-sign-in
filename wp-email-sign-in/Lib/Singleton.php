<?php
namespace WPEmailSignIn\Lib;

class Singleton {
    
    protected static $_instances = array();
    
    protected function __construct() {
        $this->_init();
    }
    
    protected function _init() {}

    protected function __clone() {}

    public function __wakeup() {
        throw new \Exception('Cannot unserialize singleton');
    }

    public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class]) || !self::$_instances[$class] instanceof Physalia_Mapper_Abstract) {
            self::$_instances[$class] = new $class;
        }

        return self::$_instances[$class];
    }
}