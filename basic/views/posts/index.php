<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Posts;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
Posts::find()->all();
//$dataProvider = new ActiveDataProvider([
//    'query' => Posts::find()->with('category', 'author')
//]);
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if(!Yii::$app->user->isGuest && !Yii::$app->user->can("editor")) : ?>
       <?=  Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif;?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           // 'id',
            'title',
            'anons:ntext',
            'content:ntext',
            [
                'label' => 'Category',
                'value' => 'category.title',
            ],
            [
                'label' => 'Author',
                'value' => 'author.username',
            ],
            'publish_status',
            'publish_date',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=> [
                    'update' => function($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', "Update"),
                            'aria-label' => Yii::t('yii', "Update"),
                            'data-pjax' => '0',
                        ];
                        return (Yii::$app->user->can('admin') ||Yii::$app->user->can('editor') ||
                            Yii::$app->user->can('updateOwnPost', ['profileId' => $model->author_id ])) ?
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options) : '';
                    },

                    'delete' => function($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', "Delete"),
                            'aria-label' => Yii::t('yii', "Delete"),
                            'data-pjax' => '0',
                            'data-method' => 'post',
                        ];
                        return (Yii::$app->user->can('admin') ||
                            Yii::$app->user->can('updateOwnPost', ['profileId' => $model->author_id ])) ?
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options) : '';
                    }

                ]
            ],
        ],
    ]); ?>
</div>
