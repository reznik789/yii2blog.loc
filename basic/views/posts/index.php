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
//$dataProvider = new ActiveDataProvider([
//    'query' => Posts::find()->with('category', 'author')
//]);
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
