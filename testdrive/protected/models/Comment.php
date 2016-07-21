<?php
/**
    ** This is the model class for table "tbl_user".
 */

class Comment extends CActiveRecord
{
    public $maxWidth = 320;
    public $maxHeight = 240;
    public $maxTxtSize;
    public $verifyCode;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_comment';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => 'Name',
            'email' => 'Email',
            'homepage' => 'Homepage',
            'text' => 'Comment',
            'date_create' => 'Date',
            'file' => 'Choose an Image or Text file'
        );
    }

    public function setMaxTxtSize($v)
    {
        $this->maxTxtSize = $v;
    }

    public function getMaxTxtSize()
    {
        return $this->maxTxtSize;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(
                self::HAS_MANY, 'User', 'user_id'
            )
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, email, text, date_create', 'required'),
            array('homepage', 'length', 'max'=>500),
            array('homepage', 'url'),
            array('email', 'email'),
            array('user_id', 'required', 'message' => 'Please login to left the comment'),
            array('file', 'validateFile'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    public function validateFile($attribute, $params)
    {
        if ($this->$attribute) {

            if (!in_array($this->$attribute->extensionName, array('jpg', 'gif', 'png', 'txt'))) {
                $this->addError($attribute, 'File type is incorrect');

            } else {

                if(strpos($this->$attribute->type, 'image') !== false) {
                    $this->file_type = 'image';
                } elseif(strpos($this->$attribute->type, 'text') !== false) {
                    $this->file_type = 'text';
                }

                $images_path = realpath(Yii::app()->basePath . '/../images');
                $filename = $images_path.'/'.$this->$attribute->getName();

                if ($this->file_type == 'text') {

                    $this->setMaxTxtSize(1024 * 100);
                    if ($this->$attribute->size > $this->getMaxTxtSize()) {
                        $this->addError($attribute, 'Txt file cannot be large than 100K');
                    } else {
                        $this->$attribute->saveAs($filename);
                    }

                } elseif ($this->file_type == 'image') {

                    $this->$attribute->saveAs($filename);
                    Yii::app()->image->load($filename)
                        ->thumb($this->maxWidth, $this->maxHeight, true)
                        ->save();
                }
            }
        }
    }


}