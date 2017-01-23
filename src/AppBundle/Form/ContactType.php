<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'attr' => ['class' => 'button is-primary is-medium'],
            ])
        ;
    }
}
