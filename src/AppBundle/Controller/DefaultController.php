<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'label' => 'contact.form.name.label',
                'attr' => ['class' => 'input', 'placeholder' => 'contact.form.name.placeholder'],
                'label_attr' => ['class' => 'label'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'contact.form.email.label',
                'attr' => ['class' => 'input', 'placeholder' => 'contact.form.email.placeholder'],
                'label_attr' => ['class' => 'label'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'contact.form.message.label',
                'attr' => ['class' => 'textarea', 'placeholder' => 'contact.form.message.placeholder'],
                'label_attr' => ['class' => 'label'],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'contact.form.send',
                'attr' => ['class' => 'button is-primary'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->getData()['name'];
//            $email = $form->getData()['email'];
//            $message = $form->getData()['message'];
            $message = \Swift_Message::newInstance()
                ->setSubject("New message from Doppl — from {$name}")
                ->setFrom($this->getParameter('from_email'))
                ->setTo($this->getParameter('to_email'))
                ->setBody(
                    $this->renderView(
                        ':emails/contact:notification.html.twig',
                        $form->getData(),
                        'text/html'
                ))
            ;
            $this->get('mailer')->send($message);

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
