<?php 

class User extends DbObject
{
	protected static $table = 'users';
	protected static $table_fields = array('username', 'password', 'first_name', 'last_name', 'user_image');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $user_image;
	public $upload_directory = 'images';
	public $image_placeholder = 'https://placehold.it/400x400&text=image';


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

	public function imagePlaceholder()
	{
		return empty($this->user_image) ? $this->image_placeholder : $this->upload_directory . DS . $this->user_image;
	}

	public function setFile($file)
	{
		if(empty($file) || !$file || !is_array($file)){
			$this->errors[] = 'No files uploaded.';
			return false;
		}elseif($file['error'] !=0){
			$this->errors[] = $this->upload_errors_array[$file['error']];
			return false;
		}else {
			$this->filename = basename($file['name']);
			$this->tmp_path = $file['tmp_name'];
			$this->type = $file['type'];
			$this->size = $file['size'];

		}
	}

	public function saveUserImage()
	{

		if(!empty($this->errors)){
			return false;
		}

		if(empty($this->user_image) || empty($this->tmp_path)){
			$this->errors[] = 'The file is not available.';
			return false;
		}

		$target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->user_image;

		if(file_exists($target_path)){
			$this->errors[] = 'The file "'.$this->user_image.'" already exists';
		}

		if(move_uploaded_file($this->tmp_path, $target_path)){
				unset($this->tmp_path);
				return true;
		}else{
			$this->errors[] = 'Some error occurred';
			return false;
		}
		
	}















}