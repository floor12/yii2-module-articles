<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.04.2018
 * Time: 18:48
 */

namespace floor12\articles;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class ArticleFilter extends Model
{

    public $showDisabled = 0;
    public $filter;
    public $page_id;

    private $query;

    public function rules()
    {
        return [
            ['filter', 'string'],
            ['showDisabled', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'showDisabled' => 'отобразить скрытые'
        ];
    }

    public function dataProvider()
    {
        if (!$this->validate())
            throw new BadRequestHttpException('Ошибка валидации модели.' . print_r($this->errors, 1));
        $this->query = Article::find()->where(['page_id' => $this->page_id]);

        if ($this->filter)
            $this->query->search($this->filter);
        else
            $this->query->orderBy('publish_date DESC');


        if (!(Yii::$app->getModule('articles')->adminMode() && $this->showDisabled))
            $this->query->active();

        return new ActiveDataProvider([
            'pagination' => [
                'route' => Yii::$app->request->getPathInfo(),
                'pageSize' => 30,
            ],
            'sort' => ['defaultOrder' => 'publish_date DESC, id DESC'],
            'query' => $this->query,
        ]);
    }

}
