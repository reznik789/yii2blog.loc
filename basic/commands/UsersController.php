<?php
/**
 * Created by PhpStorm.
 * User: dev30
 * Date: 7/6/16
 * Time: 9:50 AM
 */

namespace app\commands;



use app\rbac\ProfileRule;
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
        $auth->addChild($editor, $user);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $editor);

    }


    public function actionRules()
    {
        $auth = Yii::$app->authManager;

        $rule = new ProfileRule();
        $auth->add($rule);

        $updateUser = $auth->getPermission('updateUser');
        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
        $updateOwnProfile->ruleName = $rule->name;
        $auth->add($updateOwnProfile);
        $auth->addChild($updateOwnProfile, $updateUser);
        $user = $auth->getRole('user');
        $auth->addChild($user, $updateOwnProfile);
    }
}