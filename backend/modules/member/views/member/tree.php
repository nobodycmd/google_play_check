<?php
use jianyan\treegrid\TreeGrid;


TreeGrid::widget([
    'dataProvider' => $dataProvider,
    'keyColumnName' => 'id',
    'parentColumnName' => 'pid',
    'parentRootValue' => '0', //first parentId value
    'pluginOptions' => [
        'initialState' => 'collapsed',
    ],
    'options' => ['class' => 'table table-hover'],
    'columns' => [
        [
            'attribute' => '用户名',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $column) {
                return $model->username . \common\helpers\Html::a(' <i class="icon ion-android-add-circle"></i>', ['ajax-edit', 'pid' => $model['id']], [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]);
            }
        ],
        [
            'label' => '账户金额',
            'filter' => false, //不显示搜索框
            'value' => function ($model) {
                return "剩余：" . $model->account->user_money . '<br>' .
                    "累计：" . $model->account->accumulate_money . '<br>' .
                    "累计消费：" . abs($model->account->consume_money);
            },
            'format' => 'raw',
        ],
        [
            'header' => "操作",
            'class' => 'yii\grid\ActionColumn',
            'template' => '{edit} {status} {delete}',
            'buttons' => [
//                'edit' => function ($url, $model, $key) {
//                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
//                        'data-toggle' => 'modal',
//                        'data-target' => '#ajaxModal',
//                    ]);
//                },
//                'status' => function ($url, $model, $key) {
//                    return Html::status($model->status);
//                },
//                'delete' => function ($url, $model, $key) {
//                    return Html::delete(['delete', 'id' => $model->id]);
//                },
            ],
        ],
    ]
]);