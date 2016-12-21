<?php if ($CalendarPlusEntry->participation_mode != CalendarPlusEntry::PARTICIPATION_MODE_NONE) : ?>
    <?php echo Yii::t('CalendarPlusModule.widgets_views_participants', 'Participants:'); ?><strong>
        <?php
        $title = Yii::t('CalendarPlusModule.widgets_views_participants', ":count attending", array(':count' => $countAttending));
        if ($countAttending > 0) {
            echo HHtml::link($title, $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/userList', array('state' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_ACCEPTED, 'id' => $CalendarPlusEntry->id)), array("class" => "tt", "title" => "", "data-toggle" => "modal", "data-target" => '#globalModal', "data-placement" => "top", "data-original-title" => ""));
        } else {
            echo $title;
        }
        echo " &middot; ";

        $title = Yii::t('CalendarPlusModule.widgets_views_participants', ":count maybe", array(':count' => $countMaybe));
        if ($countMaybe > 0) {
            echo HHtml::link($title, $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/userList', array('state' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_MAYBE, 'id' => $CalendarPlusEntry->id)), array("class" => "tt", "title" => "", "data-toggle" => "modal", "data-target" => '#globalModal', "data-placement" => "top", "data-original-title" => ""));
        } else {
            echo $title;
        }
        echo " &middot; ";

        $title = Yii::t('CalendarPlusModule.widgets_views_participants', ":count declined", array(':count' => $countDeclined));
        if ($countDeclined > 0) {
            echo HHtml::link($title, $CalendarPlusEntry->createContainerUrlTemp('/calendar/entry/userList', array('state' => CalendarPlusEntryParticipant::PARTICIPATION_STATE_DECLINED, 'id' => $CalendarPlusEntry->id)), array("class" => "tt", "title" => "", "data-toggle" => "modal", "data-target" => '#globalModal', "data-placement" => "top", "data-original-title" => ""));
        } else {
            echo $title;
        }
        ?>
    </strong><br/>
<?php endif; ?>
