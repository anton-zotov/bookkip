<?php

class Book extends CActiveRecord
{
	public function tableName()
	{
		return 'book';
	}

	public function rules()
	{
		return array(
			array('id_user, author, name', 'required'),
			array('id_user', 'numerical', 'integerOnly'=>true),
			array('author', 'length', 'max'=>100),
			array('name', 'length', 'max'=>300),
			array('read_date', 'safe'),

			array('id, id_user, author, name, create_date, read_date', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	public static function getUsersAuthors($id_user)
	{
		$books = self::model()->findAll(array(
			'condition'=>'id_user='.intval($id_user),
		    'select'=>'author',
		    'group'=>'author',
		    'distinct'=>true,
		));
		$authors = array();
		foreach ($books as $book)
			$authors[] = $book->author;
		return $authors;
	}

	public static function getBaseImagePath()
	{
		return "/uploads/covers/user_" . Yii::app()->user->innerId;
	}

	public function getCover()
	{
		$dir_name = $_SERVER['DOCUMENT_ROOT'] . self::getBaseImagePath();
		if (!$this->cover || !file_exists($dir_name . '/' . $this->cover)) return "";
		return self::getBaseImagePath() . '/' . $this->cover;
	}

	public static function getRecent($offset=0, $search_string, $read=true)
	{
		$limit = 8;
		if ($offset) $limit = 9;
		$c = self::booksLeft($offset, $search_string, $read);
		$read_cond = ' AND `read_date` IS ' . ($read ? 'NOT' : '') . ' NULL';
		if ($c == ($limit + 1))
			$limit++;
		return self::model()->findAll(array(
			'condition' => 'id_user=:id_user AND (author LIKE :q OR name LIKE :q OR tags LIKE :q)' . $read_cond,
			'params' => array(':id_user'=>intval(Yii::app()->user->innerId),
				':q'=>"%$search_string%"),
			'limit'=>$limit,
			'offset'=>$offset,
			'order'=>'id desc'
		));
	}

	// public static function getCount()
	// {
	// 	return self::model()->count('id_user='.intval(Yii::app()->user->innerId));
	// }

	public static function booksLeft($offset=0, $search_string, $read=true)
	{
		$read_cond = ' AND `read_date` IS ' . ($read ? 'NOT' : '') . ' NULL';
		$c = self::model()->count(array(
			'condition' => 'id_user=:id_user AND (author LIKE :q OR name LIKE :q OR tags LIKE :q)' . $read_cond,
			'params' => array(':id_user'=>intval(Yii::app()->user->innerId),
				':q'=>"%$search_string%"),
		));
		return ($c - $offset > 0 ? $c - $offset : 0);
	}

	public function getReadDate()
	{
		return Common::RuDate($this->read_date);
	}

	public function getCoverOffset()
	{
		Yii::import('ext.SimpleImage');
		$image = new SimpleImage();
		$dir_name = $_SERVER['DOCUMENT_ROOT'] . self::getBaseImagePath();
		try {
			$image->load($dir_name . '/' . $this->cover);
		} catch (Exception $e) {
			return 0;
		}
		//return $this->cover_pos_percent.'px';
		return -300 / $image->getWidth() * $image->getHeight() * $this->cover_pos_percent.'px';
	}
}
