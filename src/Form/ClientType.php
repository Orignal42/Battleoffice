<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('address_line1')
            ->add('address_line2')
            ->add('city')
            ->add('zipcode')
            ->add('country', ChoiceType::class, [
                'mapped' => false,    
                'choices'  => [    
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                    'Luxembourg'=>'Luxembourg'
                ],
            ])
            ->add('phone')
            ->add('email', RepeatedType::class,[
            
       
                'first_options'  => ['label' => 'Email'],
                'second_options' => ['label' => 'Confirm_Email']
            ])  
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
