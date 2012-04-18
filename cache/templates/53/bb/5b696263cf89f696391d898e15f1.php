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
        // line 22
        echo " 

        <div id=\"sidebarFirst\">
            ";
        // line 25
        $this->displayBlock('sidebarFirst', $context, $blocks);
        // line 28
        echo "        </div>

        <div id=\"content\">
        \t";
        // line 31
        $this->displayBlock('content', $context, $blocks);
        // line 34
        echo "        </div>

        <div id=\"sidebarSecond\">
            ";
        // line 37
        $this->displayBlock('sidebarSecond', $context, $blocks);
        // line 40
        echo "        </div>

        <div id=\"footer\">
            ";
        // line 43
        $this->displayBlock('footer', $context, $blocks);
        // line 46
        echo "        </div>

        ";
        // line 48
        $this->displayBlock('scripts', $context, $blocks);
        // line 73
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
        ";
    }

    // line 13
    public function block_header($context, array $blocks = array())
    {
        // line 14
        echo "        <div id=\"header\">
            ";
        // line 15
        if (isset($context["global"])) { $_global_ = $context["global"]; } else { $_global_ = null; }
        if ($this->getAttribute($_global_, "userLoggedIn")) {
            // line 16
            echo "                Logged in
                <a href=\"logout\">Logout</a>
            ";
        } else {
            // line 19
            echo "                Not logged in
            ";
        }
        // line 21
        echo "        </div>
        ";
    }

    // line 25
    public function block_sidebarFirst($context, array $blocks = array())
    {
        // line 26
        echo "                Sidebar first
            ";
    }

    // line 31
    public function block_content($context, array $blocks = array())
    {
        // line 32
        echo "                Content
            ";
    }

    // line 37
    public function block_sidebarSecond($context, array $blocks = array())
    {
        // line 38
        echo "                Sidebar second
            ";
    }

    // line 43
    public function block_footer($context, array $blocks = array())
    {
        // line 44
        echo "               footer
            ";
    }

    // line 48
    public function block_scripts($context, array $blocks = array())
    {
        // line 49
        echo "            <script type=\"text/template\" id=\"login-form-tpl\">   
                <form method=\"post\" action=\"login\" id=\"login\">
                    <fieldset>              
                        <label for=\"username\">Username: </label>
                        <input type=\"text\" name=\"username\" id=\"username\" />

                        <label for=\"password\">Password: </label>
                        <input type=\"password\" id=\"password\" name=\"password\" />
                        
                        <div class=\"radioBtn\">
                            <label for=\"autologin\"><strong>Log me on automatically each visit:</strong> </label>
                            <input type=\"checkbox\" name=\"autologin\" id=\"autologin\" value=\"1\" />                         
                        </div>

                        <span><a href=\"#\">I forgot my password</a> | <a href=\"#\">I forgot my E-mail</a></span>
                        <input type=\"hidden\" name=\"login\" value=\"1\" />
                        <input class=\"btnGrey loginBtn\" type=\"submit\" value=\"Login\" />
                    </fieldset>
                </form>
            </script>        
            <script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/libs/underscore-min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/libs/backbone-min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/main.js\"></script>        
        ";
    }

    public function getTemplateName()
    {
        return "base.html";
    }

}
