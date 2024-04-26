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
         * prints the html tag
         * @return string
         */
        public function start_html()
        {
            echo("<html>");
        }


        /**
         *prints the end header
         * @return string
         */
        public function endHeader()
        {
            echo("</header>");
        }


        /**
         * prints the end html
         * @return string
         */
        public function end_html()
        {
            echo $this->includeJS();
            echo("</html>");
        }


        /**
         * prints the js script tags
         * @return string
         */
        abstract public function includeJS();


        /**
         * prints the start body tag
         * @return string
         */
        public function startBody()
        {
            echo "<body>";
        }


        /**
         * prints the end body tag
         * @return string
         */
        public function endBody()
        {
            echo "</body>";
        }


        /**
         * prints a default beginning header
         * @return string
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


        /**
         * prints the css link tags
         * @return string
         */
        abstract public function includeCSS();

        /**
         * prints the hidden field with the given csrf token
         * @param $csrf
         * @return string
         */
        function csrf_field($csrf)
        {
            $out = '<input type="hidden" name="_token" value="' . $csrf . '" autocomplete="off">';
            return $out;
        }


        /**
         * conatins all the calls neede to display the UI
         *
         */
        abstract public function Display();


        /**
         * print the header
         * @return string
         */
        abstract public function Header();

    }
}