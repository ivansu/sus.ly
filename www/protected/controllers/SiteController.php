<?php

class SiteController extends Controller
{
	private $dictionary = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$model = new ShortUrl();
		$user = Yii::app()->getComponent('user');

		// collect user input data
		if(isset($_POST['ShortUrl']))
		{
			$model->attributes=$_POST['ShortUrl'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->save())
			{
				$user->setFlash(
					'success',
					'Получилось! Теперь вы можете зайти на ту же страницу но по укороченной ссылке: <strong>' . $this->getShortUrl($model) . '</strong>'
				);
			}
			else
			{
				$user->setFlash(
					'error',
					'<strong>Ошибка!</strong> Проверьте правильность заполнения поля URL.'
				);
			}
		}


		$this->render('index', array('model'=>$model));
	}

	/**
	 * Контроллер перекидывающий с укороченных урлов на реальные
	 */
	public function actionParse()
	{
		$redirectTo = '/';

		if ($sourceUrl = $this->getLongUrl($_GET['hash']))
		{
			$redirectTo = $sourceUrl;
		}
		$this->redirect($redirectTo);
	}

	private function getLongUrl($hash)
	{
		if (empty($hash))
		{
			throw new InvalidArgumentException();
		}

		$in = $hash;
		$out = '';
		$base = strlen($this->dictionary);

		$len = strlen($in) - 1;

		for ($t = $len; $t >= 0; $t--) {
		  $bcp = bcpow($base, $len - $t);
		  $out = $out + strpos($this->dictionary, substr($in, $t, 1)) * $bcp;
		}

		$shortUrl = ShortUrl::model()->findByPk($out);

		return $shortUrl ? $shortUrl->url : false;
	}

	private function getShortUrl(ShortUrl $model)
	{
		if (empty($model->id))
		{
			throw new InvalidArgumentException();		
		}

		$in = $model->id;		
		$out =   '';
		$base = strlen($this->dictionary);

		for ($t = floor(log($in, $base)); $t >= 0; $t--) {
		  $bcp = bcpow($base, $t);
		  $a   = floor($in / $bcp) % $base;
		  $out = $out . substr($this->dictionary, $a, 1);
		  $in  = $in - ($a * $bcp);
		}

		return Yii::app()->params['baseUrl'] . $out;
	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
