<?php

/* page/ucpRegister.html */
class __TwigTemplate_20881c5e51c6c93be8467552852e2e2a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo "Register";
    }

    // line 6
    public function block_head($context, array $blocks = array())
    {
        // line 7
        echo "\t";
        $this->displayParentBlock("head", $context, $blocks);
        echo "

";
    }

    // line 11
    public function block_content($context, array $blocks = array())
    {
        // line 12
        echo "<div id=\"topMain\">
\t<div class=\"wrapper\">
\t\t<div id=\"topPrimary\">
\t\t\t<div id=\"breadcrumbs\">
\t\t\t\t<a href=\"";
        // line 16
        if (isset($context["global"])) { $_global_ = $context["global"]; } else { $_global_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_global_, "U_HOME"), "html", null, true);
        echo "\" title=\"Back to startpage\">Home</a>
\t\t\t</div>
\t\t\t<div id=\"headline\">
\t\t\t\t<h1>In the spotlight</h1>
\t\t\t</div>
\t\t\t<div id=\"actionBar\">
\t\t\t\t<ul>
\t\t\t\t\t<li class=\"active\">Register</li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t</div>
\t</div>
</div>
<div id=\"bottomMain\">
\t<div class=\"wrapper\">
\t\t<div id=\"primary\">
\t\t\t";
        // line 32
        if (isset($context["error"])) { $_error_ = $context["error"]; } else { $_error_ = null; }
        if ($_error_) {
            // line 33
            echo "\t\t\t\t<div class=\"alert\">
\t\t\t\t\t";
            // line 34
            if (isset($context["error"])) { $_error_ = $context["error"]; } else { $_error_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_error_);
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 35
                echo "\t\t\t\t\t\t<p>";
                if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
                echo twig_escape_filter($this->env, $_message_, "html", null, true);
                echo "</p>
\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 37
            echo "\t\t\t\t</div>
\t\t\t";
        }
        // line 39
        echo "\t\t\t<div class=\"msgForm\">\t\t
\t\t\t\t<form method=\"post\" action=\"register\" id=\"register\">
\t\t\t\t\t<fieldset>\t\t\t\t
\t\t\t\t\t\t<label for=\"username\">Username: </label>
\t\t\t\t\t\t<input type=\"text\" name=\"username\" id=\"username\" value=\"";
        // line 43
        if (isset($context["data"])) { $_data_ = $context["data"]; } else { $_data_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_data_, "username"), "html", null, true);
        echo "\" />

\t\t\t\t\t\t<label for=\"email\">E-mail address: </label>
\t\t\t\t\t\t<input type=\"email\" name=\"email\" id=\"email\" value=\"";
        // line 46
        if (isset($context["data"])) { $_data_ = $context["data"]; } else { $_data_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_data_, "email"), "html", null, true);
        echo "\" />\t

\t\t\t\t\t\t<label for=\"password\">Password: </label>
\t\t\t\t\t\t<input type=\"password\" id=\"password\" name=\"password\" value=\"";
        // line 49
        if (isset($context["data"])) { $_data_ = $context["data"]; } else { $_data_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_data_, "password"), "html", null, true);
        echo "\" />

\t\t\t\t\t\t<input class=\"btnGrey\" type=\"submit\" name=\"register\" value=\"Register\" />
\t\t\t\t\t\t";
        // line 52
        if (isset($context["form_token"])) { $_form_token_ = $context["form_token"]; } else { $_form_token_ = null; }
        echo $_form_token_;
        echo "
\t\t\t\t\t</fieldset>
\t\t\t\t</form>
\t\t\t</div>
\t\t</div>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "page/ucpRegister.html";
    }

    public function isTraitable()
    {
        return false;
    }
}
