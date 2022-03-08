<?php

namespace App\Controller;

use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController{

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(Environment $twigEnvironment)
    {   
        /*
            // fun example of using twig services directly
            $html = $twigEnvironment->render('question/homepage.html.twig');
            return new Response($html);
        */
        return $this->render('question/homepage.html.twig');
    }

    /**
     * @Route("questions/{slug}", name="app_question_show")
     */
    public function show($slug){
        $answers = [
            'Ok.. Answer 1',
            'Not That Good.. Answer 2',
            'Perfect.. Answer 3'
        ];

        dump($this);
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