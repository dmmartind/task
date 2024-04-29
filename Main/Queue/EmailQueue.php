<?php


namespace Main\Queue {

    use Main\Queue as Queue;
    use Main\Registry as Registry;
    use Main\Database\Exception\Sql as Sql;
    use Main\Queue\Exception as Exception;
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
            if(!is_array($queueData))
                return;

            $queueData['status'] = 'queued';
            $queueData['created_at'] = date('Y-m-d H:i:s');
            $database = Registry::get("Database");
            try {
                if (!$database->_isValidService()) {
                    $database = $database->connect();
                }
               
                if ($database->_isValidService()) {
                    $query = $database->query();
                    $resultID = $query->from($this->queueDBTable)
                        ->save($queueData);
                    return $resultID;
                }

            } catch (Sql $e) {
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
                error_log($database->getLastError());
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
                error_log($database->getLastError());
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
            catch(Exception $e)
            {
                error_log($e->getMessage());

            }
        }
    }
}


