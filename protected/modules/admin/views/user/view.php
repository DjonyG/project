<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */


$this->breadcrumbs = array(
    'Users' => array('admin'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Список', 'url' => array('admin')),
    array('label' => 'Создать пользователя', 'url' => array('create')),
    array('label' => 'Редактировать рользователя', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Удалить пользователя', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this account?')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php
$this->widget('booster.widgets.TbDetailView', array(
    'data'       => $model,
    'attributes' => [
        [
            'label' => 'Impersonate',
            'type'  => 'raw',
            'value' => function($data) {
                return Html::link(Html::Image('images/favicon.ico', '', ['width'=>16]),
                    Yii::app()->createUrl('site/impersonate', ['id'=>$data->id]), ['style'=>'margin-right:5px',]);
            }
        ],

        [
            'label' => 'Действия',
            'type' => 'raw',
            'value' =>  Html::link('<i class="glyphicon glyphicon-pencil" title="Update"></i>', Yii::app()->createUrl("admin/user/update", ["id" => $model->id]))
        ],
        'id',
        [
            'name' => 'profileStatus.user_name',
            'value' => function ($data) {
                return isset($data->profileStatus->user_name) ? $data->profileStatus->user_name : null;
            }
        ],
        [
            'label' => 'Пол',
            'value' => function ($data) {
                return isset($data->profileStatus->floor) ? Profile::$floorStatues[$data->profileStatus->floor] : null;
            }
        ],
        [
            'name'=>'email',
            'type'=>'raw',
            'value'=>function($data) {
                return Html::link($data->email, ['user/view', 'id'=>$data->id])   ;
            }
        ],
        [
            'label' => 'Дата рождения',
            'value' => function ($data) {
                return isset($data->profileStatus->date_born) ?
                    date('d-m-Y', strtotime($data->profileStatus->date_born)) : null;
            }
        ],
        'comment',
        [
            'name'  => 'email_is_verified',
            'value' => $model->email_is_verified ? 'Yes' : 'No',
        ],
        [
            'name' => 'date_created',
            'value' => function ($data) {
                /** @var User $data */
                return date('Y-m-d', strtotime($data->date_created));
            },
        ],
        'date_last',
        [
            'name'  => 'ip_create',
            'type'  => 'raw',
            'value' => function () use ($model) {
                $out = long2ip($model->ip_create);
                return $out;
            },
        ],
        [
            'name'  => 'ip_last',
            'type'  => 'raw',
            'value' => function() use ($model) {
                $ip = long2ip($model->ip_last);
                $out = $model->ip_last ? $ip : null;
                return $out;
            },
        ],
        [
            'name'  => 'role',
            'value' => Yii::app()->authManager->getAuthItem($model->role)->description,
        ],

        [
            'name' => 'profileStatus.city',
            'value' => function ($data) {
                return isset($data->profileStatus->city) ? $data->profileStatus->city : null;
            }
        ],

        [
            'name'  => 'banned',
            'value' => isset(User::$banTypes[$model->banned]) ? User::$banTypes[$model->banned] : '',
        ],
    ],
));
?>
