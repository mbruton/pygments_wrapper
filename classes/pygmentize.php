<?php

namespace pygments_wrapper{
    
    /* Prevent direct access */
    //defined('ADAPT_STARTED') or die;
    
    class pygmentize /*extends \adapt\base*/{
        
        protected $_has_pygmentize = false;
        
        public function __construct(){
            //parent::__construct();
            
            if ($this->execute("which pygmentize") != ""){
                $this->_has_pygmentize = true;
            }
        }
        
        public function list_lexers(){
            $output = array();
            if ($this->_has_pygmentize){
                $raw = $this->execute("pygmentize -L lexers");
                
                $matches = array();
                if (preg_match_all("/^\*\s*([^:]+):/m", $raw, $matches)){
                    foreach($matches[1] as $match){
                        $items = explode(",", $match);
                        foreach($items as $item){
                            $output[] = trim($item);
                        }
                    }
                }
                
                //print $output;
            }
            
            return $output;
        }
        
        public function list_formatters(){
            $output = array();
            if ($this->_has_pygmentize){
                $raw = $this->execute("pygmentize -L formatters");
                
                $matches = array();
                if (preg_match_all("/^\*\s*([^:]+):/m", $raw, $matches)){
                    foreach($matches[1] as $match){
                        $items = explode(",", $match);
                        foreach($items as $item){
                            $output[] = trim($item);
                        }
                    }
                }
                
                //print $output;
            }
            
            return $output;
        }
        
        public function list_filters(){
            $output = array();
            if ($this->_has_pygmentize){
                $raw = $this->execute("pygmentize -L filters");
                
                $matches = array();
                if (preg_match_all("/^\*\s*([^:]+):/m", $raw, $matches)){
                    foreach($matches[1] as $match){
                        $items = explode(",", $match);
                        foreach($items as $item){
                            $output[] = trim($item);
                        }
                    }
                }
                
            }
            
            return $output;
        }
        
        public function highlight_syntax($code, $lexer, $include_line_numbers = false){
            $filename = TEMP_PATH . "pygmentize-" . md5(date("Ymdhis"));
            $fp = fopen($filename, "w");
            if ($fp){
                if ($lexer == "php"){
                    fputs($fp, "<?php\n" . $code . "\n?>");
                }else{
                    fputs($fp, $code);
                }
                fclose($fp);
                
                $output = $this->execute("pygmentize -l {$lexer} -f html -o {$filename} {$filename}");
                
                $code = file_get_contents($filename);
                
                if ($lexer == "php"){
                    $code  = preg_replace("/<span class=\"cp\">&lt;\?php<\/span>/", "", $code);
                    $code  = preg_replace("/\?&gt;/", "", $code);
                }
                
                //print "|||{$code}|||\n";
                //exit(1);
                
                if ($include_line_numbers){
                    $code = str_replace("<div class=\"highlight\"><pre><", "<div class=\"highlight\"><pre>\n<", $code);
                    $code = str_replace("</pre></div>\n", "</pre></div>", $code);
                    
                    $lines = explode("\n", $code);
                    $code = "";
                    
                    for($i = 0; $i < count($lines); $i++){
                        if ($i > 0){
                            $line_number = $i;// + 1;
                            $code .= "<span class=\"line_number\">{$line_number}</span>{$lines[$i]}\n";
                        }else{
                            $code .= "{$lines[$i]}\n";
                        }
                    }
                }
                
                unlink($filename);
                
                return $code;
            }
            
            return $code;
        }
        
        protected function execute($command){
            return `{$command}`;
        }
        
    }
    
}

?>