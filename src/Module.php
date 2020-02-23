<?php

namespace floor12\articles;

/**
 * pages module definition class
 * @property  string $editRole
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'floor12\articles';

    public $editRole = '@';

    public $layout = '@app/views/layouts/main';

    public $userModel = 'app\models\User';

    public $viewIndex = '@vendor/floor12/yii2-module-articles/src/views/articles/index';
    public $viewIndexListItem = '@vendor/floor12/yii2-module-articles/src/views/articles/_index';
    public $viewView = '@vendor/floor12/yii2-module-articles/src/views/articles/view';
    public $viewForm = '@vendor/floor12/yii2-module-articles/src/views/articles/_form';


    public function init()
    {
        parent::init();
    }

    public function adminMode()
    {
        if ($this->editRole == '@')
            return !\Yii::$app->user->isGuest;
        else
            return \Yii::$app->user->can($this->editRole);
    }
}
