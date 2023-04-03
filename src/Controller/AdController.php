<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Factory\UserFactory;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'ad_')]
class AdController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(AdRepository $repository): Response
    {
        return $this->render('ad/index.html.twig', ['adList' => $repository->findAll()]);
    }

    #[Route('/form', name: 'new')]
    public function addEdit(StatusRepository       $statusRepository,
                            UserRepository         $userRepository,
                            EntityManagerInterface $em,
                            Request                $request): Response
    {
        $ad = new Ad();

        $ad->setAuthor($userRepository->findOneBy(['id' => '4']));
        $ad->setStatus($statusRepository->findOneBy(['name' => 'en cours']));

        $form = $this->createForm(AdType::class, $ad, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('ad_index');
        }

        return $this->render(
            'ad/form.html.twig',
            [
                'title'  => 'Nouvelle annonce',
                'AdForm' => $form->createView()
            ]
        );
    }
}