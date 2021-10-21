<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureSortie', DateTimeType::class)
            ->add('dateLimite', DateType::class)
            ->add('nbPlace', NumberType::class)
            ->add('duree', NumberType::class)
            ->add('description', TextType::class)
            ->add('siteOrganisateur', TextType::class)
            ->add('ville', CollectionType::class, ['allow_add'=>true])
            ->add('rue', TextType::class)//$rue getRue() en fonction du lieu selectionné
            ->add('codePostal', TextType::class)//$codePostal getCodePostal()
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveEtPublier', SubmitType::class, ['label' => 'Publier'])
        ;
        $formModifier = function (FormInterface $form, Ville $ville = null){
            $lieux = null === $ville ? [] : $ville->getLieux();

            $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'placeholder' => '',
                'choices' => $lieux,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
          function (FormEvent $event) use ($formModifier){
                $data = $event->getData();
                $ville = null === $data ? null : $data->getVille();
                $formModifier($event->getForm(), $ville);
          }
        );

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($formModifier){
                $ville = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $ville);
            }
        );


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
