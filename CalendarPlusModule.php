<?php

class CalendarPlusModule extends HWebModule
{

    public function behaviors()
    {
        return array(
            'SpaceModuleBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceModuleBehavior',
            ),
            'UserModuleBehavior' => array(
                'class' => 'application.modules_core.user.behaviors.UserModuleBehavior',
            ),
        );
    }

    public function disable()
    {
        if (parent::disable()) {

            foreach (CalendarPlusEntry::model()->findAll() as $entry) {
                $entry->delete();
            }

            return true;
        }

        return false;
    }

    public function getSpaceModuleDescription()
    {
        return Yii::t('CalendarPlusModule.base', 'Adds an event calendar to this space.');
    }

    public function getUserModuleDescription()
    {
        return Yii::t('CalendarPlusModule.base', 'Adds an calendar for private or public events to your profile and mainmenu.');
    }

    public function disableSpaceModule(Space $space)
    {
        foreach (CalendarPlusEntry::model()->contentContainer($space)->findAll() as $entry) {
            $entry->delete();
        }
    }

    public function disableUserModule(User $user)
    {
        foreach (CalendarPlusEntry::model()->contentContainer($user)->findAll() as $entry) {
            $entry->delete();
        }
    }

}
