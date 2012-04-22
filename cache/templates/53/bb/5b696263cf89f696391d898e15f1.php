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
        // line 10
        echo "    </head>
    <body id=\"body\">

        ";
        // line 13
        $this->displayBlock('header', $context, $blocks);
        // line 17
        echo " 

        <div id=\"sidebarFirst\">
            ";
        // line 20
        $this->displayBlock('sidebarFirst', $context, $blocks);
        // line 23
        echo "        </div>

        <div id=\"content\">
        \t";
        // line 26
        $this->displayBlock('content', $context, $blocks);
        // line 29
        echo "        </div>

        <div id=\"sidebarSecond\">
            ";
        // line 32
        $this->displayBlock('sidebarSecond', $context, $blocks);
        // line 35
        echo "        </div>

        <div id=\"footer\">
            ";
        // line 38
        $this->displayBlock('footer', $context, $blocks);
        // line 41
        echo "        </div>

        ";
        // line 43
        $this->displayBlock('scripts', $context, $blocks);
        // line 45
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
        ";
    }

    // line 13
    public function block_header($context, array $blocks = array())
    {
        // line 14
        echo "        <div id=\"header\">

        </div>
        ";
    }

    // line 20
    public function block_sidebarFirst($context, array $blocks = array())
    {
        // line 21
        echo "                Sidebar first
            ";
    }

    // line 26
    public function block_content($context, array $blocks = array())
    {
        // line 27
        echo "                Content
            ";
    }

    // line 32
    public function block_sidebarSecond($context, array $blocks = array())
    {
        // line 33
        echo "                Sidebar second
            ";
    }

    // line 38
    public function block_footer($context, array $blocks = array())
    {
        // line 39
        echo "               footer
            ";
    }

    // line 43
    public function block_scripts($context, array $blocks = array())
    {
        // line 44
        echo "            <script type=\"text/javascript\" data-main=\"web/js/main\" src=\"web/js/libs/require.js\"></script>        
        ";
    }

    public function getTemplateName()
    {
        return "base.html";
    }

}
