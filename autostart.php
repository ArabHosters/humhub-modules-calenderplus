<?php

Yii::app()->moduleManager->register(array(
    'id' => 'calendarplus',
    'class' => 'application.modules.calendarplus.CalendarPlusModule',
    'import' => array(
        'application.modules.calendarplus.*',
        'application.modules.calendarplus.models.*',
        'application.modules.calendarplus.notifications.*',
    ),
    'events' => array(
        array('class' => 'SpaceMenuWidget', 'event' => 'onInit', 'callback' => array('CalendarPlusModuleEvents', 'onSpaceMenuInit')),
        array('class' => 'ProfileMenuWidget', 'event' => 'onInit', 'callback' => array('CalendarPlusModuleEvents', 'onProfileMenuInit')),
        array('class' => 'SpaceSidebarWidget', 'event' => 'onInit', 'callback' => array('CalendarPlusModuleEvents', 'onSpaceSidebarInit')),
        array('class' => 'ProfileSidebarWidget', 'event' => 'onInit', 'callback' => array('CalendarPlusModuleEvents', 'onProfileSidebarInit')),
        array('class' => 'DashboardSidebarWidget', 'event' => 'onInit', 'callback' => array('CalendarPlusModuleEvents', 'onDashboardSidebarInit')),
        array('class' => 'TopMenuWidget', 'event' => 'onInit', 'callback' => array('CalendarPlusModuleEvents', 'onTopMenuInit')),
    ),
));
?>