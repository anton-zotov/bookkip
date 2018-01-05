<?php

class HomeController extends Controller
{
	public $layout = 'home';

	public function filters()
    {
        return array(
            'accessControl'
        );
    }

    public function accessRules()
    {
        return array(
            array('deny',
            	'users'=>array('?')
            ),
        );
    }

	public function actionIndex()
	{
        $q = Yii::app()->request->getParam('q');
        $this->pageTitle = "My books - Bookkip";
		$this->render('index', array('q'=>$q));
	}

    public function actionReadLater()
    {
        $this->pageTitle = "Read later - Bookkip";
        $this->render('read_later');
    }

    public function actionAddBook()
    {
        $id = Yii::app()->request->getParam('id');

        $book = false;
        if ($id)
            $book = Book::model()->findByPk($id);

        if (!$book || $book->id_user != Yii::app()->user->innerId)
        {
            $book = new Book;
            
        }
        if (!$book->read_date)
            $book->read_date = date('Y-m-d');

        if (Yii::app()->request->isPostRequest)
        {
            foreach (array('author','name','read_date','rating','comment','tags','cover_pos','cover_pos_percent') as $field)
                $book->$field = Yii::app()->request->getParam($field);
            if ($book->tags)
                $book->tags = ',' . trim($book->tags, ',') . ',';
            if ($book->read_date)
            	$book->read_date = Common::EnDate($book->read_date);
            if (Yii::app()->request->getParam('cover'))
                $book->cover = Yii::app()->request->getParam('cover');
            $book->id_user = Yii::app()->user->innerId;
            if ($book->save())
                $this->redirect('/home');
        }
        $book->tags = trim($book->tags, ',');
        $authors = Book::getUsersAuthors(Yii::app()->user->innerId);
        $this->pageTitle = "Add book - Bookkip";
        $this->render('add_book', array('book'=>$book, 'authors' => $authors));
    }

    public function actionAddBookToReadLater()
    {
        $book = new Book;
        if (Yii::app()->request->isPostRequest)
        {
            foreach (array('author','name','cover_pos','cover_pos_percent') as $field)
                $book->$field = Yii::app()->request->getParam($field);
            if (Yii::app()->request->getParam('cover'))
                $book->cover = Yii::app()->request->getParam('cover');
            $book->id_user = Yii::app()->user->innerId;
            if ($book->save())
                $this->redirect('/home/readlater');
        }
        $authors = Book::getUsersAuthors(Yii::app()->user->innerId);
        $this->pageTitle = "Add book - Bookkip";
        $this->render('add_book_to_read_later', array('book'=>$book, 'authors' => $authors));
    }

    public function actionSupport()
    {
        $id = Yii::app()->user->innerId;
        $messages = Message::model()->findAll("id_user_from=$id OR id_user_to=$id");
        Message::model()->updateAll(array('read' => 1),"id_user_to=$id AND `read`=0");
        $this->pageTitle = "Support - Bookkip";
        $this->render('support', array('messages' => $messages, 'id' => $id));
    }
}