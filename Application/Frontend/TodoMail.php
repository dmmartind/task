<?php


namespace Application\Frontend
{

    use Main\Registry as Registry;
    /**
     * Class TodoMail
     * @package Application\Frontend
     */
    class TodoMail
    {
        /**
         * @var
         */
        protected $to;
        /**
         * @var
         */
        protected $subject;
        /**
         * @var
         */
        protected $message;
        /**
         * @var
         */
        protected $headers;
        /**
         * @var
         */
        protected $from;
        /**
         * @var string
         */
        protected $cc;
        /**
         *
         */
        const HEADERS1 =  "MIME-Version: 1.0" . "\r\n";
        /**
         *
         */
        const HEADERS2 = "Content-type:text/html;charset=UTF-8" . "\r\n";


        /**
         * TodoMail constructor.
         * @param $to
         * @param $subject
         * @param $name
         * @param $title
         * @param $priority
         * @param $from
         * @param string $cc
         */
        public function __construct($to, $subject, $name, $title, $priority, $from, $cc = "")
        {
            $this->to = $to;
            $this->subject = $subject;
            $this->getMessage($name, $title, $priority);
            $this->from = $from;
            $this->cc = $cc;
        }

        /**
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
         *
         */
        public function createMessage()
        {
            error_log("ctreateMessage**&**&*&*&*&*&&*&**&&&*&");
            $mail = Registry::get('Mailer');
            error_log($this->from);
            if($this->cc === "")
            {
                $mail->sendMail($this->to,$this->subject,$this->message,$this->from, $this->cc);
            }
            else
            {
                $mail->sendMail($this->to,$this->subject,$this->message,$this->from);
            }

        }
    }
}


