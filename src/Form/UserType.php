<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'Введите email'
            ))
            ->add('fullName', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('hasRoleAdmin', ChoiceType::class, [
                'required' => true,
                'label' => 'Права',
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'Администратор' => 'Yes',
                    'Пользователь' => 'No'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Пароль',
                ),
                'second_options' => array(
                    'label' => 'Повтор пароля',
                ),
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-primary mb-5 mt-1'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
