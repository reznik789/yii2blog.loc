<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p> <?php if (Yii::$app->user->can('updateOwnPost', ['profileId' => $model->author_id ]) ||
            Yii::$app->user->can('editor') ||
            Yii::$app->user->can('admin')): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?php endif;?>
        <?php if (Yii::$app->user->can('admin')): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]); endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'title',
            'anons:ntext',
            'content:ntext',
            [                      // the owner name of the model
                'label' => 'Category',
                'value' => $model->category->title,
            ],
            [                      // the owner name of the model
                'label' => 'Author',
                'value' => $model->author->username,
            ],
            'publish_status',
            'publish_date',
        ],
    ]) ?>

</div>
