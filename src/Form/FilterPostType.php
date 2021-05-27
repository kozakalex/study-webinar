<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sortby', ChoiceType::class, [
                'label' => 'Sort by: ',
                'choices' => [
                    'Date' => 'date',
                    'Title' => 'title',
                ]
            ])
            ->add('orderby', ChoiceType::class, [
                'label' => 'Order by: ',
                'choices' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
