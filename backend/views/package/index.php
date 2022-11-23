<?php

use common\helpers\Html;
use common\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Watching Packages';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    function sc(t) {
        $(t).attr('b');
    }
</script>

<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-info">
            当前队列数量为<?= count(\common\models\common\Queue::find()->where('1=1')->all()) ?>
            <br>
            系统每隔一段时间 自动 重置队列进行包检查
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">

                    <?php
                    $m1 = new \common\models\common\WatchingPackage([
                            'link_name' => '',
                    ]);
                    $form = yii\bootstrap\ActiveForm::begin([
                            'id' => 'login-form',
                        'action' => Url::to(['search'])
                    ]); ?>

                    <?= $form->field($m1, 'link_name',[
                        //'template'=>"<div>{input}{label}</div>",
                    ])->label('关键字搜索')->textInput([
                        "placeholder" => '多個  $ 進行分割'
                    ]) ?>

                   <?= Html::submitButton('新增关键字搜索') ?>

                    <?php \yii\bootstrap\ActiveForm::end(); ?>
                </h3>

                <div class="box-tools">
                    <?= Html::linkButton(['reset'],'重置队列') ?>
                </div>
            </div>

            <div>
    <?= GridView::widget([
//            'export'=>false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],
            'id',
            [
                    'attribute' => 'name',
                'value'=> function($model){
                    return "<a href='https://play.google.com/store/apps/details?id={$model->package_name}' target='_blank'>$model->name</a>";
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'package_name',
                'value'=> function($model){
                    return "<a href='https://play.google.com/store/apps/details?id={$model->package_name}' target='_blank'>$model->package_name</a>";
                },
                'format' => 'raw',
            ],
//            'link_name',
//            'star',
            'is_down',
//            'check_datetime:datetime',
//            'create_time:datetime',
            [
                'header'=>'新包时间',
                'attribute' => 'update_time',
                'format' => 'html',
            ],
//            'company',
            'download',
//            'contact:html',
            [
                'attribute' => 'contact',
//                'contentOptions' => [
//                    'style' => 'width:100px'
//                ],
//                'headerOptions' => [
//                    'style' => 'width:100px'
//                ],
                'format' => 'html',
//                'value' => function($model){
//        $bhtml = base64_encode($model->contact);
//        $s = "<a b='$bhtml' onclick='sc(this)'>查看联系方式</a>";
//                }
            ],
            [
                'header' => '存活',
                'value' => function($m){
                    $liveTime = '未知';
                    if($m->update_time)
                    {
                        try{
                            $hr = ($m->live_end_time - strtotime(str_replace("","",$m->update_time))) / 3600;
                            $liveTime = $hr.'小时';
                        }catch (\Exception $e){}
                    }else{
                        try{
                            $hr = ($m->live_end_time - $m->create_time) / 3600;
                            $liveTime = $hr.'小时';
                        }catch (\Exception $e){}
                    }

                    return $m->is_down ? $liveTime : '';
                }
            ],
//            'had_notify',
//            [ 'attribute' => 'priority', 'class'=>'kartik\grid\EditableColumn', ],
//            [
//                'attribute' => 'queue_status',
//                'filter' => [
//                        '未入队列','已入队列'
//                ],
//            ],
//            'jobid',
//            [
//                    'header' => '详情',
//                'value' => function($model){
//        $s = "<div id='des{$model->id}' style='display: none'  onclick=\"document.getElementById('des{$model->id}').style.display = 'none'\">{$model->desc}</div>";
//        $s .= "<a onclick=\"document.getElementById('des{$model->id}').style.display = document.getElementById('des{$model->id}').style.display=='none'?'':'none'\">查看详情</a>";
//        return $s;
//                },
//                'format' => 'raw',
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => ' {delete}',
                'buttons' => [
                    'edit' => function($url, $model, $key){
                            return Html::edit(['edit', 'id' => $model->id]);
                    },
                    'delete' => function($url, $model, $key){
                            return Html::delete(['delete', 'id' => $model->id]);
                    },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
