<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.04.2018
 * Time: 15:33
 *
 * @var $this \yii\web\View
 * @var $model \floor12\articles\Articles
 */

use floor12\articles\SwiperAsset;
use floor12\editmodal\EditModalHelper;
use floor12\files\assets\LightboxAsset;
use floor12\files\components\FileListWidget;
use floor12\files\components\FilesBlock;
use yii\widgets\Pjax;

LightboxAsset::register($this);

$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->getModule('articles')->adminMode())
    Pjax::begin(['id' => 'items']);

?>
<div class="article-view">
    <?php if (Yii::$app->getModule('pages')->adminMode()): ?>
        <div class="pull-right">
            <?= EditModalHelper::editBtn(['/articles/article/form'], $model->id) ?>
            <?= EditModalHelper::deleteBtn(['/articles/article/delete'], $model->id) ?>
        </div>
    <?php endif; ?>

    <h1><?= $model->title ?></h1>

    <?php if ($model->images): ?>
        <div class="article-image-wrapper">
            <picture>
                <source type="image/webp"
                        srcset="<?= $model->images[0]->getPreviewWebPath('300', 0, true) ?> 1x,
                        <?= $model->images[0]->getPreviewWebPath('600', 0, true) ?> 2x">
                <img alt="<?= $model->title ?>"
                     src="<?= $model->images[0]->getPreviewWebPath('300') ?>"
                     srcset="<?= $model->images[0]->getPreviewWebPath('300') ?> 1x, <?= $model->images[0]->getPreviewWebPath('600') ?> 2x">
            </picture>
        </div>
    <?php endif; ?>

    <?= $model->body ?>

    <?php
    if (sizeof($model->images) > 1):
        echo FileListWidget::widget([
            'files' => $model->images,
            'passFirst' => true,
        ]);
    endif;
    ?>

    <time>
        <?= \Yii::$app->formatter->asDate($model->publish_date) ?>
    </time>

    <?php if (Yii::$app->getModule('articles')->adminMode())
        Pjax::end()
    ?>

</div>
