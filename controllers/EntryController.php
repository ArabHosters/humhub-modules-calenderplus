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
 * EntryController used to display, edit or delete calendar entries
 *
 * @package humhub.modules_core.calendar.controllers
 * @author luke
 */
class EntryController extends ContentContainerController
{

    public function actionView()
    {
        $this->checkContainerAccess();

        $CalendarPlusEntry = CalendarPlusEntry::model()->contentContainer($this->contentContainer)->findByPk(Yii::app()->request->getQuery('id'));

        if ($CalendarPlusEntry == null) {
            throw new CHttpException('404', Yii::t('CalendarPlusModule.base', "Event not found!"));
        }

        if (!$CalendarPlusEntry->content->canRead()) {
            throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to access this event!"));
        }

        $CalendarPlusEntryParticipant = CalendarPlusEntryParticipant::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'calendar_entry_id' => $CalendarPlusEntry->id));

        $this->render('view', array(
            'CalendarPlusEntry' => $CalendarPlusEntry,
            'CalendarPlusEntryParticipant' => $CalendarPlusEntryParticipant,
            'userCanRespond' => $CalendarPlusEntry->canRespond(),
            'userAlreadyResponded' => $CalendarPlusEntry->hasResponded(),
        ));
    }

    public function actionRespond()
    {

        $this->checkContainerAccess();

        $entryId = (int) Yii::app()->request->getParam('id');
        $type = (int) Yii::app()->request->getParam('type');

        $CalendarPlusEntry = CalendarPlusEntry::model()->contentContainer($this->contentContainer)->findByPk($entryId);

        if ($CalendarPlusEntry == null) {
            throw new CHttpException('404', Yii::t('CalendarPlusModule.base', "Event not found!"));
        }

        if (!$CalendarPlusEntry->content->canRead()) {
            throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to access this event!"));
        }

        if ($CalendarPlusEntry->canRespond()) {
            $CalendarPlusEntryParticipant = CalendarPlusEntryParticipant::model()->findByAttributes(array('calendar_entry_id' => $CalendarPlusEntry->id, 'user_id' => Yii::app()->user->id));

            if ($CalendarPlusEntryParticipant == null) {
                $CalendarPlusEntryParticipant = new CalendarPlusEntryParticipant;
                $CalendarPlusEntryParticipant->user_id = Yii::app()->user->id;
                $CalendarPlusEntryParticipant->calendar_entry_id = $CalendarPlusEntry->id;
            }

            $CalendarPlusEntryParticipant->participation_state = $type;
            $CalendarPlusEntryParticipant->save();
        }


        $this->redirect($this->createContainerUrl('view', array('id' => $CalendarPlusEntry->id)));
    }

    public function actionEdit()
    {
        $this->checkContainerAccess();


        // Indicates this entry is created by global calendar
        // We show a notice in this case.
        $createFromGlobalCalendar = false;

        $CalendarPlusEntry = CalendarPlusEntry::model()->contentContainer($this->contentContainer)->findByPk(Yii::app()->request->getParam('id'));
        if ($CalendarPlusEntry == null) {

            if (!$this->contentContainer->canWrite()) {
                throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to create events!"));
            }

            $CalendarPlusEntry = new CalendarPlusEntry;

            if (Yii::app()->request->getParam('createFromGlobalCalendar') == 1) {
                $createFromGlobalCalendar = true;
            }

            if (Yii::app()->request->getParam('fullCalendar') == 1) {

                $startTime = new DateTime(Yii::app()->request->getParam('start_time', ''));
                $endTime = new DateTime(Yii::app()->request->getParam('end_time', ''));

                $CalendarPlusEntry->start_time = $startTime->format('Y-m-d H:i:s');

                if (CalendarPlusUtils::isFullDaySpan($startTime, $endTime, true)) {
                    $CalendarPlusEntry->start_time_date = $startTime->format('Y-m-d H:i:s');

                    // In Fullcalendar the EndTime is the moment AFTER the event
                    $oneSecond = new DateInterval("PT1S");
                    $endTime->sub($oneSecond);

                    $CalendarPlusEntry->end_time_date = $endTime->format('Y-m-d H:i:s');
                    $CalendarPlusEntry->all_day = true;
                } else {
                    $CalendarPlusEntry->end_time = $endTime->format('Y-m-d H:i:s');
                }
            }
        } else {

            if (!$CalendarPlusEntry->content->canWrite()) {
                throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to edit this event!"));
            }
        }

        $CalendarPlusEntry->scenario = 'edit';

        if (isset($_POST['CalendarPlusEntry'])) {

            $CalendarPlusEntry->content->container = $this->contentContainer;
            $CalendarPlusEntry->attributes = Yii::app()->input->stripClean($_POST['CalendarPlusEntry']);

            if ($CalendarPlusEntry->all_day) {
                $startDate = new DateTime($CalendarPlusEntry->start_time_date);
                $endDate = new DateTime($CalendarPlusEntry->end_time_date);
                $CalendarPlusEntry->start_time = $startDate->format('Y-m-d') . " 00:00:00";
                $CalendarPlusEntry->end_time = $endDate->format('Y-m-d') . " 23:59:59";
            } else {
                // Avoid "required" error, when fields are not used
                $CalendarPlusEntry->start_time_date = $CalendarPlusEntry->start_time;
                $CalendarPlusEntry->end_time_date = $CalendarPlusEntry->end_time;
            }

            if ($CalendarPlusEntry->validate()) {
                $CalendarPlusEntry->save();

                $this->renderModalClose();

                // After closing modal refresh calendar or page
                print "<script>";
                print 'if(typeof $("#calendar").fullCalendar != "undefined") { $("#calendar").fullCalendar("refetchEvents"); } else { location.reload(); }';
                print "</script>";

                return;
            }
        }

        $this->renderPartial('edit', array('CalendarPlusEntry' => $CalendarPlusEntry, 'createFromGlobalCalendar' => $createFromGlobalCalendar), false, true);
    }

    public function actionUserList()
    {
        $this->checkContainerAccess();
        $CalendarPlusEntry = CalendarPlusEntry::model()->contentContainer($this->contentContainer)->findByPk(Yii::app()->request->getQuery('id'));

        if ($CalendarPlusEntry == null) {
            throw new CHttpException('404', Yii::t('CalendarPlusModule.base', "Event not found!"));
        }

        if (!$CalendarPlusEntry->content->canRead()) {
            throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to access this event!"));
        }

        $state = Yii::app()->request->getQuery('state');

        // Pagination
        $page = (int) Yii::app()->request->getParam('page', 1);
        $total = CalendarPlusEntryParticipant::model()->count('calendar_entry_id=:entryId and participation_state=:state', array(':entryId' => $CalendarPlusEntry->id, ':state' => $state));
        $usersPerPage = HSetting::Get('paginationSize');
        $pagination = new CPagination($total);
        $pagination->setPageSize($usersPerPage);

        $criteria = new CDbCriteria();
        $pagination->applyLimit($criteria);
        $criteria->alias = "user";
        $criteria->join = "LEFT JOIN calendar_entry_participant on user.id = calendar_entry_participant.user_id";
        $criteria->condition = "calendar_entry_participant.calendar_entry_id = :entryId AND calendar_entry_participant.participation_state = :state";
        $criteria->params = array(':entryId' => $CalendarPlusEntry->id, ':state' => $state);

        $users = User::model()->findAll($criteria);

        $title = "";
        if ($state == CalendarPlusEntryParticipant::PARTICIPATION_STATE_ACCEPTED) {
            $title = Yii::t('CalendarPlusModule.base', 'Attending users');
        } elseif ($state == CalendarPlusEntryParticipant::PARTICIPATION_STATE_DECLINED) {
            $title = Yii::t('CalendarPlusModule.base', 'Declining users');
        } elseif ($state == CalendarPlusEntryParticipant::PARTICIPATION_STATE_MAYBE) {
            $title = Yii::t('CalendarPlusModule.base', 'Maybe attending users');
        }

        $this->renderPartial('application.modules_core.user.views._listUsers', array('title' => $title, 'users' => $users, 'pagination' => $pagination), false, true);
    }

    public function actionEditAjax()
    {
        $this->checkContainerAccess();

        $CalendarPlusEntry = CalendarPlusEntry::model()->contentContainer($this->contentContainer)->findByPk(Yii::app()->request->getQuery('id'));

        if ($CalendarPlusEntry == null) {
            throw new CHttpException('404', Yii::t('CalendarPlusModule.base', "Event not found!"));
        }

        if (!$CalendarPlusEntry->content->canWrite()) {
            throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to edit this event!"));
        }

        if ((Yii::app()->request->getParam('start_time', '') != '')) {
            $startTime = new DateTime(Yii::app()->request->getParam('start_time', ''));
            $CalendarPlusEntry->start_time = $startTime->format('Y-m-d H:i:s');
        }
        if ((Yii::app()->request->getParam('end_time', '') != '')) {
            $endTime = new DateTime(Yii::app()->request->getParam('end_time', ''));

            // If we are getting an EndTime of FullCalendar, the EndTime is the moment
            // After the Event, so we need to fix it
            if (Yii::app()->request->getParam('fullCalendar') == 1 && $CalendarPlusEntry->all_day) {
                $endTime->sub(new DateInterval('PT1S'));                // Substract an second
            }

            $CalendarPlusEntry->end_time = $endTime->format('Y-m-d H:i:s');
        }

        $CalendarPlusEntry->save();
    }

    public function actionDelete()
    {

        $this->checkContainerAccess();
        $CalendarPlusEntry = CalendarPlusEntry::model()->contentContainer($this->contentContainer)->findByPk(Yii::app()->request->getQuery('id'));

        if ($CalendarPlusEntry == null) {
            throw new CHttpException('404', Yii::t('CalendarPlusModule.base', "Event not found!"));
        }

        if (!$CalendarPlusEntry->content->canDelete()) {
            throw new CHttpException('403', Yii::t('CalendarPlusModule.base', "You don't have permission to delete this event!"));
        }

        $CalendarPlusEntry->delete();

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderModalClose();
        } else {
            $this->redirect($this->createContainerUrl('view/index'));
        }
    }

}
