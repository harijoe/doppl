<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Cache(public=true, smaxage=3600)
 */
class DefaultController extends Controller
{
    /**
     * @param Request     $request
     * @param null|string $tag
     * @return Response
     */
    public function tagAction($tag = null)
    {
        return $this->render(':default:index.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @Route(path="contact", name="contact_en", defaults={"_locale"="en"})
     * @Route(path="fr/contact", name="contact_fr", defaults={"_locale"="fr"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['name'];
            $email = $data['email'];
            $message = $data['message'];

            $mailer = $this->get('app.mailer');
            $mailer->sendAutoreply($name, $email);
            $mailer->sendNotification($name, $message, $email);

            return $this->redirectToRoute("contact_success_{$request->getLocale()}");
        }

        return $this->render(':contact:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="contact/success", name="contact_success_en", defaults={"_locale"="en"})
     * @Route(path="fr/contact/succes", name="contact_success_fr", defaults={"_locale"="fr"})
     * @Method("GET")
     * @return Response
     */
    public function contactSuccessAction()
    {
        return $this->render(':contact:success.html.twig');
    }
}
