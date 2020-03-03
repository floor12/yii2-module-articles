<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 10.04.2018
 * Time: 21:18
 *
 * @var $this \yii\web\View
 * @var $model \floor12\articles\ArticleFilter
 * @var $page Page
 */

use floor12\editmodal\IconHelper;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;


\floor12\editmodal\EditModalAsset::register($this);

$this->registerJsFile('/js/autosubmit.js', ['depends' => 'yii\web\JqueryAsset']);

?>

<?php if (Yii::$app->getModule('pages')->adminMode()): ?>
    <div class="pull-right">
        <a class="btn btn-xs btn-default"
           onclick="showForm('/articles/article/form',{id:0,page_id:<?= $page->id ?>})">
            <?= IconHelper::PLUS ?> добавить объект
        </a>
    </div>
<?php endif; ?>

    <h1><?= $page->title ?></h1>

    <div class="filterBlock">
        <?php $form = ActiveForm::begin([
            'method' => 'GET',
            'options' => [
                'data-container' => '#items',
                'class' => 'autosubmit'
            ],
        ]);
        echo $form->field($model, 'filter')->label(false)->textInput(['placeholder' => 'Введите слова для поиска...']);
        if (Yii::$app->getModule('pages')->adminMode())
            echo $form->field($model, 'showDisabled')->checkbox();
        ActiveForm::end(); ?>
    </div>

<?php Pjax::begin(['id' => 'items']);

echo ListView::widget([
    'dataProvider' => $model->dataProvider(),
    'itemView' => Yii::$app->getModule('articles')->viewIndexListItem,
    'layout' => '{items}{pager}',
]);

Pjax::end();



