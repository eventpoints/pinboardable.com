<?php

declare(strict_types=1);

namespace App\Form\Filter;

use App\DataTransferObject\PinFilterDto;
use App\Enum\PinTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'required' => false,
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
            'data_class' => PinFilterDto::class,
        ]);
    }
}
