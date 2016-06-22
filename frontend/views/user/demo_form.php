<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model,'fname'); ?>


      <?= $form->field($model,'lname'); ?>

     <?= $form->field($model,'email'); ?>

      <?= $form->field($model,'password'); ?>

       <?= $form->field($model,'con_password'); ?>

       <?= $form->field($model,'birth_date'); ?>

        <?= $form->field($model,'gender'); ?>

 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>