<?php


namespace Application\Frontend
{
    class Mail
    {
        protected $to;
        protected $subject;
        protected $message;
        protected $headers;
        const HEADERS1 =  "MIME-Version: 1.0" . "\r\n";
        const HEADERS2 = "Content-type:text/html;charset=UTF-8" . "\r\n";


        public function __construct($to, $subject, $name, $title, $priority, $from, $cc = "")
        {
            $this->to = $to;
            $this->subject = $subject;
            $this->getMessage($name, $title, $priority);
            $this->getHeaders($from, $cc);



        }

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
            $this->messages = $html;
        }

        public function getHeaders($from, $cc)
        {
            $tempHeader = self::HEADERS1;
            $tempHeader .= self::HEADERS2;
            $tempHeader .= "From: <{$from}> . \r\n";
            $tempHeader .= "Cc: {$cc} . \r\n";
            $this->headers = $tempHeader;

        }

        public function sendEmail()
        {
            mail($this->to,$this->subject,$this->message,$this->headers);
        }



    }
}


