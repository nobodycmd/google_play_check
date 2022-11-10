<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>\<?= $appID ?>\modules\<?= $versions ?>;

/**
 * Class Module
 * @package addons\<?= $model->name;?>\<?= $appID ?>\modules\<?= $versions ?>
 * @author Rf <1458015476@qq.com>
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'addons\<?= $model->name;?>\<?= $appID ?>\modules\<?= $versions ?>\controllers';

    public function init()
    {
        parent::init();
    }
}