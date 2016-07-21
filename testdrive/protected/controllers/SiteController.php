<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.fancybox.pack.js');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.mousewheel-3.0.6.pack.js');
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fancybox.css');

        $model = new Comment();
        if(isset($_POST['Comment']))
        {
            $model->setAttributes($_POST['Comment']);
            $model->setAttribute('user_id', Yii::app()->user->id);
            $model->setAttribute('date_create', new CDbExpression('NOW()'));
            $model->setAttribute('file', CUploadedFile::getInstance($model, 'file'));

            if($model->save()) {
//                $this->redirect(array('index'));
            }
        }

        $sort = new CSort();
        $sort->attributes = array(
            'name', 'email', 'date_create'
        );
        $sort->defaultOrder = 'date_create DESC';

        $dataProvider=new CActiveDataProvider('Comment', array(
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 25
            )
        ));
        $this->render('index',array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
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
			if($model->validate() && $model->login()) {
//                $this->redirect(Yii::app()->user->returnUrl);
            }
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

    public function getFileHeader() {
        $attachUrl = Yii::app()->request->getBaseUrl(true) . '/images/head_attach.png';
        return '<img src="'.$attachUrl.'">';
    }

    public function getFileColumn($data, $row) {

        $content = '';
        $attachUrl = Yii::app()->request->getBaseUrl(true) . '/images/attachment.png';
        $attach = '<img src="'.$attachUrl.'">';

        if ($data->file) {
            $filePath = realpath(Yii::app()->basePath . '/../images') .'/'. $data->file;
            if (file_exists($filePath)) {

                if ($data->file_type == 'text') {

                    $fileContent = file_get_contents($filePath);
                    $content = '<a class="fancybox" href="#inline'.$data->id.'">'.$attach.'</a><div style="display:none;"><div id="inline'.$data->id.'" style="width:600px;height:300px;overflow:auto;">'.$fileContent.'</div>';

                } elseif ($data->file_type == 'image') {

                    $images_url = Yii::app()->request->getBaseUrl(true) . '/images';
                    $fileUrl = $images_url.'/'.$data->file;
                    $content = '<a class="fancybox" href="'.$fileUrl.'">'.$attach.'</a>';
                }
            }
        }
        return $content;
    }
}