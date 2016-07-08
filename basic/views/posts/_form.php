<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\Categories;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */
/* @var $form yii\widgets\ActiveForm */

$categories = Categories::find()->all();
$items = ArrayHelper::map($categories,'id','title');
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'anons')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($items, ['prompt'=>'Chose category']) ?>

    <?= $form->field($model, 'publish_status')->dropDownList([ 'draft' => 'Draft', 'publish' => 'Publish', ]) ?>

    <?= $form->field($model, 'publish_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
