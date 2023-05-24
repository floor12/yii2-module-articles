<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 10.04.2018
 * Time: 21:17
 */

namespace floor12\articles;

use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\pages\models\Page;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * @var Module
     */
    public $module;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->module = Yii::$app->getModule('articles');
        $this->layout = $this->layout;
        parent::init();
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['form', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$this->module->editRole],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['delete'],
                ],
            ],
        ];
    }


    public function actionIndex(Page $page)
    {
        $model = new ArticleFilter();
        $model->page_id = $page->id;
        $model->load(Yii::$app->request->get());
        return $this->render($this->module->viewIndex, ['model' => $model, 'page' => $page]);
    }

    public function actionView(Page $page, $key)
    {
        $model = Article::find()->where(['slug' => $key])->andFilterWhere(['page_id' => $page->id])->one();
        if (!$model)
            throw new NotFoundHttpException('Новость не найдена.');

        if (!$this->module->adminMode() && $model->status == ArticleStatus::DISABLE)
            throw new NotFoundHttpException('Новость не найдена.');

        Yii::$app
            ->metamaster
            ->setTitle($model->title)
            ->setDescription($model->description_seo ? $model->description_seo : "")
            ->setImage(
                !empty($model->images) ? $model->images[0]->getHref() : "",
                !empty($model->images) ? $model->images[0]->getRootPath() : ""
            )
            ->register($this->getView());


        return $this->render($this->module->viewView, ['model' => $model]);
    }

    public function actions()
    {
        return [
            'form' => [
                'class' => EditModalAction::class,
                'model' => Article::class,
                'view' => $this->module->viewForm,
                'logic' => ArticleUpdate::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Article::class,
                'message' => 'Объект удален',
            ]
        ];
    }

}
