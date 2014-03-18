<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<div class="page-header">
    <h1>SUS: Simple URL Shortener <small>Простой укротитель ссылок.</small></h1>
</div>
 
<p>
<?
/** @var TbActiveForm $form */

$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'shortenerForm',
        'type' => 'inline',
        'htmlOptions' => array('class' => 'well'),
    )
);

$this->widget('bootstrap.widgets.TbAlert', array(
	'block' => true,
	'fade' => true,
	'closeText' => '&times;', // false equals no close link
	'events' => array(),
	'htmlOptions' => array(),
	'userComponentId' => 'user',
	'alerts' => array( // configurations per alert type
		'success' => array('closeText' => '&times;'),
		'error' => array('block' => false, 'closeText' => '&times;')
	),
));






echo $form->textFieldRow(
    $model,
    'url',
    array(
        'class' => 'input',
		'placeholder' => 'Скопируйте URL сюда'
    )
);
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'type'=>'primary', 'label' => 'Укротить!')
);

$this->endWidget();
unset($form);

?>

