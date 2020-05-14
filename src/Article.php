<?php

namespace floor12\articles;

use common\models\User;
use floor12\files\components\FileBehaviour;
use floor12\files\models\File;
use floor12\pages\interfaces\PageObjectInterface;
use floor12\pages\models\Page;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property int $id_old Старый ID
 * @property int $status Скрыть
 * @property int $created Время создания
 * @property int $updated Время обновления
 * @property int $create_user_id Создал
 * @property int $update_user_id Обновил
 * @property int $page_id Связь со страницей
 * @property string $slug Ключевое слово для URL
 * @property string $title Заголовок новости
 * @property string $title_seo Title страницы
 * @property string $description_seo Meta Description
 * @property string $announce Анонс новости
 * @property string $body Текст новости
 * @property int $publish_date Дата публикации
 * @property string $url Адрес страницы
 * @property bool $index_page Показывать на главной
 * @property bool $poster_in_listing Показывать постер в списке
 * @property bool $poster_in_view Показывать постер при просмотре
 * @property bool $slider Показывать слайдер
 * @property string $tsvector
 * @property string $lang
 *
 * @property User $creator
 * @property User $updater
 * @property File[] $images
 */
class Article extends ActiveRecord implements PageObjectInterface
{
    /** @var string */
    public $title_highlighted;
    /** @var string */
    public $body_highlighted;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['slug', 'trim'],
            [['status', 'created', 'updated', 'create_user_id', 'update_user_id', 'publish_date', 'page_id', 'index_page'], 'integer'],
            [['created', 'updated', 'slug', 'title', 'title_seo', 'publish_date'], 'required'],
            [['announce', 'body'], 'string'],
            [['slug', 'description_seo'], 'string', 'max' => 400],
            [['title', 'title_seo'], 'string', 'max' => 255],
            //[['create_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->getModule('article')->userModel,
            // 'targetAttribute' => ['create_user_id' => 'id']],
            //[['update_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->getModule('article')->userModel,
            // 'targetAttribute' => ['update_user_id' => 'id']],
            ['images', 'file', 'maxFiles' => 10, 'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg'], 'checkExtensionByMimeType' => false],
            ['slug', 'match', 'pattern' => '/^[-a-z0-9]*$/', 'message' => 'Ключ URL может состоять только из латинских букв в нижнем регистре, цифр и дефиса.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Скрыть',
            'created' => 'Время создания',
            'updated' => 'Время обновления',
            'create_user_id' => 'Создал',
            'update_user_id' => 'Обновил',
            'slug' => 'Ключевое слово для URL',
            'title' => 'Заголовок новости',
            'title_seo' => 'Title страницы',
            'description_seo' => 'Meta Description',
            'announce' => 'Анонс новости',
            'body' => 'Текст новости',
            'publish_date' => 'Дата публикации',
            'images' => 'Изображения',
            'index_page' => 'Показывать на главной',
            'poster_in_listing' => 'Показывать постер в списке',
            'poster_in_view' => 'Показывать постер при просмотре',
            'slider' => 'Показывать слайдер'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @inheritdoc
     * @return ArticleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'files' => [
                'class' => FileBehaviour::class,
                'attributes' => [
                    'images'
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $page_path = Page::find()->where(['id' => $this->page_id])->select('path')->scalar();
        return $page_path . '/' . $this->slug . '.html';
    }
}
