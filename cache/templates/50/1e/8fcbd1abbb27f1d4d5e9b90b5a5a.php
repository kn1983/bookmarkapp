<?php

/* page/ucpLogin.html */
class __TwigTemplate_501e8fcbd1abbb27f1d4d5e9b90b5a5a extends Twig_Template
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
        echo "Login";
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
        echo "
";
    }

    public function getTemplateName()
    {
        return "page/ucpLogin.html";
    }

    public function isTraitable()
    {
        return false;
    }
}
