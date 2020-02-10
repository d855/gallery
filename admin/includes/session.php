<?php

class Session
{
	private $logged_in = false;
	public $user_id;
	public $message;
	public $count;

	public function __construct()
	{
		session_start();
		$this->checkTheLogin();
		$this->checkMessage();
		$this->countVisitor();
	}

	public function countVisitor()
	{
		if(isset($_SESSION['count'])){
			return $this->count = $_SESSION['count']++;
		}else {
			$_SESSION['count'] = 1;
		}
	}

	public function message($msg ='')
	{	
		if(!empty($msg)){
			$_SESSION['message'] = $msg;
		}else {
			return $this->message;
		}
	}

	private function checkMessage()
	{
		if(isset($_SESSION['message'])){
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		}else {
			return $this->message = '';
		}
	}

	public function isSignedIn()
	{
		return $this->logged_in;
	}

	public function login($user)
	{
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_in = true;
		}
	}

	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
	}

	private function checkTheLogin()
	{
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		}else{
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
}


$session = new Session();
$message = $session->message();
