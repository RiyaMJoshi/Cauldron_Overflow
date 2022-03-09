<?php

namespace App\Controller;

use Twig\Environment;
use App\Service\MarkdownHelper;
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
    public function show($slug, MarkdownHelper $markdownHelper){
        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        $questionText = 'I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';
        $parsedQuestionText = $parsedQuestionText = $markdownHelper->parse($questionText);
        
        // dump($cache);
        // dump($this);
        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-',' ',$slug)),
            'questionText' => $parsedQuestionText,
            'answers' => $answers
        ]);
        // return new Response(sprintf('Future page to show question "%s"!',
        // ucwords(str_replace('-',' ',$slug))
    //));
    }
}
?>