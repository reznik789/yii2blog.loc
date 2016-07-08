<?php
/**
 * Created by PhpStorm.
 * User: dev30
 * Date: 7/6/16
 * Time: 9:50 AM
 */

namespace app\commands;



use app\rbac\CreatePostRule;
use app\rbac\PostRule;
use yii\console\Controller;
use \Yii;

class PostsController extends Controller
{
    public function actionPostRule(){
        $auth = Yii::$app->authManager;

        $rule = new PostRule();
        $auth->add($rule);

        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);       
        $user = $auth->getRole('user');
        $auth->addChild($user, $updateOwnPost);
    }

    public function actionCreateRule () {
        $auth = Yii::$app->authManager;
        $rule = new CreatePostRule();
        $auth->add($rule);

        $canCreatePost = $auth->createPermission('canCreatePost');
        $canCreatePost->ruleName = $rule->name;
        $auth->add($canCreatePost);
        $user = $auth->getRole('user');
        $auth->addChild($user, $canCreatePost);
    }
}