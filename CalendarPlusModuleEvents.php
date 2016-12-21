<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

/**
 * Description of CalendarEvents
 *
 * @author luke
 */
class CalendarPlusModuleEvents
{

    public static function onTopMenuInit($event)
    {
        if (Yii::app()->user->isGuest) {
            return;
        }

        $user = Yii::app()->user->getModel();
        if ($user->isModuleEnabled('calendarplus')) {
            $event->sender->addItem(array(
                'label' => Yii::t('CalendarPlusModule.base', 'Calendar'),
                'url' => Yii::app()->createUrl('//calendarplus/global/index', array('uguid' => Yii::app()->user->guid)),
                'icon' => '<i class="fa fa-calendar"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'calendarplus' && Yii::app()->controller->id == 'global'),
                'sortOrder' => 300,
            ));
        }
    }

    public static function onSpaceMenuInit($event)
    {
        $space = Yii::app()->getController()->getSpace();

        if ($space->isModuleEnabled('calendarplus')) {

            $event->sender->addItem(array(
                'label' => Yii::t('CalendarPlusModule.base', 'Calendar'),
                'group' => 'modules',
                'url' => Yii::app()->createUrl('//calendarplus/view/index', array('sguid' => $space->guid)),
                'icon' => '<i class="fa fa-calendar"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'calendar'),
            ));
        }
    }

    public static function onProfileMenuInit($event)
    {
               
        $user = Yii::app()->getController()->getUser();

        // Is Module enabled on this workspace?
        if ($user->isModuleEnabled('calendarplus')) {
            $event->sender->addItem(array(
                'label' => Yii::t('CalendarPlusModule.base', 'Calendar'),
                'url' => Yii::app()->createUrl('//calendarplus/view/index', array('uguid' => $user->guid)),
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'calendar'),
            ));
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        
        if (Yii::app()->user->isGuest) {
            return;
        }
                
        $space = null;

        if (isset(Yii::app()->params['currentSpace'])) {
            $space = Yii::app()->params['currentSpace'];
        }

        if (Yii::app()->getController() instanceof ContentContainerController && Yii::app()->getController()->contentContainer instanceof Space) {
            $space = Yii::app()->getController()->contentContainer;
        }

        if ($space != null) {
            if ($space->isModuleEnabled('calendarplus')) {
                $event->sender->addWidget('application.modules.calendarplus.widgets.NextEventsSidebarWidget', array('contentContainer' => $space), array('sortOrder' => 550));
            }
        }
    }

    public static function onDashboardSidebarInit($event)
    {
        if (Yii::app()->user->isGuest) {
            return;
        }

        $user = Yii::app()->user->getModel();
        if ($user->isModuleEnabled('calendarplus')) {
            $event->sender->addWidget('application.modules.calendarplus.widgets.NextEventsSidebarWidget', array(), array('sortOrder' => 550));
        }
    }

    public static function onProfileSidebarInit($event)
    {
        
        if (Yii::app()->user->isGuest) {
            return;
        }
                
        $user = null;

        if (isset(Yii::app()->params['currentUser'])) {
            $user = Yii::app()->params['currentUser'];
        }

        if (Yii::app()->getController() instanceof ContentContainerController && Yii::app()->getController()->contentContainer instanceof User) {
            $user = Yii::app()->getController()->contentContainer;
        }

        if ($user != null) {
            if ($user->isModuleEnabled('calendarplus')) {
                $event->sender->addWidget('application.modules.calendarplus.widgets.NextEventsSidebarWidget', array('contentContainer' => $user), array('sortOrder' => 550));
            }
        }
    }

}
