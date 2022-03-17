<?php

namespace App\DataFixtures;

//use Zenstruck\Foundry\TProxiedObject;
use App\Entity\Tag;
use App\Entity\Answer;
use App\Entity\Question;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionTagFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(100);

        QuestionTagFactory::createMany(10);
        return;

        $questions = QuestionFactory::new()->createMany(20, function(){
            return [
                'tags' => TagFactory::randomRange(0, 5),
            ];
        });

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5)
        ;
    
        
        AnswerFactory::createMany(100, function() use ($questions){
            return [
                'question' => $questions[array_rand($questions)],
            ];
        });
     
        AnswerFactory::new(function() use ($questions){
            return [
                'question' => $questions[array_rand($questions)],
            ];
        })->needsApproval()->many(20)->create();

        $manager->flush();
    }
}
