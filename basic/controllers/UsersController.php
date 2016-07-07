<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'only'  => ['update', 'delete'],
//                'rules' => [
//                    [
//                        "allow" => true,
//                        'actions' => ['update'],
//                        'roles' => ['editor']
//                    ],
//                    [
//                        "allow" => true,
//                        'actions' => ['delete'],
//                        'roles' => ['admin']
//                    ]
//            ],
//                'denyCallback' => function($rule, $action) {
//                    if ($action->id == 'delete') {
//                        throw new ForbiddenHttpException('Only administrators can delete users.');
//                    } elseif ($action->id == 'update') {
//                        throw new ForbiddenHttpException('You have\'t permissions to update users.');
//                    } else {
//                        if (Yii::$app->user->isGuest) {
//                            Yii::$app->user->loginRequired();
//                        }
//                    }
//                }
//            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();
        } elseif (!Yii::$app->user->can('admin')){
            throw new ForbiddenHttpException('Permission denied.');
        }
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('updateUser', ['profileId' => $id])) {
            throw new ForbiddenHttpException('Access denied');
        }
        if (!Yii::$app->user->can('admin')){
            return $this->render('ownProfile', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $model->hashPassword = true;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistration()
    {
        $model = new Users();
        $model->hashPassword = true;

        if (Yii::$app->user->isGuest){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('registration', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->actionView(Yii::$app->user->getId());
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updateUser', ['profileId' => $id])) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model = $this->findModel($id);
        
        if(!Yii::$app->user->can('admin')){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('updateOwnProfile', [
                    'model' => $model,
                ]);
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
