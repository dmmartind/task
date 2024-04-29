<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */

namespace Main\Mailer {

    use Main\Mailer as Mailer;
    use Main\Mailer\Exception as Exception;
    use PHPMailer\PHPMailer\PHPMailer as PHPMailer;


    /**
     * Class Mail
     * @package Main\Mailer
     */
    class Mail
    {

        /**
         * bool to hold whether where to verbose extra info
         * @var mixed
         */
        protected $SMTPDebug;

        /**
         * bool whether this is a SMTP connection
         * @var bool
         */
        protected $isSMTP;

        /**
         * host name
         * @var mixed
         */
        protected $Host;

        /**
         * authentication token
         * @var mixed
         */
        protected $SMTPAuth;

        /**
         * connection username
         * @var mixed
         */
        protected $Username;

        /**
         * connection password
         * @var mixed
         */
        protected $Password;

        /**
         * type of security
         * @var mixed
         */
        protected $SMTPSecure;

        /**
         * connection port
         * @var mixed
         */
        protected $Port;


        /**
         * Mail constructor.
         * takes the the options from the ini file and fills the class props
         * @param array $options
         */
        public function __construct(Array $options)
        {
            $this->SMTPDebug = $options['SMTPDebug'];
            $this->isSMTP = ($options['type'] === 'smtp') ? true : false;
            $this->Host = $options['Host'];
            $this->SMTPAuth = $options['SMTPAuth'];
            $this->Username = $options['Username'];
            $this->Password = $options['Password'];
            $this->SMTPSecure = $options['SMTPSecure'];
            $this->Port = $options['Port'];
        }


        /**
         * send email after all the PHPMail props is set to the class props
         * @param $to
         * @param $subject
         * @param $message
         * @param $from
         * @param string $cc
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function sendMail($to, $subject, $message, $from, $cc = "")
        {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = $this->SMTPDebug;
            if ($this->isSMTP) {
                $mail->isSMTP();
            }

            $mail->Host = $this->Host;
            $mail->SMTPAuth = $this->SMTPAuth;
            $mail->Username = $this->Username;
            $mail->Password = $this->Password;
            $mail->SMTPSecure = $this->SMTPSecure;
            $mail->Port = $this->Port;

            //Recipients
            $mail->setFrom($from, 'system@gmail.com');
            $mail->addAddress($to);     //Add a recipient
            if ($cc !== "") {
                $mail->addCC($cc);
            }

            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = ($subject === "") ? $subject : "TaskManager Report: New Task Added to your List";
            $mail->Body = $message;
            try {
                $mail->send();
            } catch (Exception $e) {
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        }

    }
}