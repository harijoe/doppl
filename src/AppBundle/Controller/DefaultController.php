<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $defaultTag = $this->getParameter('default_tag');

        return $this->tagAction($request, $defaultTag);
    }

    /**
     * @param Request $request
     * @param string  $tag
     * @return Response
     */
    public function tagAction(Request $request, $tag)
    {
        return new Response($tag.' '.$request->getLocale());
    }
}
