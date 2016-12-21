<?php

class CalendarWallEntryWidget extends HWidget
{

    public $CalendarPlusEntry;

    public function run()
    {


        $CalendarPlusEntryParticipant = CalendarPlusEntryParticipant::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'calendar_entry_id' => $this->CalendarPlusEntry->id));


        $this->render('wallEntry', array(
            'CalendarPlusEntry' => $this->CalendarPlusEntry,
            'CalendarPlusEntryParticipant' => $CalendarPlusEntryParticipant,
        ));
    }

}

?>