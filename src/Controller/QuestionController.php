<?php
namespace App\Controller;

use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use Sentry\State\HubInterface;
use Psr\Log\LoggerInterface;
use App\Service\MarkdownHelper;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
     * @Route("/{page<\d+>}",name="app_homepage")
     */
    public function homepage(QuestionRepository $repository, Request $request, int $page = 1)
    {
        // $repository = $entityManager->getRepository(Question::class);
        $queryBuilder = $repository->createAskedOrderedByNewestQueryBuilder();

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($queryBuilder)
        );

        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        return $this->render('question/homepage.html.twig', [
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager){
        return new Response('Sounds like a GREAT feature for V2!');
    }


     /**
     * @Route("/questions/{slug}",name="app_question_show")
     */
    public function show(Question $question)
    {       
        if ($this->isDebug) {

            $this->logger->info('We are in Debug Mode!');
            
        }

         $answers = $question->getAnswers();
        
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