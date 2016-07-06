<?php
/**
 * Created by PhpStorm.
 * User: dev30
 * Date: 7/6/16
 * Time: 9:50 AM
 */

namespace app\commands;


use yii\console\Controller;
use \Yii;

class UsersController extends Controller
{
    public function actionPermissions()
    {
        $auth = Yii::$app->authManager;

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update a user';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete a user';
        $auth->add($deleteUser);
    }

    public function actionRoles()
    {
        $auth = Yii::$app->authManager;

        $updateUser = $auth->getPermission('updateUser');
        $deleteUser = $auth->getPermission(('deleteUser'));

        $user = $auth->createRole("user");
        $auth->add($user);

        $editor = $auth->createRole('editor');
        $auth->add($editor);
        $auth->addChild($editor, $updateUser);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $editor);

    }

    public function actionUpdateRoles()
    {
        $auth = Yii::$app->authManager;
        $user = $auth->getRole('user');
        $editor = $auth->getRole('editor');
        $auth->addChild($editor, $user);
    }

//    public function actionRules()
//    {
//        $auth = Yii::$app->authManager;
//
//        $rule = new ProfileRule();
//        $auth->add($rule);
//
//        $updateMonster = $auth->getPermission('updateMonster');
//        $updateMonster->ruleName = $rule->name;
//        $auth->update('updateMonster', $updateMonster);
//    }
}