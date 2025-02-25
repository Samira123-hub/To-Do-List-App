<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'attr'=>[
                    'placeholder'=>'Username',
                    'class'=>'input',
                ],
                'label'=>false,
                'required'=>false,
            ])
            ->add('email',EmailType::class,[
                'attr'=>[
                    'placeholder'=>'Email',
                    'class'=>'input',

                ],
                'label'=>false,
                'required'=>false,
            ])
            ->add('password', PasswordType::class,[
                'attr'=>[
                    'placeholder'=>'Password',
                    'class'=>'input',
                ],
                'label'=>false,
                'required'=>false,
            ])
            ->add('image',FileType::class,[
                'attr'=>[
                    'placeholder'=>'Image',
                    'class'=>'input',
                ],
                'label'=>false,
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
