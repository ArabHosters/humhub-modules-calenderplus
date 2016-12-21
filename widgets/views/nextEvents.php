<div class="panel">

    <div class="panel-heading"><?php echo Yii::t('CalendarPlusModule.widgets_views_nextEvents', '<strong>Upcoming</strong> events '); ?></div>
    <div class="panel-body">

        <?php foreach ($calendarEntries as $CalendarPlusEntry) : ?>
            <strong><?php echo CHtml::link($CalendarPlusEntry->title,$CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/view', array('id'=>$CalendarPlusEntry->id))); ?></strong><br />
            <?php $this->widget('application.modules.calendarplus.widgets.CalendarPlusEntryDateWidget', array('CalendarPlusEntry'=>$CalendarPlusEntry)); ?><br />
            <br/>
        <?php endforeach; ?>
    </div>

</div>

