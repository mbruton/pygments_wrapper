<?php

namespace pygments{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class pygmentize extends \adapt\base{
        
        protected $_has_pygmentize = false;
        
        public function __construct(){
            parent::__construct();
        }
        
        
        
    }
    
}

?>