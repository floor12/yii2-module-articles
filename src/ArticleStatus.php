<?php


namespace floor12\articles;

use yii2mod\enum\helpers\BaseEnum;

class ArticleStatus extends BaseEnum
{
    const ACTIVE = 0;
    const DISABLE = 1;

    public static $list = [
        self::ACTIVE => 'Active',
        self::DISABLE => 'Disabled',
    ];
}
