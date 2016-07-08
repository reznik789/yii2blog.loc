<?php
/**
 * Created by PhpStorm.
 * User: dev30
 * Date: 7/6/16
 * Time: 12:23 PM
 */

namespace app\rbac;

use app\models\Posts;
use yii\rbac\Rule;
use yii\rbac\Item;

class CreatePostRule extends Rule
{
    public $name = 'canCreate';

    /**
     * @param string|integer $user   the user ID.
     * @param Item           $item   the role or permission that this rule is associated with
     * @param array          $params parameters passed to ManagerInterface::checkAccess().
     *
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['authorId']) ? Posts::find()->where(["author_id" => $params['authorId']])->count() <= 5 : false;
    }
}