<?php

declare(strict_types=1);

namespace App\Form\Filter;

use App\DataTransferObject\PinFilterDto;
use App\Entity\Tag;
use App\Enum\PinTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PinFilterType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod(Request::METHOD_GET)
            ->add('keyword', TextType::class, [
                'required' => false,
                'label' => $this->translator->trans('keyword'),
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'placeholder' => $this->translator->trans('keyword'),
                ],
            ])
            ->add('pinTypeEnum', EnumType::class, [
                'label' => false,
                'required' => false,
                'class' => PinTypeEnum::class,
                'choice_label' => 'value',
                'autocomplete' => true,
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => $this->translator->trans('category'),
                ],
            ])->add('tags', EntityType::class, [
                'label' => $this->translator->trans('tags'),
                'placeholder' => $this->translator->trans('tags'),
                'choice_label' => function (Tag $tag): string {
                    return $this->translator->trans($tag->getTitle());
                },
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'class' => Tag::class,
                'multiple' => true,
                'autocomplete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PinFilterDto::class,
        ]);
    }
}
