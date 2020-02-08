<?php

class DbObject
{

	
	public $errors = array();
	public $upload_errors_array = array(

		UPLOAD_ERR_OK => 'There is no error',
		UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive',
		UPLOAD_ERR_FORM_SIZE => 'The uploaded foile exceeds the MAX_FILE_SIZE directive',
		UPLOAD_ERR_PARTIAL => 'The uploaded file was partially uploaded',
		UPLOAD_ERR_NO_FILE => 'No file was uploaded',
		UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
		UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
		UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
	);


	public static function findAll()
	{
		return static::findQuery('select * from '.static::$table);
	}

	public static function findById($id)
	{
		$result = static::findQuery('select * from '.static::$table.' where id = ' . $id);


		return !empty($result) ? array_shift($result) : false;	
	}

	public static function findQuery($sql)
	{
		global $database;
		$result = $database->query($sql);
		$object_array = array();

		while($row = mysqli_fetch_array($result)){
			$object_array[] = static::instantation($row);
		}
		return $object_array;
	}

	public static function instantation($record)
	{
		$calling_class = get_called_class();
        $object = new $calling_class;

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
	public function properties()
	{
		$properties = array();

		foreach(static::$table_fields as $field){
			if(property_exists($this, $field)){
				$properties[$field] = $this->$field;
			}
		}
		return $properties;
	}

	public function cleanProperties()
	{
		global $database;

		$clean_properties = array();

		foreach($this->properties() as $key => $value){
			$clean_properties[$key] = $database->escapeString($value);
		}
		return $clean_properties;
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
		public function save()
	{
		return isset($this->id) ? $this->update() : $this->create();
	}

	public function create()
	{
		global $database;

		$properties = $this->cleanProperties();

		$sql = 'insert into '.static::$table.' ('. implode(',', array_keys($properties)) .')';
		$sql .= 'values ("'. implode('","', array_values($properties)) .'")';


		if($database->query($sql)){
			$this->id = $database->insertId();

			return true;
		}else {
			return false;
		}
	}

	public function update()
	{
		global $database;

		$properties = $this->cleanProperties();
		$properties_pairs = array();

		foreach($properties as $key => $value){

			$properties_pairs[] = "{$key}='{$value}'";
		}

		$sql = 'update '.static::$table.' set ' .implode(',', $properties_pairs). ' where id= "'.$database->escapeString($this->id).'"';

		$database->query($sql);

		return (mysqli_affected_rows($database->connection) == 1) ? true : false;

	}

	public function delete()
	{
		global $database;

		$sql = 'delete from '.static::$table.' where id = "'.$database->escapeString($this->id).'"';
		$database->query($sql);
		return (mysqli_affected_rows($database->connection) == 1) ? true : false;
	}

	public static function countAll()
	{
		global $database;
		$sql = 'select count(*) from ' .static::$table;
		$result = $database->query($sql);
		$row = mysqli_fetch_array($result);

		return array_shift($row);
	}


}