<?php
class YzmPHP 
{
    //框架的运行方法
    public function run(){
        $this->init_config();
        spl_autoload_register(array($this,'load'));
        set_error_handler(array($this,'AppError'));
        set_exception_handler(array($this,'AppException'));
        if(isset($_SERVER['REQUEST_URI'];)){
            $url = $_SERVER['REQUEST_URI'];
            $_arr = explode('/', $url);
            $action = ucfirst($_arr[1]).'Action';
            if($url=='/'){
                $action = 'IndexAction';
            }
        }
        //如果有这个控制器参数，说明是命令行模式执行项目
        if(isset($_REQUEST['mod'])){
            $action = ucfirst($_REQUEST['mod']).Action;
        }
        $actionObj = new $action;
        $objClass = isset($_arr[2])?$_arr[2]:'index';

        //如果有这个方法参数，说明是命令行模式执行项目
        if(isset($_REQUEST['action'])){
            $objClass = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';
        }
        $actionObj->call($actionObj,$objClass);
        
    }
    
    private function load($className){
        $data = self::core_file();
        if(isset($data[$className])){
            $path = $data[$className];
        }else if(strpos($className, 'Action')!=false){
            $_str= str_replace('Action', '', $className);
            $path = APP_PATH."/App/Action/{$_str}.action.php";
        }else if(strpos($className, 'Model')!=false){
            $_str= str_replace('Model', '', $className);
            $path = APP_PATH."/App/Model/{$_str}.model.php";
        }else{
            throw new Exception("没有找到对应的{$className}");
        }
        require $path;
    }
    
    public function AppError($errno, $errstr ,$errfile, $errline){
        $error = '错误时间【'.date('Y-m-d H:i:s').'】';
        $error .= '错误号:'.$errno."\t\t";
        $error .= '错误信息:'.$errstr.'<br>';
        $error .= '所在的文件'.$errfile.'<br>';
        $error .= '所在的行数:第'.$errline.'行'."\t\t";
        $error .= '请求的地址:'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].PHP_EOL;
        file_put_contents('Log/'.date('Y-m-d').'_error.log', $error,FILE_APPEND);
        if(APP_DEBUG==true){
            die($error);
        }
        exit;
    }
    
    public function AppException($e){
        file_put_contents('Log/'.date('Y-m-d').'_error.log', $e->__toString().PHP_EOL,FILE_APPEND);
        if(APP_DEBUG==true){
            die($e->__toString());
        }
        exit;
    }
    
    
    private function init_config(){
        $gloabal = APP_PATH.'App/Util/include/global.php';
        require $gloabal;
        $path = APP_PATH.'Config/config.php';
        if(!file_exists($path)){
            die('配置文件不存在！');
        }
        require $path;
        if(isset($config['mysql'])){
            extract($config['mysql']);
            define('MYSQL_HOST', $host);
            define('MYSQL_DB',$dbname);
            define('MYSQL_USER',$mysql_user);
            define('MYSQL_PWD',$mysql_pwd);
        }
        
        if(isset($config['mem'])){
            extract($config['mem']);
            define('MEM_HOST', $host);
            define('MEM_PORT', $port);
        }
        
        if(isset($config['redis'])){
            extract($config['redis']);
            define('REDIS_HOST', $host);
            define('REDIS_PORT', $port);
        }
        
    }
    
    public static function core_file(){
        $_arr = array(
            'Action'=>Lib.'/core/action.class.php',
            'ActionMiddleware'=>APP_PATH."App/Util/ActionMiddleware.php",
            'Input'=>Lib.'/core/Input.php',
            'YzmDbPdo'=> Lib.'/core/YzmDbPdo.php',
            'Model'=>Lib.'/core/Model.php',
            'MmCache'=>Lib.'/core/MmCache.php',
            'YzmRedis'=>Lib.'/core/YzmRedis.php'
        );
        return $_arr;
    }
}


