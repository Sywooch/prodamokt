<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 26.03.17
 * Time: 16:20
 */

use yii\helpers\Html;
use common\models\Board;

/* @var $this yii\web\View */
/* @var $model common\models\Board */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мой кабинет', 'url' => ['cabinet']];
$this->params['breadcrumbs'][] = $this->title;



$is_enabled  = $model->enable;
$is_started  = $model->isStarted() ? 1 : 0;
$is_finished = $model->isFinished() ? 1: 0;
$is_active   = $model->isActive() ? 1 : 0;

$script = <<< JS

var is_enabled = $is_enabled;
var is_started = $is_started;
var is_finished = $is_finished;
var is_active = $is_active;

$(document).ready(function() {

    if (is_enabled)
    {
        $("#open").hide();
        $("#panel").addClass('panel-success');
        $("#heading").html('Активно');
    }
    else
    {
        $("#close").hide();
        $("#panel").addClass('panel-danger');
        $("#heading").html('Закрыто');
    }

    if (is_finished) {
        $("#open").hide();
        $("#close").hide();
        $("#prolong").show();
        $("#heading").html('Завершено');

    }

    if (!is_started) {
        $("#heading").html('Ожидает активации');
    }

});

 function loadimg(idimg)
    {
        $("#MainImg").empty();
        $("#MainImg").html('<img src="'+idimg+'" />');
    }



JS;
$this->registerJs($script, yii\web\View::POS_END);


?>
<div class="board-view">

    <div class="panel" id="panel">
        <div class="panel-heading">
            <h3 class="panel-title text-center" id="heading">Объявление активно</h3>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <p><?php if ($model->isStarted()) { echo 'Опубликовано: <strong>'. Yii::$app->formatter->asDate($model->started_at, "php: d M H:i ").'</strong>'; } else { echo 'Активируется через: <strong>'. intval(($model->started_at-time())/60).'</strong> минут'; }  ?>  </p>
                <p><?php if ($model->isFinished()) { echo 'Истекло'; } else { echo 'Истекает: <strong>'. Yii::$app->formatter->asDate($model->finished_at, "php: d M H:i ").'</strong>'; } ?></p>

            </div>
            <div class="col-md-2">
                <p>Просмотры <span class="glyphicon glyphicon-eye-open"></span> <?=$model->views?> </p>
            </div>
            <div class="col-md-6 text-right">
                <?=Html::a('<span class="glyphicon glyphicon-pencil"></span> Редактировать', ['user/board-update', 'id'=> $model->id], ['class' => 'btn btn-info'])?>
                <?=Html::a('<span class="glyphicon glyphicon-upload"></span> Активировать', ['user/board-on', 'id'=> $model->id], ['class' => 'btn btn-success', 'id' => 'open'])?>
                <?=Html::a('<span class="glyphicon glyphicon-download"></span> Закрыть', ['user/board-off', 'id'=> $model->id], ['class' => 'btn btn-warning', 'id' => 'close'])?>
                <?=Html::a('<span class="glyphicon glyphicon-repeat"></span> Продлить', ['user/board-prolong', 'id'=> $model->id], ['class' => 'btn btn-success', 'id' => 'prolong' , 'style'=>'display:none'])?>
                <?=Html::a('<span class="glyphicon glyphicon-trash"></span> Удалить', ['user/board-delete', 'id'=> $model->id], ['class' => 'btn btn-danger', 'data' => [
                    'confirm' => 'Удалить объявление безвозвратно?',
                    'method' => 'post',
                ],])?>
            </div>
        </div>

    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-5">
            <?php

            if ($model->existImages())
            {
                $image = $model->getImage();
                echo '<div id="MainImg">'.Html::img($image->getUrl('450x'), ['class' => 'img-responsive']).'</div>';

            }
            ?>
        </div>
        <div class="col-md-2">
            <div class="scroll-img-list">
                <?php
                if ($model->existImages())
                {
                    echo '<ul class="list-group list-image">';
                    foreach ($model->getImages() as $img)
                    {
                        $big_img = $img->getUrl('450x');
                        echo '<li class="list-group-item text-center">'.Html::img($img->getUrl('100x100'), [
                                'class' => 'img-rounded',
                                'onclick' => "loadimg('$big_img')"
                            ]).'</li>';

                    }
                    echo '</ul>';
                }

                ?>
            </div>
        </div>
        <div class="col-md-5">
            <?php if ($model->getPrice()) {
                echo '<p class="lead">'.$model->price['name'].': <span class="label label-success">'.$model->price['cost'].'</span></p>';
            } ?>
            <p class="lead"> Продавец: <strong><?=$model->user->username?></strong></p>
            <p class="lead"> Контакты: <span id="showPhone"> 8 <?= $model->user->phone ?></span>

            </p>

            <p class="lead"> <span class="glyphicon glyphicon-map-marker"></span> <?=$model->town->name?>  </p>
            <hr />
            <p><?=nl2br(Html::encode($model->body))?></p>
            <table class="table table-striped table-condensed">
                <?php
                foreach ($properties as $property)
                {
                    if (!empty($model->getValue($property->number)))
                    {
                        ?>
                        <tr>
                            <td><?=$property->name?></td>
                            <td><?=$model->getValue($property->number)?></td>
                        </tr>
                        <?php
                    }

                }
                ?>
            </table>
        </div>
    </div>

</div>
