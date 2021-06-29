<?php

namespace App\Form;
use App\Entity\Client;
use App\Entity\LandingPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandingPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('client', ClientType::class)
        ->add('addressbilling', AddressBillingType::class)
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LandingPage::class,
        ]);
    }
}
