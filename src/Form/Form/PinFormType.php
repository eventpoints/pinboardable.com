<?php

declare(strict_types=1);

namespace App\Form\Form;

use App\Entity\Pin;
use App\Entity\Tag;
use App\Enum\PinTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
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
                'label' => ucfirst($this->translator->trans('purpose')),
                'help' => 'What are you promoting?',
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('purpose')),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => ucfirst($this->translator->trans('description')),
                'constraints' => [
                    new Length([
                        'min' => 20,
                        'max' => 1000,
                        'minMessage' => 'Your first name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
                    ]),
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('description')),
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
                'label' => ucfirst($this->translator->trans('email')),
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'placeholder' => ucfirst($this->translator->trans('email')),
                ],
            ])
            ->add('pinTypeEnum', EnumType::class, [
                'label' => false,
                'class' => PinTypeEnum::class,
                'choice_label' => 'value',
                'autocomplete' => true,
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'placeholder' => $this->translator->trans('pin-type'),
                ],
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('country'),
                ],
                'help' => 'Leave this empty if only digital.',
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'autocomplete' => true,
            ])
            ->add('tags', EntityType::class, [
                'label' => false,
                'class' => Tag::class,
                'choice_label' => function (Tag $tag): string {
                    return $this->translator->trans($tag->getTitle());
                },
                'translation_domain' => false,
                'multiple' => true,
                'autocomplete' => true,
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'placeholder' => $this->translator->trans('tags'),
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
