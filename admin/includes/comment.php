<?php 

class Comment extends DbObject
{
	protected static $table = 'comments';
	protected static $table_fields = array('photo_id', 'author', 'body');
	public $id;
	public $photo_id;
	public $author;
	public $body;


	public static function createComment($photo_id, $author, $body)
	{
		if(!empty($photo_id) && !empty($author) && !empty($body)){
			$comment = new Comment();

			$comment->photo_id = $photo_id;
			$comment->author = $author;
			$comment->body = $body;


			return $comment;
		}else {
			return false;
		}
	}

	public static function findComments($photo_id)
	{	
		global $database;	
		$sql = 'select * from '.self::$table.' where photo_id = "' .$database->escapeString($photo_id).'" order by id asc';

		return self::findQuery($sql);
	}















}