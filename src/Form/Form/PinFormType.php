<?php

namespace App\Form\Form;

use App\Entity\Pin;
use App\Enum\PinTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PinFormType extends AbstractType
{
    public function __construct(
            private readonly TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('title', TextType::class, [
                        'label' => $this->translator->trans('title'),
                        'row_attr' => [
                                'class' => 'form-floating',
                        ],
                        'attr' => [
                                'placeholder' => $this->translator->trans('title'),
                        ],
                ])
                ->add('description', TextType::class, [
                        'label' => $this->translator->trans('description'),
                        'row_attr' => [
                                'class' => 'form-floating',
                        ],
                        'attr' => [
                                'placeholder' => $this->translator->trans('description'),
                        ],
                ])
                ->add('url', UrlType::class, [
                        'label' => $this->translator->trans('url'),
                        'row_attr' => [
                                'class' => 'form-floating',
                        ],
                        'attr' => [
                                'placeholder' => $this->translator->trans('url'),
                        ],
                ])
                ->add('email', TextType::class, [
                        'mapped' => false,
                        'label' => $this->translator->trans('email'),
                        'row_attr' => [
                                'class' => 'form-floating',
                        ],
                        'attr' => [
                                'placeholder' => $this->translator->trans('email'),
                        ],
                ])
                ->add('pinTypeEnum', EnumType::class, [
                        'class' => PinTypeEnum::class,
                        'choice_label' => 'value',
                        'autocomplete' => true,
                        'row_attr' => [
                                'class' => 'form-floating',
                        ],
                        'attr' => [
                                'placeholder' => $this->translator->trans('email'),
                        ],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'data_class' => Pin::class,
        ]);
    }
}