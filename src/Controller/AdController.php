<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Status;
use App\Factory\UserFactory;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Security\Voter\AdVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        return $this->render('ad/details.html.twig', ['ad' => $ad]);
    }

    #[isGranted('ROLE_USER')]
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
        } else {
            $this->denyAccessUnlessGranted(AdVoter::EDIT, $ad);
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

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancelAd(Ad                     $ad,
                             StatusRepository       $repository,
                             EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(AdVoter::EDIT, $ad);
        $ad->setStatus($repository->findOneBy(['name' => 'annulée']));
        $entityManager->flush();

        return $this->redirectToRoute('ad_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteAd(Ad                     $ad,
                             StatusRepository       $repository,
                             EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(AdVoter::EDIT, $ad);
        $ad->setStatus($repository->findOneBy(['name' => 'terminée']));
        $entityManager->flush();

        return $this->redirectToRoute('ad_index');
    }
}