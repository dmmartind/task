<?php


namespace Main\Mailer
{
    use Main\Mailer as Mailer;
    use PHPMailer\PHPMailer\PHPMailer as PHPMailer;

    /**
     * Class Mail
     * @package Main\Mailer
     */
    class Mail
    {
        /**
         * @var mixed
         */
        protected $SMTPDebug;
        /**
         * @var mixed
         */
        protected $isSMTP;
        /**
         * @var mixed
         */
        protected $Host;
        /**
         * @var mixed
         */
        protected $SMTPAuth;
        /**
         * @var mixed
         */
        protected $Username;
        /**
         * @var mixed
         */
        protected $Password;
        /**
         * @var mixed
         */
        protected $SMTPSecure;
        /**
         * @var mixed
         */
        protected $Port;


        /**
         * Mail constructor.
         * @param array $options
         */
        public function __construct(Array $options)
        {
            $this->SMTPDebug = $options['SMTPDebug'];
            $this->isSMTP = ($options['type'] === 'smtp')?true : false;
            $this->Host = $options['Host'];
            $this->SMTPAuth = $options['SMTPAuth'];
            $this->Username = $options['Username'];
            $this->Password = $options['Password'];
            $this->SMTPSecure = $options['SMTPSecure'];
            $this->Port = $options['Port'];
        }

        /**
         * @param $to
         * @param $subject
         * @param $message
         * @param $from
         * @param $cc
         */
        public function sendMail($to,$subject,$message,$from, $cc="")
        {
            error_log("sendmail");
            error_log($message);
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = $this->SMTPDebug;
            if($this->isSMTP)
                $mail->isSMTP();

            $mail->Host = $this->Host;
            $mail->SMTPAuth = $this->SMTPAuth;
            $mail->Username = $this->Username;
            $mail->Password = $this->Password;
            $mail->SMTPSecure = $this->SMTPSecure;
            $mail->Port = $this->Port;

            //Recipients
            $mail->setFrom($from, 'system@gmail.com');
            $mail->addAddress($to);     //Add a recipient
            if($cc !== "")
            {
                $mail->addCC($cc);
            }

            //$mail->addBCC('bcc@example.com');

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = ($subject === "")?$subject:"TaskManager Report: New Task Added to your List";
            $mail->Body    = $message;
            try{
                $mail->send();
                error_log('Message has been sent');
            }
            catch(Exception $e)
            {
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        }

    }
}