<?php

class Action{
    
    public $input;
    public function before(){
        $input = new Input();
        $this->input = $input->parse();
    }
    
    public function after(){
        //echo '为什么';
    }
    
    public function call($actionObj,$action){
        $this->before();
        $actionObj->$action();
        $this->after();
    }
    
    public function display($view,$data=array()){
        extract($data);
        require APP_PATH.'App/View/'.$view;
    }
    
    public function redirect($url){
        echo "<script>location.href='{$url}'</script>";
    }
}

