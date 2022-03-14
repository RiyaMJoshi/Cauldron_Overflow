<?php
namespace App\Controller;

use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use Sentry\State\HubInterface;
use Psr\Log\LoggerInterface;
use App\Service\MarkdownHelper;
// use Symfony\Contracts\Cache\CacheInterface;
// use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{

    private $logger;
    private $isDebug;


    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(QuestionRepository $repository)
    {
        // $repository = $entityManager->getRepository(Question::class);
        $questions = $repository->findAllAskedOrderedByNewest();

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions,
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager){
        $question = new Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-'.rand(0, 1000))
            ->setQuestion(<<<EOF
Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
to make my dishes wash themselves. But while I was casting it,
I slipped a little and I think `I also hit my pants with the spell`.
            
When I woke up this morning, I caught a quick glimpse of my pants
opening the front door and walking out! I've been out all afternoon
(with no pants mind you) searching for them.
            
Does anyone have a spell to call your pants back?
EOF            
        );

        if(rand(1, 10) > 2){
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $question->setVotes(rand(-20, 50));
        $entityManager->persist($question);
        $entityManager->flush();

        return new Response(sprintf(
            'Well hello! The shiny new question is id #%d, slug %s',
            $question->getId(),
            $question->getSlug()
        ));
    }


     /**
     * @Route("/questions/{slug}",name="app_question_show")
     */
    public function show(Question $question)
    {       
        if ($this->isDebug) {

            $this->logger->info('We are in Debug Mode!');
            
        }

        $answers=['make sure your cat is sitting `perfectly`',
                'furry shoes better than cat',
                'try it backwards'    
    ];

    // $questionText="I\'ve been turned into a cat, any thoughts on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.";
    // $parsedQuestionText = $markdownHelper->parse($questionText);

      
        /* return $this->render('question/show.html.twig',[
            'question'=> ucwords(str_replace('-', ' ', $slug)),
            'questionText'=>$parsedQuestionText,
            'answers'=>$answers,
        ]);
        */

        return $this->render('question/show.html.twig',[
            'question'=> $question,
            'answers'=>$answers,
        ]);
        
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager){
        $direction = $request->request->get('direction');

        if($direction === 'up'){
            $question->upVote();
        }
        else if($direction === 'down'){
            $question->downVote();
        }
        
        $entityManager->flush();

        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug(),
        ]);
    }
}



?>