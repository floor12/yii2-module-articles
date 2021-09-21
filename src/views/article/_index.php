<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.04.2018
 * Time: 19:31
 *
 * @var $this \yii\web\View
 * @var $model Article
 */

use floor12\articles\Article;
use floor12\articles\ArticleStatus;
use floor12\editmodal\EditModalHelper;
use floor12\files\components\PictureWidget;
use yii\helpers\Html;

?>

<?php if (Yii::$app->getModule('pages')->adminMode()): ?>
    <div class="article-object-admin-block pull-right">
        <?= EditModalHelper::editBtn('/articles/article/form', $model->id) ?>
        <?= EditModalHelper::deleteBtn('/articles/article/delete', $model->id) ?>
    </div>
<?php endif; ?>

<div class="article-index-object <?= $model->status == ArticleStatus::DISABLE ? "object-disabled" : NULL ?>">


    <a href="<?= $model->url ?>" data-pjax="0" class="image">
        <?php if ($model->images): ?>
            <div class="image-wrapper">
                <?= PictureWidget::widget([
                    'model' => $model->images[0],
                    'alt' => $model->title,
                    'classImg' => 'img-responsive author-image-full',
                    'width' => 300,
                ]); ?>
            </div>
        <?php else: ?>
            <div class="no-image"></div>
        <?php endif; ?>
    </a>

    <div class="content">
        <?= Html::a($model->title_highlighted ?: $model->title, $model->url, ['class' => 'article-list-title', 'data-pjax' => '0']) ?>
        <p><?= $model->body_highlighted ?: $model->announce ?></p>
        <time><?= \Yii::$app->formatter->asDate($model->publish_date) ?></time>
    </div>


</div>
