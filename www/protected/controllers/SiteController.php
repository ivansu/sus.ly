<?php

class SiteController extends Controller
{
	private $dictionary = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	/**
	 * Главная страница сайта - формирование коротких ссылок
	 */
	public function actionIndex()
	{
		$url = new ShortUrl();
		$user = Yii::app()->getComponent('user');

		// collect user input data
		if(isset($_POST['ShortUrl']))
		{
			$url->attributes = $_POST['ShortUrl'];

			if($url->validate() && $url->save())
			{
				$user->setFlash(
					'success',
					'Получилось! Теперь вы можете зайти на ту же страницу но по укороченной ссылке: <strong>http://' . $_SERVER['SERVER_NAME'] . '/' . $this->getHashByUrl($url) . '</strong>'
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

		$this->render('index', array('url' => $url));
	}

	/**
	 * Контроллер перекидывающий с укороченных урлов на реальные
	 */
	public function actionParse()
	{
		$redirectTo = '/';

		if ($url = $this->getUrlByHash($_GET['hash']))
		{
			$redirectTo = $url;
		}
		$this->redirect($redirectTo);
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
	 * Преобразование хеша в исходный урл
	 */
	private function getUrlByHash($hash)
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

	/**
	 * Преобразование урла в хеш
	 */
	private function getHashByUrl(ShortUrl $model)
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

		return $out;
	}

}
