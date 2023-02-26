<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>
use uzzielpelawak\modules\UserManagement\models\User;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    <?= '.'. str_replace(' ', '-',strtolower(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>-container {
        vertical-align: middle !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= "<?= " ?>Html::a(<?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


<?= $generator->enablePjax ? "                    <?php Pjax::begin(); ?>\n" : '' ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "                <?= " ?>GridView::widget([
                        'pjax' => true,
                        'pjaxSettings' => [
                            'neverTimeout' => true,
                        ],
                        'options' => [
                            'id' => '<?= str_replace(' ', '-',strtolower(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>-container'
                        ],
                        'panel' => [
                            'type' => GridView::TYPE_PRIMARY,
                            'heading' => '<span class="fa fa-building"></span>  ' . Html::encode($this->title),
                        ],
                        'containerOptions' => ['style' => 'overflow: auto; font-size: 0.9rem;'],
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                        'toolbar' =>  [
                            'content' => 
                            Html::a('reset filter', ['index'], ['class' => 'btn btn-secondary mr-2'])
                            ,
                        ],
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => false
                        ],
                        'toggleDataOptions' => ['minCount' => 10],
                        'hover' => true,
                        'bordered' => true,
                        'condensed' => true,
                        'responsiveWrap' => false,
                        'dataProvider' => $dataProvider,
                        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n                        'columns' => [\n" : "'columns' => [\n"; ?>
                            ['class' => 'kartik\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "                            '" . $name . "',\n";
        } else {
            echo "                            //'" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "                            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "                            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

                                [
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{view} {update} {delete}',
                                'width' => '150px',
                                'buttons'=>[
                                    'view' => function ($url, $model) {
                                        if(User::hasRole('view<?= StringHelper::basename($generator->modelClass) ?>')) {
                                            return Html::a('<span class="fa fa-eye"></span>', $url, [
                                                'title' => Yii::t('app', 'View'),
                                                'class' => 'btn btn-sm btn-primary',
                                            ]);
                                        } else {
                                            return '';
                                        }
                                    },
                                    'update' => function ($url, $model) {
                                        if(User::hasRole('update<?= StringHelper::basename($generator->modelClass) ?>')) {
                                            return Html::a('<span class="fa fa-edit"></span>', $url, [
                                                'title' => Yii::t('app', 'Update'),
                                                'class' => 'btn btn-sm btn-warning',
                                            ]);
                                        } else {
                                            return '';
                                        }
                                        
                                    },
                                    'delete' => function ($url, $model) {
                                        if(User::hasRole('delete<?= StringHelper::basename($generator->modelClass) ?>')) {
                                            return Html::a('<span class="fa fa-trash"></span>', $url, [
                                                'title' => Yii::t('app', 'Delete'),
                                                'class' => 'btn btn-sm btn-danger',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        } else {
                                            return '';
                                        }
                                    }
                                ],
                            ],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]); ?>
<?php else: ?>
    <?= "               <?= " ?>ListView::widget([
                        'dataProvider' => $dataProvider,
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'itemOptions' => ['class' => 'item'],
                        'itemView' => function ($model, $key, $index, $widget) {
                            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
                        },
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                            'options' => ['class' => 'pagination mt-3'],
                        ]
                    ]) ?>
<?php endif; ?>

<?= $generator->enablePjax ? "                    <?php Pjax::end(); ?>\n" : '' ?>

                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
