<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, ['disabled' => true])
            ->add('name')
            ->add('email')
            ->add('account')
            ->add('reset_token', null, [
                'label' => 'Email Token',
                'disabled' => true,
                'attr' => ['placeholder' => 'Um ein Token zu erzeugen müssen Email und Account gesetzt sein.']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
