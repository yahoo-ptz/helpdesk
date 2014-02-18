<?php

class HelpDesk {
	const STATUS_UNSEEN = 'UNSEEN';
	private $connctionString = '';
	private $email = '';
	private $password = '';
	private $organization = '';
	private static $newTaskAnswer = '
		Уважаемый клиент!<br><br>
		Благодарим  Вас за обращение в службу поддержки %s.<br><br>
		Вашей заявке присвоен номер %d.<br>
		Наш специалист свяжется с Вами в ближайшее время.<br>
		Обращаем Ваше внимание на время работы службы поддержки:<br>
		c 08:00 до 17:00 по Московскому времени, кроме выходных и праздничных дней.<br><br>
		%s';

	public function __construct ($connctionString, $email, $password) {
		$this->connctionString = $connctionString;
		$this->email = $email;
		$this->password = $password;
	}

	public function setOrganization($organization) {
		$this->organization = $organization;
	}

	public function checkMessages($type = self::STATUS_UNSEEN) {
		$mbox = imap_open($this->connctionString, $this->email, $this->password);
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
		$text = sprintf(self::$newTaskAnswer, $this->organization, $taskId, quoted_printable_decode($body));

		if(!imap_mail($to, $subject, $text, 'From: ' . $this->email)) {
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