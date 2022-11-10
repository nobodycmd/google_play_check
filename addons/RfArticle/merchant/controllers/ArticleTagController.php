<?php

namespace addons\RfArticle\merchant\controllers;

use Yii;
use common\traits\MerchantCurd;
use addons\RfArticle\common\models\ArticleTag;

/**
 * 文章标签
 *
 * Class ArticleTagController
 * @package addons\RfArticle\merchant\controllers
 * @author Rf <1458015476@qq.com>
 */
class ArticleTagController extends BaseController
{
    use MerchantCurd;

    /**
     * @var ArticleTag
     */
    public $modelClass = ArticleTag::class;
}