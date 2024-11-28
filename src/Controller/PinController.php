<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\PinFilterDto;
use App\Entity\Pin;
use App\Form\Filter\PinFilterType;
use App\Form\Form\PinFormType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PinController extends AbstractController
{
    public function __construct(
        private readonly PinRepository $pinRepository,
        private readonly UserRepository $userRepository,
        private readonly HttpClientInterface $cloudflareTurnstileClient
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
                'pins' => $pins,
            ]);
        }

        return $this->render('pins/index.html.twig', [
            'pinFilter' => $pinFilter,
            'pins' => $pins,
        ]);
    }

    #[Route(path: '/create', name: 'create_pin')]
    public function create(Request $request): Response
    {
        $pin = new Pin();
        $pinForm = $this->createForm(PinFormType::class, $pin);
        $pinForm->handleRequest($request);
        if ($pinForm->isSubmitted() && $pinForm->isValid()) {

            $response = $this->cloudflareTurnstileClient->request(Request::METHOD_POST, '/turnstile/v0/siteverify', [
                'body' => [
                    'secret' => $this->getParameter('CLOUDFLARE_TURNSTILE_PRIVATE_KEY'),
                    'response' => $request->request->get('cf-turnstile-response'),
                    'ip' => $request->getClientIp(),
                ],
            ]);

            $isCaptchaSuccessful = json_decode($response->getContent())->success;

            if (! $isCaptchaSuccessful) {
                $pinForm->addError(new FormError(message: 'Captcha verification failed. Please try again.'));
            } else {
                $email = $pinForm->get('email')->getData();
                $user = $this->userRepository->findByEmailOrCreate(email: $email);
                $this->userRepository->save(entity: $user, flush: true);

                $pin->setOwner($user);
                foreach ($pin->getTags() as $tag) {
                    $tag->addPin($pin);
                }
                $this->pinRepository->save(entity: $pin, flush: true);

                $id = $pin->getId();
                return $this->redirectToRoute('landing', [
                    '_fragment' => "pin_$id",
                ]);
            }
        }

        return $this->render('pins/create.html.twig', [
            'pinForm' => $pinForm,
        ]);
    }
}
