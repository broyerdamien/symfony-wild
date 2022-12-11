<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\ProgramType;
use App\Entity\Episode;
use App\Entity\Program;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;

#[Route('/program', name: 'program_')]

class ProgramController extends AbstractController

{

    #[Route('/', name: 'index')]

    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render(

            'program/index.html.twig',

            ['programs' => $programs]

        );
    }
    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $ProgramRepository): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $ProgramRepository->save($program, true);

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/show/{program}', name: 'show')]

    public function show(Program $program): Response
    {

        
        return $this->render('program/show.html.twig',
         [ 'program' => $program]);
    }

    #[Route('/{program}/season/{season}', name: 'season_show')]
    public function showSeason(Program $program, Season $season, )
    {

        return $this->render('program/season_show.html.twig', [
            "program" => $program,
            "season" => $season
        ]);
    }
    #[Route('/program/{programId}/season/{seasonId}/episode/{episodeId}', name: 'episode_show')]
    #[Entity('program', options: ['mapping' => ['programId' => 'id']])]
    #[Entity('season', options: ['mapping' => ['seasonId' => 'id']])]
    #[Entity('episode', options: ['mapping' => ['episodeId' => 'id']])]
    
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            "program" => $program,
            "season" => $season,
            "episode" => $episode
        ]);
    }
}

