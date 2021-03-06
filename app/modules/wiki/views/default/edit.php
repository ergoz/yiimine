<?php
$this->breadcrumbs = array(
    $project->title => array('/project/view', 'url' => $project->identifier),
    'Wiki' => array('/wiki/default/pageIndex'),
    'Редактирование записи'
);

$this->menu = array(
    array('label' => $project->title, 'itemOptions' => array('class' => 'nav-header')),
    array('label' => 'Обзор', 'url' => $this->createUrl('/project/view', array('url' => $project->identifier))),
    array('label' => 'Задачи', 'url' => $this->createUrl('/issue')),
    array('label' => 'Wiki', 'url' => $this->createUrl('/wiki/default/pageIndex'), 'itemOptions' => array('class' => 'active')),
    array('label' => 'Файлы', 'url' => $this->createUrl('/file')),
    '---',
    array('label' => 'Создать запись', 'url' => $this->createUrl('create')),
);

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'edit-page-form',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
    )
); ?>
<fieldset>
    <legend><?php echo CHtml::encode($page->getWikiUid()) ?></legend>
    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::activeTextArea($page, 'content') ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::label(Yii::t('wiki', 'Change summary'), CHtml::getIdByName('comment'), array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo CHtml::textField('comment', $comment) ?>
        </div>
    </div>
    <div class="form-actions">
        <?php echo CHtml::submitButton(Yii::t('wiki', 'Save'), array('class' => 'btn btn-primary')); ?>
    </div>
</fieldset>
<?php $this->endWidget(); ?>