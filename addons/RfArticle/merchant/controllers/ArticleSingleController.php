<?php

namespace addons\RfArticle\merchant\controllers;

use Yii;
use common\traits\MerchantCurd;
use addons\RfArticle\common\models\ArticleSingle;

/**
 * 单页管理
 *
 * Class ArticleSingleController
 * @package addons\RfArticle\merchant\controllers
 * @author Rf <1458015476@qq.com>
 */
class ArticleSingleController extends BaseController
{
    use MerchantCurd;

    /**
     * @var ArticleSingle
     */
    public $modelClass = ArticleSingle::class;
}