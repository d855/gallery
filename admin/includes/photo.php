<?php 

class Photo extends DbObject

{
	protected static $table = 'photos';
	protected static $table_fields = array('title', 'description', 'filename', 'type', 'size');
	public $id;
	public $title;
	public $description;
	public $filename;
	public $type;
	public $size = 0;	

	public $tmp_path;
	public $upload_directory = 'images';
	public $custom_errors = array();
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



































}