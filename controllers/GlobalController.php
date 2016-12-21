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
 * GlobalController provides a global view.
 *
 * @package humhub.modules_core.calendar.controllers
 * @author luke
 */
class GlobalController extends Controller {

    public function beforeAction($action) {
        if (!Yii::app()->user->getModel()->isModuleEnabled('calendarplus')) {
            throw new CHttpException('500', 'Calendar module is not enabled for your user!');
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {

        // Restore users last selectors
        $lastSelectorsJson = Yii::app()->user->getModel()->getSetting('lastSelectors', 'calendar');
        if ($lastSelectorsJson != "") {
            $selectors = CJSON::decode($lastSelectorsJson);
        } else {
            $selectors = array(
                CalendarPlusEntry::SELECTOR_MINE,
                CalendarPlusEntry::SELECTOR_SPACES,
            );
        }

        // Restore users last used filter
        $lastFilterJson = Yii::app()->user->getModel()->getSetting('lastFilters', 'calendar');
        if ($lastFilterJson != "") {
            $filters = CJSON::decode($lastFilterJson);
        } else {
            $filters = array();
        }

        $this->render('index', array(
            'selectors' => $selectors,
            'filters' => $filters
        ));
    }

    public function actionLoadAjax() {

        $output = array();

        $startDate = new DateTime(Yii::app()->request->getParam('start'));
        $endDate = new DateTime(Yii::app()->request->getParam('end'));
        $selectors = explode(",", Yii::app()->request->getParam('selectors'));
        $filters = explode(",", Yii::app()->request->getParam('filters'));

        Yii::app()->user->getModel()->setSetting('lastSelectors', CJSON::encode($selectors), 'calendar');
        Yii::app()->user->getModel()->setSetting('lastFilters', CJSON::encode($filters), 'calendar');

        $entries = CalendarPlusEntry::getEntriesByRange($startDate, $endDate, $selectors, $filters);

        foreach ($entries as $entry) {
            $output[] = $entry->getFullCalendarArray();
        }

        if (in_array('mytasks', $selectors)) {
            $criteria = new CDbCriteria;
            $criteria->condition = 't.assigned_to = ' . Yii::app()->user->id;

            // Attach filters
            if (in_array(Issue::FILTER_SUBSCRIBED, $filters)) {
                $criteria->join = 'LEFT JOIN user_follow ON t.id = user_follow.object_id AND user_follow.object_model=:object_model';
                $criteria->params = array(':object_model' => 'Issue');
                $criteria->condition = 'user_follow.user_id = ' . Yii::app()->user->id;
            }
            if (in_array(Issue::FILTER_MINE, $filters)) {
                $criteria->condition .= ' OR t.owner_id = ' . Yii::app()->user->id;
            }
            
            if (!in_array(Issue::FILTER_PAST, $filters)) {
                $criteria->compare('due_date', ">=".date("Y-m-d"));
            }
//            dump($criteria);
            $tasks = Issue::model()->findAll($criteria);
            $today_time = strtotime(date("Y-m-d"));
            // Creating event format required by fullcalendar component
            foreach ($tasks as $task) {
                $url = Yii::app()->createUrl('tasks/task/view', array('id' => $task->id));
                $expire_time = strtotime($task->due_date);
                $color = '';
                $textColor = '';
                if ($expire_time < $today_time) {
                    $color = 'red';
                    $textColor = 'white';
                }
                $output[] = array(
                    'editable' => false,
                    'title' => $task->title . ' (Task)',
                    'start' => Yii::app()->dateFormatter->format('yyyy-MM-dd', $task->due_date),
                    'end' => Yii::app()->dateFormatter->format('yyyy-MM-dd', $task->due_date),
                    'description' => $task->description,
                    'url' => $url,
                    'color' => $color,
                    'textColor' => $textColor,
                );
            }
        }

        if (in_array('holidays', $selectors)) {
            $holidays = Holidays::model()->findAll();
            foreach ($holidays as $holiday) {
                $output[] = array(
                    'editable' => false,
                    'title' => $holiday->name . ' (' . $holiday->status . ')',
                    'start' => Yii::app()->dateFormatter->format('yyyy-MM-dd', $holiday->dateh),
                    'end' => Yii::app()->dateFormatter->format('yyyy-MM-dd', $holiday->dateh),
                    'url' => false
                );
            }
        }

        echo CJSON::encode($output);
    }

}
