<?php

namespace Application {

    /**
     * Class UI
     * @package Application
     */
    abstract class UI
    {
        /**
         * @var
         */
        private $documentName;
        /**
         * @var array
         */
        private $jsArr = [];
        /**
         * @var array
         */
        private $cssArr = [];


        /**
         *
         */
        public function start_html()
        {
            echo("<html>");
        }

        /**
         *
         */
        public function endHeader()
        {
            echo("</header>");
        }

        /**
         *
         */
        public function end_html()
        {
            echo $this->includeJS();
            echo("</html>");
        }

        /**
         *
         */
        public function startBody()
        {
            echo "<body>";
        }

        /**
         *
         */
        public function endBody()
        {
            echo "</body>";
        }

        /**
         *
         */
        public function defaultHeader()
        {
            $out = <<<EOF
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->documentName}</title>
EOF;

            echo($out);
            $this->includeCSS();
            echo "</head>";
        }

        function csrf_field($csrf)
        {
            $out = '<input type="hidden" name="_token" value="'. $csrf .'" autocomplete="off">';
            return $out;
        }

        /**
         * @return mixed
         */
        abstract public function Display();

        /**
         * @return mixed
         */
        abstract public function Header();

        /**
         * @return mixed
         */
        abstract public function includeJS();

        /**
         * @return mixed
         */
        abstract public function includeCSS();

    }
}