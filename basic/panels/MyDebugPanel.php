<?php
/**
 * Created by PhpStorm.
 * User: dev30
 * Date: 7/4/16
 * Time: 5:10 PM
 */

namespace app\panels;

use yii\base\Event;
use app\models\User;
//use app\models\Category;
//use app\models\Roles;
//use app\models\Post;
use yii\debug\Panel;

class MyDebugPanel extends Panel
{
    private $_users = [];

    public function init()
    {
        parent::init();
        Event::on(User::className(), User::EVENT_AFTER_FIND, function ($event) {
            $this->_users[] = $event->sender->login . ' was loaded.';
        });
    }


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'My Debug Panel';
    }

    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        $url = $this->getUrl();
        $count = count($this->data);
        return "<div class=\"yii-debug-toolbar__block\">
                    <a href=\"$url\">Users <span class=\"yii-debug-toolbar__label\">$count</span></a></div>";
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return '<ol><li>' . implode('<li>', $this->data) . '</ol>';
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->_users;
    }
}