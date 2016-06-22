


<?php 
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <button>Submit</button>

<?php ActiveForm::end() ?>

<!--     <form enctype="multipart/form-data" name="imageForm">
                                <input type="file" id="imageFile" name="imageFile" required="" /><br/>
                                <input type="submit" />
                            </form>-->

