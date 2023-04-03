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

    #[Route('/details/{id}', name: 'details', requirements: ['id' => '\d+'])]
    public function details(AdRepository $repository, Ad $ad): Response
    {
        $fundAd = $repository->find($ad);
        return $this->render('ad/details.html.twig', ['ad' => $fundAd]);
    }

    #[Route('/form', name: 'new')]
    #[Route('form/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function addEdit(StatusRepository       $statusRepository,
                            EntityManagerInterface $em,
                            Request                $request,
                            Ad                     $ad = null): Response
    {
        if ($ad == null) {
            $ad = new Ad();
            $ad->setAuthor($this->getUser());
            $ad->setStatus($statusRepository->findOneBy(['name' => 'en cours']));
        }

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

    #[Route('/search/', name: 'search')]
    public function search(AdRepository $repository, Request $request): Response
    {
        $searchInput = $request->query->get('research');
        $adList = $repository->searchAd($searchInput);
        return $this->render('ad/search.html.twig', ['search' => $adList]);
    }
}