<?php if ($CalendarPlusEntry->all_day): ?>
    <?php if ($CalendarPlusEntry->GetDurationDays() > 1): ?>
        <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->start_time, 'long', false); ?> 
        - <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->end_time, 'long', false); ?>
    <?php else: ?>
        <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->start_time, 'long', false); ?>
    <?php endif; ?>
<?php else: ?>
    <?php if ($CalendarPlusEntry->GetDurationDays() > 1): ?>
        <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->start_time, 'long', 'short'); ?>
        - 
        <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->end_time, 'long', 'short'); ?>
    <?php else: ?>
        <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->start_time, 'long', null); ?>

        (<?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->start_time, null, 'short'); ?>
        - 
        <?php echo Yii::app()->dateFormatter->formatDateTime($CalendarPlusEntry->end_time, null, 'short'); ?>)
    <?php endif; ?>
<?php endif; ?>