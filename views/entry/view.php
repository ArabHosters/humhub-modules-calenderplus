<div class="panel panel-default">
    <div class="panel-body">
        <h1>
            Event: <?php echo CHtml::encode($CalendarPlusEntry->title); ?>

            <?php if ($CalendarPlusEntry->is_public): ?>
                <span class="label label-success"><?php echo Yii::t('CalendarPlusModule.views_entry_view', 'Public'); ?></span>
            <?php endif; ?>

        </h1>

        <div class="pull-right">

            <?php if ($userCanRespond && !$userAlreadyResponded): ?>
                <?php echo CHtml::link(Yii::t('CalendarPlusModule.views_entry_view', "Attend"), $this->createContainerUrl('/calendar/entry/respond', array('type' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_ACCEPTED, 'id' => $CalendarPlusEntry->id)), array('class' => 'btn btn-success')); ?>
                <?php echo CHtml::link(Yii::t('CalendarPlusModule.views_entry_view', "Maybe"), $this->createContainerUrl('/calendar/entry/respond', array('type' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_MAYBE, 'id' => $CalendarPlusEntry->id)), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link(Yii::t('CalendarPlusModule.views_entry_view', "Decline"), $this->createContainerUrl('/calendar/entry/respond', array('type' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_DECLINED, 'id' => $CalendarPlusEntry->id)), array('class' => 'btn btn-default')); ?>
            <?php endif; ?>

            <?php if ($userAlreadyResponded): ?>
                <?php
                $participationModes = array();
                $participationModes[CalendarPlusEntryParticipant::PARTICIPATION_STATE_ACCEPTED] = Yii::t('CalendarPlusModule.views_entry_view', "I´m attending");
                $participationModes[CalendarPlusEntryParticipant::PARTICIPATION_STATE_MAYBE] = Yii::t('CalendarPlusModule.views_entry_view', "I´m maybe attending");
                $participationModes[CalendarPlusEntryParticipant::PARTICIPATION_STATE_DECLINED] = Yii::t('CalendarPlusModule.views_entry_view', "I´m not attending");
                ?>

                <div class="btn-group">
                    <button type="button" class="btn btn-success"><?php echo $participationModes[$CalendarPlusEntryParticipant->participation_state]; ?></button>
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        unset($participationModes[$CalendarPlusEntryParticipant->participation_state]);
                        ?>

                        <?php foreach ($participationModes as $participationMode => $title): ?>
                            <li><?php echo CHtml::link($title, $this->createContainerUrl('/calendar/entry/respond', array('type' => $participationMode, 'id' => $CalendarPlusEntry->id)), array('class' => '')); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div>
                <br />
                <?php if ($CalendarPlusEntry->content->canWrite()) : ?>
                    <?php echo HHtml::link(Yii::t('CalendarPlusModule.views_entry_view', 'Edit this event'), '#', array('class' => 'btn btn-primary btn-sm', 'onclick' => 'openEditModal(' . $CalendarPlusEntry->id . ')')); ?>
                <?php endif; ?>
            </div>
            <br />            
        </div>

        <?php $this->widget('application.modules.calendarplus.widgets.CalendarPlusEntryDateWidget', array('CalendarPlusEntry'=>$CalendarPlusEntry)); ?>
        
        <br /><br />

        <?php echo Yii::t('CalendarPlusModule.views_entry_view', 'Created by:'); ?> <strong><?php echo HHtml::link($CalendarPlusEntry->content->user->displayName, $CalendarPlusEntry->content->user->getUrl()); ?></strong><br />

        <?php $this->widget('application.modules.calendarplus.widgets.CalendarPlusEntryParticipantsWidget', array('CalendarPlusEntry'=>$CalendarPlusEntry)); ?>
        
        <br />

        <?php $this->beginWidget('CMarkdown'); ?><?php echo nl2br(CHtml::encode($CalendarPlusEntry->description)); ?><?php $this->endWidget(); ?>


        <hr>
        <!-- <a href="#">Download ICal</a> &middot; -->
        <?php $this->widget('application.modules_core.like.widgets.LikeLinkWidget', array('object' => $CalendarPlusEntry)); ?> &middot;
        <?php $this->widget('application.modules_core.comment.widgets.CommentLinkWidget', array('object' => $CalendarPlusEntry)); ?>
        <?php $this->widget('application.modules_core.comment.widgets.CommentsWidget', array('object' => $CalendarPlusEntry)); ?>


    </div>

</div>

<script>
    function openEditModal(id) {
        var editUrl = '<?php echo Yii::app()->getController()->createContainerUrl('entry/edit', array('id' => '-id-')); ?>';
        editUrl = editUrl.replace('-id-', encodeURIComponent(id));
        $('#globalModal').modal({
            show: 'true',
            remote: editUrl
        });
    }
</script>    
