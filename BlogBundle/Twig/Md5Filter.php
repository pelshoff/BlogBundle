<?php

namespace Pelshoff\BlogBundle\Twig;

class Md5Filter extends \Twig_Extension
{
    public function getName()
    {
        return 'md5';
    }

    public function getFilters()
    {
        return array(
            'md5' => new \Twig_Filter_Function('md5'),
        );
    }
}
