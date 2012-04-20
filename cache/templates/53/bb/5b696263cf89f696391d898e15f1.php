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
        // line 101
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
        echo "            <script type=\"text/template\" id=\"header-tpl\">
                <h1>Bookmarks</h1>
            </script>
            <script type=\"text/template\" id=\"login-form-tpl\">   
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
                 <div class=\"error\">
                    <%= errorMessage %>
                </div>
            </script>
            <script type=\"text/template\" id=\"main-menu-item\">
                <a href=\"<%= url %>\"><%= name %></a>
            </script>
            <script type=\"text/template\" id=\"register-form-tpl\">
                <form method=\"post\" action=\"register\" id=\"register\">
                    <fieldset>              
                        <label for=\"username\">Username: </label>
                        <input type=\"text\" name=\"username\" id=\"username\" value=\"\" />

                        <label for=\"email\">E-mail address: </label>
                        <input type=\"email\" name=\"email\" id=\"email\" value=\"\" /> 

                        <label for=\"password\">Password: </label>
                        <input type=\"password\" id=\"password\" name=\"password\" value=\"\" />

                        <input class=\"btnGrey submitUser\" type=\"submit\" name=\"register\" value=\"Register\" />
                    </fieldset>
                </form>
            </script>       
            <script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/libs/underscore-min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/libs/backbone-min.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/models/userinfo.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/models/mainmenu.js\"></script>
            <script type=\"text/javascript\" src=\"web/js/models/register.js\"></script> 
            <script type=\"text/javascript\" src=\"web/js/models/login.js\"></script>  
            <script type=\"text/javascript\" src=\"web/js/views/loginform.js\"></script> 
            <script type=\"text/javascript\" src=\"web/js/views/header.js\"></script> 
            <script type=\"text/javascript\" src=\"web/js/views/mainmenu.js\"></script> 
            <script type=\"text/javascript\" src=\"web/js/views/registerform.js\"></script> 
            <script type=\"text/javascript\" src=\"web/js/main.js\"></script>        
        ";
    }

    public function getTemplateName()
    {
        return "base.html";
    }

}
