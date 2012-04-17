<?php

/* page/index.html */
class __TwigTemplate_c5bb38d7d214cfb63b6ff013a6516839 extends Twig_Template
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
        echo "Index";
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
\t\t\t\t\t<li class=\"active\">In the spotlight</li>
\t\t\t\t\t<li><a href=\"";
        // line 24
        if (isset($context["a_newly_added"])) { $_a_newly_added_ = $context["a_newly_added"]; } else { $_a_newly_added_ = null; }
        echo twig_escape_filter($this->env, $_a_newly_added_, "html", null, true);
        echo "\">Newly added</a></li>
\t\t\t\t\t<li><a href=\"";
        // line 25
        if (isset($context["a_following"])) { $_a_following_ = $context["a_following"]; } else { $_a_following_ = null; }
        echo twig_escape_filter($this->env, $_a_following_, "html", null, true);
        echo "\">Following</a></li>
\t\t\t\t\t<li><a href=\"";
        // line 26
        if (isset($context["a_bookmarked"])) { $_a_bookmarked_ = $context["a_bookmarked"]; } else { $_a_bookmarked_ = null; }
        echo twig_escape_filter($this->env, $_a_bookmarked_, "html", null, true);
        echo "\">Bookmarked</a></li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t</div>
\t</div>
</div>
<div id=\"bottomMain\">
\t<div class=\"wrapper\">
\t\t<div id=\"primary\">
\t\t\t<ul class=\"row\">
\t\t\t\t
\t\t\t</ul>
\t\t\t<div class=\"pagination\">";
        // line 38
        if (isset($context["prev_page"])) { $_prev_page_ = $context["prev_page"]; } else { $_prev_page_ = null; }
        echo $_prev_page_;
        echo "<span id=\"pageNr\" class=\"";
        if (isset($context["page_nr"])) { $_page_nr_ = $context["page_nr"]; } else { $_page_nr_ = null; }
        echo $_page_nr_;
        echo "\">Page ";
        if (isset($context["page_nr"])) { $_page_nr_ = $context["page_nr"]; } else { $_page_nr_ = null; }
        echo $_page_nr_;
        echo "</span>";
        if (isset($context["next_page"])) { $_next_page_ = $context["next_page"]; } else { $_next_page_ = null; }
        echo $_next_page_;
        echo "</div>
\t\t</div>
\t\t<div id=\"sidebar\">

\t\t\t";
        // line 42
        if (isset($context["base"])) { $_base_ = $context["base"]; } else { $_base_ = null; }
        if ($this->getAttribute($_base_, "user_logged_in")) {
            // line 43
            echo "\t\t\t<h3>Create your own community</h3>
\t\t\t<p>
\t\t\t\tStart own communities for your interests and let other people submit content to it.\t\t
\t\t\t\t<a id=\"btnCreateAlbum\" href=\"";
            // line 46
            if (isset($context["a_create_forum"])) { $_a_create_forum_ = $context["a_create_forum"]; } else { $_a_create_forum_ = null; }
            echo twig_escape_filter($this->env, $_a_create_forum_, "html", null, true);
            echo "\">Create your community now.</a>
\t\t\t</p>\t
\t\t\t";
        }
        // line 49
        echo "
\t\t\t<h3>Recently viewed links</h3>
\t\t\t<ul id=\"lastVisit\">\t\t\t\t
\t\t\t</ul>\t\t\t
\t\t</div>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "page/index.html";
    }

    public function isTraitable()
    {
        return false;
    }
}
