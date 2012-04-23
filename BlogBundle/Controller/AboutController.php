<?php

namespace Pelshoff\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
    public function indexAction()
    {
        return $this->render('PelshoffBlogBundle:About:index.html.twig');
    }
}
