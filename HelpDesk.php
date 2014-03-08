<?php

class HelpDesk {
    const STATUS_UNSEEN = 'UNSEEN';
    private $_config = array();

    public function __construct ($configPath) {
        $this->_config = include($configPath);
    }

    public function checkMessages($type = self::STATUS_UNSEEN) {
        $mbox = imap_open($this->_config['connctionString'], $this->_config['email'], $this->_config['password']);
        if (!$mbox) {
            throw new Exception('Connection error.');
        }
        $unseenEmails = imap_search($mbox, $type);
        if (!$unseenEmails) {
            return true;
        }

        foreach ($unseenEmails as $uid) {
            $id = $this->getTaskIdByHeader($mbox, $uid);
            if ($id && $this->isExistTask($id)) {
                $this->updateTask($id, $mbox, $uid);
            } else {
                $this->registerNewTask($mbox, $uid);
            }
        }
        imap_close($mbox);
        return true;
    }

    public function getTaskIdByHeader($mbox, $uid) {
        $header = imap_headerinfo($mbox, $uid);
        return null;
    }

    public function registerNewTask($mbox, $uid) {
        $header = imap_headerinfo($mbox, $uid);
        $body = imap_fetchbody($mbox, $uid, 1.1);
        if ($body == '') {
            $body = imap_fetchbody($mbox, $uid, 1);
        }
        $body = imap_qprint($body);
        $st = imap_fetchstructure($mbox, $uid);
        var_dump($st->parts[0]);
        $to = $header->from[0]->mailbox . '@' . $header->from[0]->host;
        $taskId = $this->createTask($uid, $header, $body);
        $subject = sprintf('[Task #%d] Ваша заявка приянта', $taskId);
        $text = sprintf($this->_config['newTaskAnswer'], $this->_config['organization'], $taskId, quoted_printable_decode($body));

        if(!imap_mail($to, $subject, $text, 'From: ' . $this->_config['email'])) {
            throw new Exception('registerNewTask: Send msg error.');
        }
    }

    public function updateTask($id, $mbox, $uid) {
        $body = imap_body($mbox, $uid);
    }

    public function isExistTask($id) {
        return false;
    }

    public function createTask($uid, $header, $body) {
        echo '<br><br>';
        echo 'Date: ' . $header->date . '<br>';
        echo 'From: ' . $header->from[0]->mailbox . '@' . $header->from[0]->host .'<br>';
        echo 'Subject: ' . $header->subject . '<br>';
        echo 'Body: ' . $body . '<br>';
        echo '<br><br>';
        return true;
        return 1;
    }
}
