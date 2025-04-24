<?php

interface Event_Reminder_Manager {
  public function send_reminders();
  public function cron_exec();
}