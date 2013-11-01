##介绍

基于现在比较火热的**nodejs**，以及它优秀的事件驱动模型，根据**eventproxy**模块以**PHP**实现其部分功能，以用于**PHP**开发   
###Now phpeventproxy is development phases###

power by hughnian <hugh.nian@163.com>

```php
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
```