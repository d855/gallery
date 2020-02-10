<?php 

class Photo extends DbObject

{
	protected static $table = 'photos';
	protected static $table_fields = array('title', 'caption', 'description', 'filename', 'alternate_text', 'type', 'size');
	public $id;
	public $title;
	public $caption;
	public $description;
	public $filename;
	public $alternate_text;	
	public $type;
	public $size = 0;

	public $tmp_path;
	public $upload_directory = 'images';
	// public $errors = array();
	// public $upload_errors_array = array(

	// 	UPLOAD_ERR_OK => 'There is no error',
	// 	UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive',
	// 	UPLOAD_ERR_FORM_SIZE => 'The uploaded foile exceeds the MAX_FILE_SIZE directive',
	// 	UPLOAD_ERR_PARTIAL => 'The uploaded file was partially uploaded',
	// 	UPLOAD_ERR_NO_FILE => 'No file was uploaded',
	// 	UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
	// 	UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
	// 	UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
	// );


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

	public function picturePath()
	{
		return $this->upload_directory.DS.$this->filename;
	}

	public function save()
	{
		if($this->id){
			$this->update();
		}else{

			if(!empty($this->errors)){
				return false;
			}

			if(empty($this->filename) || empty($this->tmp_path)){
				$this->errors[] = 'The file is not available.';
				return false;
			}

			$target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;

			if(file_exists($target_path)){
				$this->errors[] = 'The file "'.$this->filename.'" already exists';
			}

			if(move_uploaded_file($this->tmp_path, $target_path)){
				if($this->create()){
					unset($this->tmp_path);
					return true;
				}
			}else{
				$this->errors[] = 'Some error occurred';
				return false;
			}



			// $this->create();
		}
	}

	public function deletePhoto()
	{
		if($this->delete()){
			$target_path = SITE_ROOT. DS . 'admin' . DS . $this->picturePath();

			return unlink($target_path) ? true : false;
		}else {
			return false;
		}
	}

	public static function displaySidebar($photo_id)
	{
		$photo = Photo::findById($photo_id);

		$output = "<a class='thumbnail' href='#'><img width='100' src='{$photo->picturePath()}'";
		$output .= "<p>Filename: {$photo->filename}</p>";
		$output .= "<p>Type: {$photo->type}</p>";
		$output .= "<p>Size: {$photo->size}</p>";

		echo $output;

	}

}