<?php 

class User 
{
	protected static $table = 'users';
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;



	public static function findUser()
	{
		return self::findQuery('select * from users');
	}

	public static function findUserById($id)
	{
		$result = self::findQuery('select * from users where id = ' . $id);


		return !empty($result) ? array_shift($result) : false;	
	}

	public static function verifyUser($username, $password)
	{
		global $database;

		$username = $database->escapeString($username);
		$password = $database->escapeString($password);

		$sql = 'select * from users where username = "'.$username.'" and password = "'.$password.'" limit 1';

		$result = self::findQuery($sql);


		$res = !empty($result) ? array_shift($result) : false;
		return $res;
	}

	public static function findQuery($sql)
	{
		global $database;
		$result = $database->query($sql);
		$object_array = array();

		while($row = mysqli_fetch_array($result)){
			$object_array[] = self::instantation($row);
		}
		return $object_array;
	}

	public static function instantation($record)
	{
        $object = new self;

        foreach($record as $attribute => $value){
        	if($object->hasAttribute($attribute)){
        		$object->$attribute = $value;
        	}
        }

        return $object;
	}

	private function hasAttribute($attribute)
	{
		$property = get_object_vars($this);

		return array_key_exists($attribute, $property);
	}

	public function create()
	{
		global $database;
		$sql = 'insert into '.self::$table.' (username, password, first_name, last_name)';
		$sql .= 'values ("'.$database->escapeString($this->username).'", 
						"'.$database->escapeString($this->password).'", 
						"'.$database->escapeString($this->first_name).'", 
						"'.$database->escapeString($this->last_name).'")';


		if($database->query($sql)){
			$this->id = $database->insertId();

			return true;
		}else {
			return false;
		}
	}
	public function save()
	{
		return isset($this->id) ? $this->update() : $this->create();
	}

	public function update()
	{
		global $database;
		$sql = 'update '.self::$table.' set username= "'.$database->escapeString($this->username).'",
								password= "'.$database->escapeString($this->password).'",
								first_name= "'.$database->escapeString($this->first_name).'",
								last_name= "'.$database->escapeString($this->last_name).'"
								where id= "'.$database->escapeString($this->id).'"';

		$database->query($sql);

		return (mysqli_affected_rows($database->connection) == 1) ? true : false;

	}

	public function delete()
	{
		global $database;

		$sql = 'delete from '.self::$table.' where id = "'.$database->escapeString($this->id).'"';
		$database->query($sql);
		return (mysqli_affected_rows($database->connection) == 1) ? true : false;

	}














}