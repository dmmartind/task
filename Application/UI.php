<?php

namespace Application {
    abstract class UI
    {
        private $documentName;
        private $jsArr = [];
        private $cssArr = [];



        public function start_html()
        {
            echo("<html>");
        }

        public function endHeader()
        {
            echo("</header>");
        }

        public function end_html()
        {
            echo $this->includeJS();
            echo("</html>");
        }

        public function startBody()
        {
            echo "<body>";
        }

        public function endBody()
        {
            echo "</body>";
        }

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

        abstract public function Display();
        abstract public function Header();
        abstract public function includeJS();
        abstract public function includeCSS();

    }
}



?>