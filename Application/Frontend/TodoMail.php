<?php
/**
 * ©2024 David Martin. All Rights Reserve.
 */

namespace Application\Frontend {

    use Main\ArrayMethods;
    use Main\Registry as Registry;

    /**
     * Class TodoMail
     * @package Application\Frontend
     */
    class TodoMail
    {
        /**
         * const containing the MIME version
         */
        private const HEADERS1 = "MIME-Version: 1.0" . "\r\n";
        /**
         * const containing the content type
         */
        private const HEADERS2 = "Content-type:text/html;charset=UTF-8" . "\r\n";
        /**
         * for holding the recipient
         * @var
         */
        protected $to;
        /**
         * for holding the subject line of the email message
         * @var
         */
        protected $subject;
        /**
         * for holding the html message of the email
         * @var
         */
        protected $message;
        /**
         * for holding the headers of the email
         * @var
         */
        protected $headers;
        /**
         * for holding the email address of the system
         * @var
         */
        protected $from;
        /**
         * for holding the email of the carbon copy
         * @var string
         */
        protected $cc;

        /**
         * TodoMail constructor.
         * takes arguments for all the attributes for an email message and set the class props to them
         * @param $to
         * @param $subject
         * @param $name
         * @param $title
         * @param $priority
         * @param $from
         * @param string $cc
         */
        public function __construct(Array $input)
        {
            $this->to = ArrayMethods::array_get($input, 'to_email', "");
            $this->subject = ArrayMethods::array_get($input,'subject', "");
            $this->getMessage(ArrayMethods::array_get($input, 'name', ""), ArrayMethods::array_get($input, 'title',""), ArrayMethods::array_get($input, 'priority',""));
            $this->from = ArrayMethods::array_get($input, 'from_email', "");
            $this->cc = ArrayMethods::array_get($input, 'cc', "");
        }

        /**
         * takes in the name, title of the task, and the initial priority and creates a new message with them
         * @param $name
         * @param $title
         * @param $priority
         */
        public function getMessage($name, $title, $priority)
        {
            $html = <<<EOF
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager Report: New Task Added to your List</title>
</head>
<body>
<p>Hi {$name}, a new task called {$title} with the priority set to {$priority} has been added to your task list.</p>
</body>
</html>
EOF;
            $this->message = $html;
        }


        /**
         * creates headers using the inputs of the from and cc to make them.
         * @param $from
         * @param $cc
         */
        public function getHeaders($from, $cc)
        {
            $tempHeader = self::HEADERS1;
            $tempHeader .= self::HEADERS2;
            $tempHeader .= "From: <{$from}> . \r\n";
            $tempHeader .= "Cc: {$cc} . \r\n";
            $this->headers = $tempHeader;
        }


        /**
         * gets an instance of the mailer and
         * sends the contents and mail attributes to the mailer to be processes and sent.
         */
        public function createMessage()
        {
            $mail = Registry::get('Mailer');
            if ($this->cc === "") {
                $mail->sendMail($this->to, $this->subject, $this->message, $this->from, $this->cc);
            } else {
                $mail->sendMail($this->to, $this->subject, $this->message, $this->from);
            }
        }
    }
}


