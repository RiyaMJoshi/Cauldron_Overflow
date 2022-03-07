<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController{

    /**
     * @Route("/")
     */
    public function homepage()
    {
        return new Response("What an idea sirJi..!");
    }

    /**
     * @Route("questions/{slug}")
     */
    public function show($slug){
        $answers = [
            'Ok.. Answer 1',
            'Not That Good.. Answer 2',
            'Perfect.. Answer 3'
        ];
        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-',' ',$slug)),
            'answers' => $answers
        ]);
        // return new Response(sprintf('Future page to show question "%s"!',
        // ucwords(str_replace('-',' ',$slug))
    //));
    }
}
?>