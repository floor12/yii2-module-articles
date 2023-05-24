<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 13.04.2018
 * Time: 19:18
 */

namespace floor12\articles;

use floor12\pages\models\Page;
use Yii;
use yii\base\ErrorException;
use yii\web\IdentityInterface;

class ArticleUpdate
{
    /** @var Article */
    private $model;
    /** @var array */
    private $data;
    /** @var Page */
    private $page;
    /** @var IdentityInterface */
    private $identity;
    /** @var string */
    protected $currentPsqlLanguage = Language::ENGLISH;
    /** @var array */
    protected $psqlLanguages = [
        Language::ENGLISH => 'english',
        Language::RUSSIAN => 'russian',
        Language::TURKISH => 'turkish',
    ];

    /**
     * ArticleUpdate constructor.
     * @param Article $model
     * @param array $data
     * @param IdentityInterface $identity
     */
    public function __construct(Article $model, array $data, IdentityInterface $identity)
    {
        $this->model = $model;
        $this->data = $data;
        $this->identity = $identity;
        $this->page = Page::findOne($this->model->page_id ?: (int)Yii::$app->request->get('page_id'));
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->model->load($this->data);
        $this->updatePageLink();
        $this->setLanguage();
        $this->updateTimetampsAndUser();
        $this->prepareTsVector();
        return $this->model->save();
    }

    protected function setLanguage()
    {
        if (!$this->page)
            return;
        $this->model->lang = $this->page ? $this->page->lang : Language::ENGLISH;

        if (!array_key_exists($this->model->lang, $this->psqlLanguages))
            throw new ErrorException("Language {$language} cannot be submited in search index.");

        $this->currentPsqlLanguage = $this->psqlLanguages[$this->model->lang];
    }

    protected function updatePageLink()
    {
        if ($this->page)
            $this->model->page_id = $this->page->id;
    }

    protected function updateTimetampsAndUser()
    {
        if (!is_numeric($this->model->publish_date))
            $this->model->publish_date = strtotime($this->model->publish_date);

        $this->model->update_user_id = $this->identity->getId();
        $this->model->updated = time();

        if ($this->model->isNewRecord) {
            $this->model->create_user_id = $this->identity->getId();
            $this->model->created = time();
            if (empty($this->model->publish_date))
                $this->model->publish_date = time();
        }
    }

    protected function prepareTsVector()
    {
        $clearedBody = str_replace(['"', "'"], '', strip_tags($this->model->body));
        $sql = "SELECT (
            setweight(to_tsvector('{$this->currentPsqlLanguage}', '{$this->model->title}'),'A') || 
            setweight(to_tsvector('{$this->currentPsqlLanguage}', '{$this->model->announce}'), 'B') ||
            setweight(to_tsvector('{$this->currentPsqlLanguage}', '{$clearedBody}'), 'B')
        )";

        $this->model->tsvector = Yii::$app
            ->db
            ->createCommand($sql)
            ->queryScalar();
    }
}
