<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-10">

            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    $this->widget('application.modules.calendarplus.widgets.FullCalendarWidget', array(
                        'canWrite' => true,
                        'selectors' => $selectors,
                        'filters' => $filters,
                        'loadUrl' => $this->createUrl('loadAjax'),
                        'createUrl' => $this->createUrl('entry/edit', array('uguid' => Yii::app()->user->guid, 'start_time' => '-start-', 'end_time' => '-end-', 'fullCalendar' => '1', 'createFromGlobalCalendar'=>1)),
                    ));
                    ?>

                </div>
            </div>

        </div>
        <div class="col-md-2">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <?php echo Yii::t('CalendarPlusModule.views_global_index', '<strong>Select</strong> calendars'); ?>
                </div>

                <div class="panel-body">

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selector" class="selectorCheckbox" value="<?php echo CalendarPlusEntry::SELECTOR_MINE; ?>" <?php if (in_array(CalendarPlusEntry::SELECTOR_MINE, $selectors)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'My profile'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selector" class="selectorCheckbox"  value="<?php echo CalendarPlusEntry::SELECTOR_SPACES; ?>" <?php if (in_array(CalendarPlusEntry::SELECTOR_SPACES, $selectors)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'My spaces'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selector" class="selectorCheckbox" value="mytasks" <?php if (in_array('mytasks', $selectors)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Tasks'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selector" class="selectorCheckbox" value="holidays" <?php if (in_array('holidays', $selectors)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Holidays'); ?>
                        </label>
                    </div>
                    <br />

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selector" class="selectorCheckbox"  value="<?php echo CalendarPlusEntry::SELECTOR_FOLLOWED_SPACES; ?>" <?php if (in_array(CalendarPlusEntry::SELECTOR_FOLLOWED_SPACES, $selectors)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Followed spaces'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="selector" class="selectorCheckbox"  value="<?php echo CalendarPlusEntry::SELECTOR_FOLLOWED_USERS; ?>" <?php if (in_array(CalendarPlusEntry::SELECTOR_FOLLOWED_USERS, $selectors)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Followed users'); ?>
                        </label>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">

                <div class="panel-heading">
                    <?php echo Yii::t('CalendarPlusModule.views_global_index', '<strong>Filter</strong> Tasks'); ?>
                </div>

                <div class="panel-body">

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox" value="<?php echo Issue::FILTER_SUBSCRIBED; ?>" <?php if (in_array(Issue::FILTER_SUBSCRIBED, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Subscribed'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox" value="<?php echo Issue::FILTER_MINE; ?>" <?php if (in_array(Issue::FILTER_MINE, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'My Tasks'); ?>
                        </label>
                    </div> 
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox"  value="<?php echo Issue::FILTER_PAST; ?>" <?php if (in_array(Issue::FILTER_PAST, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Expired Tasks'); ?>
                        </label>
                    </div>                    
                </div>
            </div>
            
            <div class="panel panel-default">

                <div class="panel-heading">
                    <?php echo Yii::t('CalendarPlusModule.views_global_index', '<strong>Filter</strong> events'); ?>
                </div>

                <div class="panel-body">

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox" value="<?php echo CalendarPlusEntry::FILTER_PARTICIPATE; ?>" <?php if (in_array(CalendarPlusEntry::FILTER_PARTICIPATE, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'I´m attending'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox" value="<?php echo CalendarPlusEntry::FILTER_MINE; ?>" <?php if (in_array(CalendarPlusEntry::FILTER_MINE, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'My events'); ?>
                        </label>
                    </div>                    
                    <br />
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox"  value="<?php echo CalendarPlusEntry::FILTER_NOT_RESPONDED; ?>" <?php if (in_array(CalendarPlusEntry::FILTER_NOT_RESPONDED, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Not responded yet'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter" class="filterCheckbox"  value="<?php echo CalendarPlusEntry::FILTER_RESPONDED; ?>" <?php if (in_array(CalendarPlusEntry::FILTER_RESPONDED, $filters)): ?>checked="checked"<?php endif; ?>>
                            <?php echo Yii::t('CalendarPlusModule.views_global_index', 'Already responded'); ?>
                        </label>
                    </div>                    
                </div>
            </div>
        </div>        

    </div>
</div>


<script>


</script>    