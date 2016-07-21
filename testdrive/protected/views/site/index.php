<?php
/* @var $this SiteController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = Yii::app()->name;
?>
<br>
<h1>Comments</h1>
<?php

$attachUrl = Yii::app()->request->getBaseUrl(true) . '/images/head_attach.png';
$header = '<img src="'.$attachUrl.'">';

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'comment-grid',
    'dataProvider'=>$dataProvider,
    'filter' => null,

    'columns' => array(
        array(
            'name' => 'name',
            'htmlOptions' => array('width' => '15%', 'style' => 'height: 70px;')
        ),
        array(
            'name' => 'email',
            'htmlOptions' => array('width' => '20%')
        ),
        array(
            'name' => 'text'
        ),
        array(
            'header' => $header,
            'value' => array($this, 'getFileColumn'),
            'type' => 'raw',
            'htmlOptions' => array('width' => '30px', 'style' => 'text-align: center;')
        ),
        array(
            'name' => 'date_create',
            'htmlOptions' => array('width' => '15%')
        )
    ),
));
?>
<br />
<h1>Add comment</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'enableClientValidation'=>true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <?php echo $form->labelEx($model,'name'); ?>
    <?php echo $form->textField($model,'name'); ?>
    <?php echo $form->error($model,'name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email'); ?>
    <?php echo $form->error($model,'email'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'homepage'); ?>
    <?php echo $form->textField($model,'homepage',array('size'=>60,'maxlength'=>500)); ?>
    <?php echo $form->error($model,'homepage'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'text'); ?>
    <?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'text'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'file'); ?>
    <?php echo $form->fileField($model,'file'); ?>
</div>

<?php if(CCaptcha::checkRequirements()): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'verifyCode'); ?>
        <div>
            <?php $this->widget('CCaptcha'); ?>
            <?php echo $form->textField($model,'verifyCode'); ?>
        </div>
        <div class="hint">Please enter the letters as they are shown in the image above.
            <br/>Letters are not case-sensitive.</div>
        <?php echo $form->error($model,'verifyCode'); ?>
    </div>
<?php endif; ?>

<div class="row buttons">
    <?php echo CHtml::submitButton('Submit'); ?>
</div>

<?php $this->endWidget(); ?>
</div>

<script>
    $(function(){
       $('.fancybox').fancybox();
    });
</script>