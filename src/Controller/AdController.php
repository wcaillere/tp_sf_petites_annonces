<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'ad_')]
class AdController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(AdRepository $repository):Response {
        return $this->render('ad/index.html.twig', ['adList' => $repository->findAll()]);
    }
}