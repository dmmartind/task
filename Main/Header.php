<?php


namespace Main {

    use Main\ArrayMethods as ArrayMethods;


    /**
     * Class Header
     * @package Main
     */
    class Header
    {


        /**
         * @var array|mixed|null
         */
        private $host;

        /**
         * @var array|mixed|null
         */
        private $userAgent;

        /**
         * @var array|mixed|null
         */
        private $accept;

        /**
         * @var array|mixed|null
         */
        private $acceptLang;

        /**
         * @var array|mixed|null
         */
        private $acceptEncc;

        /**
         * @var array|mixed|null
         */
        private $contentType;

        /**
         * @var array|mixed|null
         */
        private $requestWith;

        /**
         * @var array|mixed|null
         */
        private $contentLength;

        /**
         * @var array|mixed|null
         */
        private $origin;

        /**
         * @var array|mixed|null
         */
        private $connection;

        /**
         * @var array|mixed|null
         */
        private $referer;

        /**
         * @var array|mixed|null
         */
        private $fetchDest;

        /**
         * @var array|mixed|null
         */
        private $fetchMode;

        /**
         * @var array|mixed|null
         */
        private $fetchSite;


        /**
         * Header constructor.
         */
        public function __construct()
        {
            $headerArr = getallheaders();
            $this->host = ArrayMethods::array_get($headerArr, 'Host', "");
            $this->userAgent = ArrayMethods::array_get($headerArr, 'User-Agent', "");
            $this->accept = ArrayMethods::array_get($headerArr, 'Accept', "");
            $this->acceptLang = ArrayMethods::array_get($headerArr, 'Accept-Language', "");
            $this->acceptEncc = ArrayMethods::array_get($headerArr, 'Accept-Encoding', "");
            $this->contentType = ArrayMethods::array_get($headerArr, 'Content-Type', "");
            $this->requestWith = ArrayMethods::array_get($headerArr, 'X-Requested-With', "");
            $this->contentLength = ArrayMethods::array_get($headerArr, 'Content-Length', "");
            $this->origin = ArrayMethods::array_get($headerArr, 'Origin', "");
            $this->connection = ArrayMethods::array_get($headerArr, 'Connection', "");
            $this->referer = ArrayMethods::array_get($headerArr, 'Referer', "");
            $this->fetchDest = ArrayMethods::array_get($headerArr, 'Sec-Fetch-Dest', "");
            $this->fetchMode = ArrayMethods::array_get($headerArr, 'Sec-Fetch-Mode', "");
            $this->fetchSite = ArrayMethods::array_get($headerArr, 'Sec-Fetch-Site', "");
        }


        /**
         * @return array|mixed|null
         */
        public function getHost()
        {
            return $this->host;
        }


        /**
         * @return array|mixed|null
         */
        public function getUserAgent()
        {
            return $this->userAgent;
        }


        /**
         * @return array|mixed|null
         */
        public function getAccept()
        {
            return $this->accept;
        }


        /**
         * @return array|mixed|null
         */
        public function getAcceptLang()
        {
            return $this->acceptLang;
        }


        /**
         * @return array|mixed|null
         */
        public function getAcceptEncc()
        {
            return $this->acceptEncc;
        }


        /**
         * @return array|mixed|null
         */
        public function getContentType()
        {
            return $this->contentType;
        }

        /**
         * @return array|mixed|null
         */
        public function getContentLength()
        {
            return $this->contentLength;
        }

        /**
         * @return array|mixed|null
         */
        public function getOrigin()
        {
            return $this->origin;
        }

        /**
         * @return array|mixed|null
         */
        public function getConnection()
        {
            return $this->connection;
        }

        /**
         * @return array|mixed|null
         */
        public function getReferer()
        {
            return $this->referer;
        }

        /**
         * @return array|mixed|null
         */
        public function getFetchDest()
        {
            return $this->fetchDest;
        }

        /**
         * @return array|mixed|null
         */
        public function getFetchMode()
        {
            return $this->fetchMode;
        }

        /**
         * @return array|mixed|null
         */
        public function getFetchSite()
        {
            return $this->fetchSite;
        }

        /**
         * @return bool
         */
        public function isAjax()
        {
            return 'XMLHttpRequest' == $this->getRequestWith();
        }

        /**
         * @return array|mixed|null
         */
        public function getRequestWith()
        {
            return $this->requestWith;
        }

    }
}


