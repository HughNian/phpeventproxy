<?php
/**
 * PHP��eventproxy
 * 
 * @author: hughnian <hugh.nian@163.com>
 * 
 */
class EventProxy
{
  private static $_instance;
	
  private $_events = array();//�����¼���
	
  private $_register = array();//ע���¼�����ֵ
	
  private function __construct(){}
  
  public static function init()
  {
    if(!isset(self::$_instance))
    {
    	$class = __CLASS__;
    	self::$_instance = new $class;	
    }
    return self::$_instance;
  }
  
  public function __clone()
  {
  	trigger_error('Clone is not allow ��', E_USER_ERROR);	
  }
  
  //ע���¼�
  public function registerEvent($eventname=array())
  {
  	if(!is_array($eventname)){
  		trigger_error('Arguments are wrong ��', E_USER_ERROR);
  		return;
  	}
  	$this->_events = $eventname;
  	$count = count($eventname);
  	for($i = 0; $i<$count; $i++){
  		$this->_register[$eventname[$i]] = null;
  	}
  }
  
  public function triggerEvent($eventname, $val)
  {
  	if(!in_array($eventname, $this->_events)){
  		trigger_error('eventname is wrong ��', E_USER_ERROR);
  		return;
  	} else {
  		$this->_register[$eventname] = $val;  	
  	}  	
  }
  
  /**
   * return object
   * 
   */
  public function getDatas()
  {
  	return (object)$this->_register;	
  }
  
  //���ݶ���ע���ʹ����¼�����
  public function __call($funcname,$arguments)
  {
  		$register_funcs = array('create', 'on', 'bind','subscribe');
  		$trigger_funcs = array('trigger', 'emit', 'fire');
  		if(!in_array($funcname, $register_funcs) && !in_array($funcname, $trigger_funcs)) {
  			trigger_error("Method $funcname is not defined ��", E_USER_ERROR);
  			return;
  	  }  	  
  	 	
  	 	if(in_array($funcname, $register_funcs)) {
  	 		if(count($arguments) == 0){
  	 			trigger_error("Method $funcname arguments are wrong ��", E_USER_ERROR);
  				return;
 			  } else {
 			  	$this->registerEvent($arguments);
 			  } 			  
  	 	}
  	 	
  	 	if(in_array($funcname, $trigger_funcs)) {
  	 		if(count($arguments)>2 || count($arguments) <= 1){
  	 			trigger_error("Method $funcname arguments are wrong ��", E_USER_ERROR);
  				return;
 			  } else {
 			  	$this->triggerEvent($arguments[0], $arguments[1]);
 			  } 			  
  	 	}	  
  }
}

/*------- use -------*/
class test
{
	public $event = null;
	
	public function __construct()
	{
		$this->event = EventProxy::init();
		$this->event->create('news', 'users');
	}	
	
	public function input()
	{	
		return $inputs = $this->event->getDatas();
	}
	
	public function getNews()
	{
		$news = "This is News";
		$this->event->trigger('news', $news);
	}
	
	public function getUsers()
	{
		$users = "This is Users";
		$this->event->trigger('users', $users);
	}
}

$test = new test();
$test->getNews();
$test->getUsers();
$ret = $test->input();
echo $ret->news;
