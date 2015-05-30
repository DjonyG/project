<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = [
    'Users' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'Создать пользователя', 'url' => ['create']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление пользователями</h1>

<?php echo CHtml::link('Поиск', '#', ['class' => 'search-button', 'style' => 'font-size:20px']); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', [
        'model' => $model,
    ]); ?>
</div><!-- search-form -->

<?php $this->widget('admin.widgets.AdminGridView', [
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'email',
        [
            'name' => 'date_created',
            'value' => function ($data) {
                /** @var User $data */
                return date('Y-m-d', strtotime($data->date_created));
            },
            'htmlOptions' => [
                'style' => 'white-space: nowrap;',
            ],
        ],
        'date_last',
        [
            'name' => 'ip_create',
            'value' => function ($data) {
                /* @var $data User */
                return long2ip($data->ip_create);
            }
        ],
        [
            'name' => 'ip_last',
            'value' => function ($data) {
                /* @var $data User */
                return long2ip($data->ip_last);
            }
        ],
        [
            'name' => 'role',
            'filter' => CHtml::listData(Yii::app()->authManager->getRoles(), 'name', 'description'),
            'value' => function ($data) {
                /** @var User $data */
                $item = Yii::app()->authManager->getAuthItem($data->role);
                return ($item) ? $item->description : '';
            }
        ],
        [
            'name' => 'email_is_verified',
            'header' => 'EV',
            'value' => function ($data) {
                return $data->email_is_verified ? '+' : '-';
            },
            'htmlOptions' => [
                'style' => 'text-align: center;'
            ],
        ],
        'banned',
        'comment',

        [
            'class' => 'TbButtonColumn',
            'template' => '{view} {update} {delete} {impersonate}',
            'buttons' => [
                'impersonate' => [
                    'label' => 'Войти под этим пользователем',
                    'icon' => 'user',
                    'url' => 'Yii::app()->createUrl("site/impersonate", array("id"=>$data->id))',
                ],
            ],
            'htmlOptions' => [
                'width' => 80,
            ],
        ],
    ],
]); ?>
