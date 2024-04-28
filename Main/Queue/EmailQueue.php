<?php


namespace Main\Queue {

    use Main\Queue as Queue;
    use Main\Registry as Registry;
    use Main\Database\Exception\Sql as Sql;
    use Application\Frontend\TodoMail as TodoMail;

    /**
     * Class EmailQueue
     * @package Main\Queue
     */
    class EmailQueue extends Queue
    {
        /**
         * EmailQueue constructor.
         */
        public function __construct()
        {
            $this->queueDBTable = 'email_queue';
        }

        /**
         * @param array $queueData
         */
        public function addItem(array $queueData)
        {
            error_log("addItem");
            if(!is_array($queueData))
                return;

            error_log("step1");
            $queueData['status'] = 'queued';
            $queueData['created_at'] = date('Y-m-d H:i:s');
            error_log("step2");
            $database = Registry::get("Database");
            try {
                error_log("step3");
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }
                error_log("step4");
                error_log($this->queueDBTable);
                error_log(print_r($queueData, true));
                if ($database->_isValidService()) {
                    $query = $database->query();
                    $resultID = $query->from($this->queueDBTable)
                        ->save($queueData);
                    error_log($resultID);
                    return $resultID;
                }

            } catch (\Exception $e) {
                error_log("error");
                error_log($database->getLastError());
            }
        }

        /**
         * @return mixed
         */
        public function getItem()
        {
            $database = Registry::get("Database");
            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                if ($database->_isValidService()) {
                    $query = $database->query();
                    $resultID = $query->from($this->queueDBTable)
                        ->where('status = ?', 'queued')
                        ->first();
                    return $resultID;
                }

            } catch (Sql $e) {
                error_log($e->getMessage());
            }
        }

        public function changeItemStatus(int $id, string $status, string $error = "")
        {
            $update['status']= $status;
            $update['error_text'] = $error;
            $database = Registry::get("Database");
            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }

                if ($database->_isValidService()) {
                    $query = $database->query();
                    $resultID = $query->from($this->queueDBTable)
                        ->where('id = ?', $id)
                        ->save($update);
                    return $resultID;
                }

            } catch (Sql $e) {
                error_log($e->getMessage());
            }
        }

        public function markProcessed($item)
        {
            $this->changeItemStatus($item['id'], 'processing');
        }

        public function markDone($item)
        {
            $this->changeItemStatus($item['id'], 'done');
        }

        public function markFailed($item, $errorMessageText)
        {
            $this->changeItemStatus($item['id'], 'failed', $errorMessageText);
        }

        public function process()
        {
            $item = $this->getItem();
            if($item === null)
                return 0;
            $this->markProcessed($item);
            try{
                $mail = new TodoMail($item);
                $mail->createMessage();
                $this->markDone($item);
                return 1;
            }
            catch(\Exception $e)
            {
                error_log($e->getMessage());

            }
        }



        /**
         *
         */
        public function test()
        {
            $to = ArrayMethods::array_get($details, 'email', "");
            $subject = "New task has been added";
            $name = ArrayMethods::array_get($details, 'userName', "");
            $title = ArrayMethods::array_get($details, 'title', "");
            $priority = ArrayMethods::array_get($details, 'priority', "");
            $from = "system@test.com";
        }


    }
}


