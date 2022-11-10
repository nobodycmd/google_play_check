<?php

namespace api\modules\v1\controllers\member;

use api\controllers\UserAuthController;
use common\models\member\Invoice;

/**
 * 发票管理
 *
 * Class InvoiceController
 * @package api\modules\v1\controllers\member
 * @author Rf <1458015476@qq.com>
 */
class InvoiceController extends UserAuthController
{
    /**
     * @var Invoice
     */
    public $modelClass = Invoice::class;
}