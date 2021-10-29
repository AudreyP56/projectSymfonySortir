<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, ['label' => "Nouveau Password"])
            ->add('passwordConfirm', PasswordType::class, ['label' => "Confirmer le nouveau password"])
            ->add('send', SubmitType::class, ['label' => "le Changement c'est maintenant"]);
    }
}