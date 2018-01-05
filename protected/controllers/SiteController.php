<?php

class SiteController extends Controller
{
	public $layout = 'login';

	public function actionIndex()
	{
		if (!Yii::app()->user->isGuest) $this->redirect('/home');
		$method = Yii::app()->request->getParam('method');
		$message = Yii::app()->request->getParam('message');
		$fields = Yii::app()->request->getParam('fields', array('name' => '', 'email' => ''));
		
		$messages = array('no_user'=>'Wrong login or password', 'no_fields'=>'Some fields are empty',
			'user_exists'=>'User with this email already exists', 
			'reg_error' => 'Some error occurred');
		if (key_exists($message, $messages)) $message = $messages[$message];
		else $message = '';
		
		$this->pageTitle = "Bookkip - your personal book list";
		$this->render('index', array('message' => $message, 'method' => $method, 'fields' => $fields));
	}

	public function actionLogin()
	{
		$message = '';
		$login = '';
		if (Yii::app()->request->isPostRequest)
		{
			$login = Yii::app()->request->getParam('email');
			$password = Yii::app()->request->getParam('password');

			if ($login && $password)
			{
				$user = new UserIdentity($login, $password);
				if ($user->authenticate())
				{
					Yii::app()->user->login($user, 3600*24*7);
					$this->redirect('/home');
				}
				else $message = 'no_user';
			}
			else $message = 'no_fields';
		}
		$this->redirect('/?'.http_build_query(array('message'=>$message, 'method' => 'login',
			'fields' => array('name' => '', 'email' => $login))));
	}

	public function actionRegister()
	{
		$message = '';
		if (Yii::app()->request->isPostRequest)
		{
			$name = Yii::app()->request->getParam('name');
			$email = Yii::app()->request->getParam('email');
			$password = Yii::app()->request->getParam('password');
			if ($name && $email && $password)
			{
				$user = User::model()->findByAttributes(array('email' => $email));
				if ($user)
					$message = 'user_exists';
				else
				{
					$user = new User;
					$user->name = $name;
					$user->email = $email;
					$user->password = $password;
					if ($user->save())
					{
						$user = new UserIdentity($email, $password);
						if ($user->authenticate())
						{
							Yii::app()->user->login($user, 3600*24*7);
							$this->redirect('/home');
						}
					}
					else $message = 'reg_error';
				}
			}
			else $message = 'no_fields';
		}
		$this->redirect('/?'.http_build_query(array('message'=>$message, 'method' => 'register', 
			'fields' => array('name' => $name, 'email' => $email))));
	}

	public function actionFBLogin()
	{
		$name = Yii::app()->request->getParam('name');
		$email = Yii::app()->request->getParam('email');
		$password = '809323d523l458' . $email;
		if (!$name || !$email) {
			echo 'fail';
			return;
		}

		if (User::model()->findByAttributes(array('email'=>$email)))
		{
			$user = new UserIdentity($email, $password);
			if ($user->authenticate())
			{
				Yii::app()->user->login($user, 3600*24*7);
				echo 'ok';
			}
			else
				echo 'fail';
			return;
		}
		else
		{
			$user = new User;
			$user->name = $name;
			$user->email = $email;
			$user->password = $password;
			if ($user->save())
			{
				$user = new UserIdentity($email, $password);
				if ($user->authenticate())
				{
					Yii::app()->user->login($user, 3600*24*7);
					echo 'ok';
				}
			}
		}
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			//if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			//else
			//	$this->render('error', $error);
		}
	}
}