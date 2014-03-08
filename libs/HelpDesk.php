<?php

class HelpDesk {
    const STATUS_UNSEEN = 'UNSEEN';
    private $_mailbox = null; // instance of ImapMailbox

    public function __construct () {
        $config = new Config();
        $this->_mailbox = new ImapMailbox($config->connection_string, $config->email, $config->password, $config->attachments_dir, 'utf-8');
        if (empty($this->_mailbox)) {
            throw new Exception('Empty mailbox');
        }
    }

    public function checkMessages() {
        $mailsIds = $this->_mailbox->searchMailBox(self::STATUS_UNSEEN);
        if(!$mailsIds) {
            return;
        }

        foreach ($mailsIds as $uid) {
            $mail = $this->_mailbox->getMail($uid);
            $taskId = $this->getTaskIdByHeader($mail->subject);
            $task = Task::find(array(
                'conditions' => array('`id` = ? and `from` = ?', $taskId, $mail->fromAddress)
            ));
            if (!empty($task)) {
                //update task
            } else {
                $this->registerNewTask($mail);
            }
        }
    }

    public function getTaskIdByHeader($subject) {
        $id = null;
        //todo reg_exp id from $subject
        return $id;
    }

    public function registerNewTask(IncomingMail $mail) {
        $task = new Task();
        $task->from = $mail->fromAddress;
        $task->subject = $mail->subject;
        $task->message = $mail->textPlain;
        $task->time_received = $mail->date;
        $task->save();

        $config = new Config();
        $subject = sprintf($config->new_task_answer_subject, $task->id);
        $text = sprintf($config->new_task_answer_body, $task->id, quoted_printable_decode($task->message));
        if(!mail($task->from, $subject, $text, 'From: ' . $config->email)) {
            throw new Exception('registerNewTask: Send msg error.');
        }
    }
}
