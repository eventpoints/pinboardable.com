<?php

namespace App\Controller;

use App\DataTransferObject\PinFilterDto;
use App\Entity\Pin;
use App\Form\Filter\PinFilterType;
use App\Form\Form\PinFormType;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PinController extends AbstractController
{


    public function __construct(
            private readonly PinRepository $pinRepository
    )
    {
    }

    #[Route(path: '/', name: 'landing')]
    public function index(Request $request): Response
    {
        $pinFilterDto = new PinFilterDto();
        $pins = $this->pinRepository->findByFilter(pinFilterDto: $pinFilterDto);

        $pinFilter = $this->createForm(PinFilterType::class, $pinFilterDto);
        $pinFilter->handleRequest($request);
        if ($pinFilter->isSubmitted() && $pinFilter->isValid()) {
            $pins = $this->pinRepository->findByFilter($pinFilterDto);
            return $this->render('pins/index.html.twig', [
                    'pinFilter' => $pinFilter,
                    'pins' => $pins
            ]);
        }

        return $this->render('pins/index.html.twig', [
                'pinFilter' => $pinFilter,
                'pins' => $pins
        ]);
    }

    #[Route(path: '/pins/create', name: 'create_pin')]
    public function create(Request $request): Response
    {
        $pin = new Pin();
        $pinForm = $this->createForm(PinFormType::class, $pin);
        $pinForm->handleRequest($request);
        if ($pinForm->isSubmitted() && $pinForm->isValid()) {

        }

        return $this->render('pins/create.html.twig', [
                'pinForm' => $pinForm
        ]);
    }

}