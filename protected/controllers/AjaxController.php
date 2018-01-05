<?php

class AjaxController extends Controller
{
	public $layout = '';

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

    public function actionLoadRecentBooks()
    {
        $offset = Yii::app()->request->getParam('offset',0);
        $q = Yii::app()->request->getParam('q');
        $read = Yii::app()->request->getParam('read', true);
        $books = Book::getRecent($offset, $q, $read);
        $booksLeft = Book::booksLeft($offset + count($books), $q, $read);
        $this->renderPartial('load_recent_books',array('books'=>$books,'load_more'=>($booksLeft > 0), 'read' => $read));
    }

    public function actionSearchHints()
    {
        $q = Yii::app()->request->getParam('q','');
        if (strlen($q) < 2) return '{}';
        $books = Book::getRecent(0, $q);
        $result = array();
        foreach ($books as $book)
        {
            if (mb_stripos($book->author, $q) !== false)
                $result['_author_'.$book->author] = array('title_clear'=>$book->author, 
                	'title'=>preg_replace("#$q#iu", "<b>\\0</b>", $book->author), 'type'=>'Автор');
            if (mb_stripos($book->name, $q) !== false)
                $result['_name_'.$book->name] = array('title_clear'=>$book->name, 
                	'title'=>preg_replace("#$q#iu", "<b>\\0</b>", $book->name), 'type'=>'Книга');
            if (mb_stripos($book->tags, $q) !== false)
            {
                foreach (explode(',', trim($book->tags,',')) as $tag)
                    if (mb_stripos($tag, $q) !== false)
                        $result['_tag_'.$tag] = array('title_clear'=>$tag, 
                        	'title'=>preg_replace("#$q#iu", "<b>\\0</b>", $tag), 'type'=>'Тег');
            }
        }
        echo json_encode($result);
    }

    public function actionTagHints()
    {
    	$q = Yii::app()->request->getParam('q','');
        //if (strlen($q) < 2) return '{}';
        $books = Book::getRecent(0, $q);
        $result = array();
        foreach ($books as $book)
        {
        	if (mb_stripos($book->tags, $q) !== false)
            {
                foreach (explode(',', trim($book->tags,',')) as $tag)
                    if (mb_stripos($tag, $q) !== false)
                        $result['_tag_'.$tag] = array('title_clear'=>$tag, 'title'=>preg_replace("#$q#iu", "<b>\\0</b>", $tag));
            }
        }
        echo json_encode($result);
    }

    public function actionLoadImages()
    {
        $offset = Yii::app()->request->getParam('offset',0);
        $name = urlencode(Yii::app()->request->getParam('name',''));
        $url = 'https://api.datamarket.azure.com/Bing/Search/v1/Image?Query=%27'.$name.'%27&Market=%27ru-RU%27&Adult=%27Off%27&$format=json&$top=4&$skip='.$offset;
        $login = 'c80c2673-f80f-4d51-b05a-7a640747d913';
        $password = 'yxTTN7R9Y+nanCTynPDGT9r5eSFU1/APasXn0hHcDBE';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        $result = curl_exec($ch);
        curl_close($ch);  
        echo $result;
    }

    public function actionUploadCover()
    {
        if (!is_array($_FILES["images"]["error"])) return;
        $allowed_types = array('image/jpeg', 'image/png');

        $key = 0;
        $error = $_FILES["images"]["error"][$key];

        if ($error == UPLOAD_ERR_OK) {
            $type = $_FILES["images"]["type"][$key];
            if (!in_array($type, $allowed_types))
            {
                echo json_encode(array('result'=>'error','description'=>'Unsupported image type'));
                return;
            }
            $tmp_name = $_FILES["images"]["name"][$key];
            $ext = explode('.', $tmp_name);
            $ext = $ext[count($ext)-1];
            $name = sha1(mktime(true) . rand()) . ".$ext";
            $dir_name = $_SERVER['DOCUMENT_ROOT'] . Book::getBaseImagePath();
            if (!is_dir($dir_name))
                mkdir($dir_name);
            if (move_uploaded_file( $_FILES["images"]["tmp_name"][$key], $dir_name . '/' . $name))
            {
                Yii::import('ext.SimpleImage');
                $image = new SimpleImage();
                $image->load($dir_name . '/' . $name);
                if ($image->getWidth() > $image->getHeight() and max($image->getWidth(), $image->getHeight()) > 900)
                {
                    $image->resizeToWidth(900);
                    $image->save($dir_name . '/' . $name);
                }
                elseif (max($image->getWidth(), $image->getHeight()) > 900) {
                    $image->resizeToHeight(900);
                    $image->save($dir_name . '/' . $name);
                }
            }
        }
        $response = array('result' => 'ok', 'name' => $name, 
            'full_path' => Book::getBaseImagePath() . '/' . $name);
        echo json_encode($response);
    }

    public function actionUploadCoverByUrl()
    {
        $allowed_types = array('jpg', 'png');
        $url = Yii::app()->request->getParam('url');
        $ext = explode('.', $url);
        if (count($ext) < 2) {
            echo json_encode(array('result'=>'error','description'=>'Unsupported image type'));
            return;
        }
        $ext = $ext[count($ext)-1];
        if (!in_array(strtolower($ext), $allowed_types))
        {
            echo json_encode(array('result'=>'error','description'=>'Unsupported image type'));
            return;
        }
        $name = sha1(mktime(true) . rand()) . ".$ext";
        $dir_name = $_SERVER['DOCUMENT_ROOT'] . Book::getBaseImagePath();
        if (!is_dir($dir_name))
            mkdir($dir_name);
        $tmp_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/tmp/' . $name;
        file_put_contents($tmp_path, file_get_contents($url));
        if (copy($tmp_path, $dir_name . '/' . $name))
        {
            Yii::import('ext.SimpleImage');
            $image = new SimpleImage();
            $image->load($dir_name . '/' . $name);
            if ($image->getWidth() > $image->getHeight() and max($image->getWidth(), $image->getHeight()) > 900)
            {
                $image->resizeToWidth(900);
                $image->save($dir_name . '/' . $name);
            }
            elseif (max($image->getWidth(), $image->getHeight()) > 900) {
                $image->resizeToHeight(900);
                $image->save($dir_name . '/' . $name);
            }
        }
        $response = array('result' => 'ok', 'name' => $name, 
            'full_path' => Book::getBaseImagePath() . '/' . $name);
        echo json_encode($response);
    }

    public function actionSendMessage()
    {
        $text = Yii::app()->request->getParam('text');
        if (!$text) return;
        $message = new Message;
        $message->id_user_from = Yii::app()->user->innerId;
        $message->id_user_to = 1;
        $message->text = $text;
        $message->save();
        $response = array('result' => 'ok', 'date' => date('H:i d.m.y'));
        echo json_encode($response);
    }

    public function actionNewMessagesCount()
    {
        $id = Yii::app()->user->innerId;
        echo Message::model()->count("id_user_to=$id AND `read`=0");
    }
}