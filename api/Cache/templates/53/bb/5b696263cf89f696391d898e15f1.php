<?php

/* base.html */
class __TwigTemplate_53bb5b696263cf89f696391d898e15f1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'sidebarFirst' => array($this, 'block_sidebarFirst'),
            'content' => array($this, 'block_content'),
            'sidebarSecond' => array($this, 'block_sidebarSecond'),
            'footer' => array($this, 'block_footer'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>       
        ";
        // line 4
        $this->displayBlock('head', $context, $blocks);
        // line 11
        echo "    </head>
    <body id=\"body\">
        <div id=\"container\">
            ";
        // line 14
        $this->displayBlock('header', $context, $blocks);
        // line 18
        echo " 

            <div id=\"sidebarFirst\">
                ";
        // line 21
        $this->displayBlock('sidebarFirst', $context, $blocks);
        // line 24
        echo "            </div>

            <div id=\"content\">
            \t";
        // line 27
        $this->displayBlock('content', $context, $blocks);
        // line 30
        echo "            </div>

            <div id=\"sidebarSecond\">
                ";
        // line 33
        $this->displayBlock('sidebarSecond', $context, $blocks);
        // line 36
        echo "            </div>

            <div id=\"footer\">
                ";
        // line 39
        $this->displayBlock('footer', $context, $blocks);
        // line 42
        echo "            </div>
        </div>
        ";
        // line 44
        $this->displayBlock('scripts', $context, $blocks);
        // line 46
        echo "        
    </body>
</html>";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "            <title>";
        $this->displayBlock('title', $context, $blocks);
        echo " - My Webpage</title>
            <!-- <link type=\"<textarea></textarea>xt/css\" rel=\"stylesheet\" href=\"web/css/base.css\" /> -->
            <!-- <link type=\"text/css\" rel=\"stylesheet\" href=\"web/css/header.css\" /> -->
            <!-- <link type=\"text/css\" rel=\"stylesheet\" href=\"web/css/forms.css\" /> -->
            <link type=\"text/css\" rel=\"stylesheet\" href=\"web/css/temporaryCss.css\" />
        ";
    }

    // line 14
    public function block_header($context, array $blocks = array())
    {
        // line 15
        echo "            <div id=\"header\">

            </div>
            ";
    }

    // line 21
    public function block_sidebarFirst($context, array $blocks = array())
    {
        // line 22
        echo "                    Sidebar first
                ";
    }

    // line 27
    public function block_content($context, array $blocks = array())
    {
        // line 28
        echo "                    Content
                ";
    }

    // line 33
    public function block_sidebarSecond($context, array $blocks = array())
    {
        // line 34
        echo "                    Sidebar second
                ";
    }

    // line 39
    public function block_footer($context, array $blocks = array())
    {
        // line 40
        echo "                   footer
                ";
    }

    // line 44
    public function block_scripts($context, array $blocks = array())
    {
        // line 45
        echo "            <script type=\"text/javascript\" data-main=\"web/js/main\" src=\"web/js/libs/require.js\"></script>
        ";
    }

    public function getTemplateName()
    {
        return "base.html";
    }

}
