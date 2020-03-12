<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 24.10.2016
 * Time: 20:22
 *
 * @var Articles $model
 *
 */

use floor12\articles\Articles;
use floor12\files\components\FileInputWidget;
use floor12\summernote\Summernote;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'articles-form',
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);

if (Yii::$app->request->get('parent_id'))
    $model->parent_id = intval(Yii::$app->request->get('parent_id'));


if (is_numeric($model->publish_date))
    $model->publish_date = date("d.m.Y", $model->publish_date);

?>
<div class="modal-header">
    <h2><?= $model->isNewRecord ? "Добавление новости" : "Редактирование новости"; ?></h2>
</div>
<div class="modal-body">
    <?= $form->errorSummary($model); ?>


    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'title_seo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'description_seo')->textarea(['style' => 'height: 108px;']) ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'slug')->textInput() ?>

            <div class="row">
                <div class="col-xs-6">
                    <?= $form->field($model, 'publish_date', ['enableClientValidation' => false])->widget(DatePicker::className(), [
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pickerButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]); ?>
                </div>
                <div class="col-xs-6">
                    <?= $form->field($model, 'status')->checkbox() ?>
                    <?= $form->field($model, 'index_page')->checkbox() ?>
                </div>
            </div>

        </div>


    </div>

    <?= $form->field($model, 'announce')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'body')->widget(Summernote::className(), []) ?>

    <?= $form->field($model, 'images')->widget(FileInputWidget::className(), []) ?>

</div>

<div class="modal-footer">
    <?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable pull-left']) ?>
    <?php if (!$model->isNewRecord) {
        echo Html::submitButton('Сохранить и перейти к следующей', ['class' => 'btn btn-primary', 'name' => 'showNextOnSuccess', 'value' =>
            1]);
    } ?>
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
