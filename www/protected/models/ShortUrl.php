<?php

/**
 * Укороченный URL
 */
class ShortUrl extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
 
    public function tableName()
    {
        return 'short_url';
    }

	public function rules()
    {
        return array(
            array('url', 'required', 'message' => 'Пожалуйста, введите URL.'),
            array('url', 'url', 'message' => 'Формат введённого URL неверен.'),
        );
    }
}
