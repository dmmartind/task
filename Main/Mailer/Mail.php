<?php


namespace Main\Mailer
{
    use Main\Mailer as Mailer;
    class Mail
    {
        protected $SMTPDebug;
        protected $isSMTP;
        protected $Host;
        protected $SMTPAuth;
        protected $Username;
        protected $Password;
        protected $SMTPSecure;
        protected $Port;


        public function __construct(Array $options)
        {
            $this->SMTPDebug = $options['SMTPDebug'];
            $this->isSMTP = $options['isSMTP'];
            $this->Host = $options['Host'];
            $this->SMTPAuth = $options['SMTPAuth'];
            $this->Username = $options['Username'];
            $this->Password = $options['Password'];
            $this->SMTPSecure = $options['SMTPSecure'];
            $this->Port = $options['Port'];
        }

        public function sendMail($to,$subject,$message,$from, $cc)
        {
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
            $mail->setFrom($from, 'Mailer');
            $mail->addAddress($to);     //Add a recipient
            $mail->addCC($cc);
            $mail->addBCC('bcc@example.com');

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "TaskManager Report: New Task Added to your List";
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