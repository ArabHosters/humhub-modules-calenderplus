<div class="panel panel-default">
    <div class="panel-body">

        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $CalendarPlusEntry)); ?>


        <div class="pull-right">


            <?php if ($CalendarPlusEntry->canRespond() && !$CalendarPlusEntry->hasResponded()): ?>
                <?php echo CHtml::link(Yii::t('CalendarPlusModule.views_entry_view', "Attend"), $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/respond', array('type' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_ACCEPTED, 'id' => $CalendarPlusEntry->id)), array('class' => 'btn btn-success')); ?>
                <?php echo CHtml::link(Yii::t('CalendarPlusModule.views_entry_view', "Maybe"), $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/respond', array('type' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_MAYBE, 'id' => $CalendarPlusEntry->id)), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link(Yii::t('CalendarPlusModule.views_entry_view', "Decline"), $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/respond', array('type' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_DECLINED, 'id' => $CalendarPlusEntry->id)), array('class' => 'btn btn-default')); ?>
            <?php endif; ?>

            <?php if ($CalendarPlusEntry->hasResponded()): ?>
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
                            <li><?php echo CHtml::link($title, $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/respond', array('type' => $participationMode, 'id' => $CalendarPlusEntry->id)), array('class' => '')); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <br />            
        </div>


        <strong>Event: <?php echo CHtml::encode($CalendarPlusEntry->title); ?></strong><br />
        <?php $this->widget('application.modules.calendarplus.widgets.CalendarPlusEntryDateWidget', array('CalendarPlusEntry' => $CalendarPlusEntry)); ?><br />
        <br />
        <?php $this->widget('application.modules.calendarplus.widgets.CalendarPlusEntryParticipantsWidget', array('CalendarPlusEntry' => $CalendarPlusEntry)); ?><br />

        <?php if ($CalendarPlusEntry->description != ""): ?>
            <?php $this->beginWidget('CMarkdown'); ?><?php echo nl2br(CHtml::encode($CalendarPlusEntry->description)); ?><?php $this->endWidget(); ?>
        <?php endif; ?>

        <?php $this->endContent(); ?>

    </div>
</div>