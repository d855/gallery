<?php 

class User extends DbObject
{
	protected static $table = 'users';
	protected static $table_fields = array('username', 'password', 'first_name', 'last_name');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;


	public static function verifyUser($username, $password)
	{
		global $database;

		$username = $database->escapeString($username);
		$password = $database->escapeString($password);

		$sql = 'select * from '.self::$table.' where username = "'.$username.'" and password = "'.$password.'" limit 1';

		$result = self::findQuery($sql);


		$res = !empty($result) ? array_shift($result) : false;
		return $res;
	}














}