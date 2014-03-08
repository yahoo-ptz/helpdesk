<?php
    class Config {
        // DB
        public $db_host = 'db_host';
        public $db_type = 'mysql';
        public $db_name = 'db_name';
        public $db_user = 'db_user';
        public $db_password = 'db_password';

        // IMAP settings
        public $connection_string = '{imap.yandex.ru:143/imap/notls}';
        public $email = '';
        public $password = '';

        public $attachments_dir = '/attachments';
        public $newTaskAnswer = '
            Уважаемый клиент!<br>
            Благодарим  Вас за обращение в службу поддержки.<br>
            Вашей заявке присвоен номер %d.<br>
            Наш специалист свяжется с Вами в ближайшее время.<br>
            <br><br>%s<br><br>
            С уважением, служба поддержки.<br>
            Обращаем Ваше внимание на время работы службы поддержки:<br>
            c 08:00 до 17:00 по Московскому времени, кроме выходных и праздничных дней.';
    }
