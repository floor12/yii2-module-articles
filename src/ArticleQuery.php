<?php

namespace floor12\articles;

use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\common\models\Article]].
 *
 * @see \common\models\Article
 */
class ArticleQuery extends ActiveQuery
{

    public function search(string $question)
    {
        if (empty($question))
            return $this->andWhere('1=0');

        $tsQuery = new Expression("
            plainto_tsquery('russian', '{$question}') as qRu,
            plainto_tsquery('english', '{$question}') as qEn
        ");

        $highlightedTitleExpression = new Expression("CASE
           WHEN lang = 'ru' THEN
               ts_headline('russian', title, qRu)
           ELSE
               ts_headline('english', title, qEn) 
           END as title_highlighted");

        $highlightedContentExression = new Expression("CASE
           WHEN lang = 'ru' THEN
               ts_headline('russian', body, qRu, 'MaxWords=20, MinWords=10, ShortWord=3, HighlightAll=FALSE,MaxFragments=3')
           ELSE
               ts_headline('english', body, qEn)
           END as body_highlighted");

        $orderExpression = new Expression("ts_rank(tsvector, qRu || qEn) DESC");

        return $this
            ->select(['*', $highlightedTitleExpression, $highlightedContentExression])
            ->from(['article', $tsQuery])
            ->andWhere("tsvector @@ (qRu || qEn)")
            ->orderBy($orderExpression);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => Article::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return Article[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Article|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
