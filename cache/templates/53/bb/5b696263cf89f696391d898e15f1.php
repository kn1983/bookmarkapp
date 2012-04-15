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
            'content' => array($this, 'block_content'),
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
        // line 12
        echo "    </head>
    <body>

        ";
        // line 15
        $this->displayBlock('header', $context, $blocks);
        // line 40
        echo "        


    \t";
        // line 43
        $this->displayBlock('content', $context, $blocks);
        // line 45
        echo "

        <div id=\"footer\">
            ";
        // line 48
        $this->displayBlock('footer', $context, $blocks);
        // line 51
        echo "        </div>
        ";
        // line 52
        $this->displayBlock('scripts', $context, $blocks);
        // line 53
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
            <link type=\"text/css\" rel=\"stylesheet\" href=\"web/css/base.css\" />
            <link type=\"text/css\" rel=\"stylesheet\" href=\"web/css/header.css\" />
            <link type=\"text/css\" rel=\"stylesheet\" href=\"web/css/forms.css\" />
            <script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/base.js\"></script>
        ";
    }

    // line 15
    public function block_header($context, array $blocks = array())
    {
        // line 16
        echo "        <div id=\"head\">
            <ul id=\"menu1\">
                <li><a id=\"hw\" href=\"\">Projekt 3</a></li>             
            </ul>
            <ul id=\"menu2\">
                ";
        // line 21
        if (isset($context["global"])) { $_global_ = $context["global"]; } else { $_global_ = null; }
        if ($this->getAttribute($_global_, "userLoggedIn")) {
            // line 22
            echo "                <li><a href=\"#\">";
            if (isset($context["global"])) { $_global_ = $context["global"]; } else { $_global_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_global_, "username"), "html", null, true);
            echo "</a></li>
                <li id=\"account\"></li>
                ";
        } else {
            // line 24
            echo "              
                <li><a href=\"register\">Register</a></li>
                <li><a href=\"login\">Login</a></li>
                ";
        }
        // line 28
        echo "            </ul>
            ";
        // line 29
        if (isset($context["global"])) { $_global_ = $context["global"]; } else { $_global_ = null; }
        if ($this->getAttribute($_global_, "userLoggedIn")) {
            // line 30
            echo "            <div id=\"userBar\">
                <ul>
                    <li><a href=\"#\">0 new pms</a></li>
                    <li><a href=\"#\">Account settings</a></li>
                    <li><a href=\"#\">User settings</a></li>
                    <li><a href=\"logout\">Logout</a></li>   
                </ul>       
            </div>
             ";
        }
        // line 39
        echo "        </div>
        ";
    }

    // line 43
    public function block_content($context, array $blocks = array())
    {
        // line 44
        echo "        ";
    }

    // line 48
    public function block_footer($context, array $blocks = array())
    {
        // line 49
        echo "               
            ";
    }

    // line 52
    public function block_scripts($context, array $blocks = array())
    {
        // line 53
        echo "        ";
    }

    public function getTemplateName()
    {
        return "base.html";
    }

}
