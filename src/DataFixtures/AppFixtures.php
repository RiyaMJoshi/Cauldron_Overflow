<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $questions = QuestionFactory::new()->createMany(20);

       QuestionFactory::new()
            ->unpublished()
            ->createMany(5)
        ;
          
        /*
        $answer = new Answer();
        $answer->setContent('This question is the best? I wish... I knew the answer.');
        $answer->setUsername('weaverryan');
       
        $question = new Question();
        $question->setName('How to un-disappear your wallet.');
        $question->setQuestion('... I should not have done this...');
        
        $answer->setQuestion($question);
        
        $manager->persist($answer);
        $manager->persist($question);
        */

        AnswerFactory::createMany(100, function() use ($questions){
            return [
                'question' => $questions[array_rand($questions)],
            ];
        });
        $manager->flush();
    }
}
