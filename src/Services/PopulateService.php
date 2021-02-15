<?php

namespace App\Services;

use App\Entity\Answer;
use App\Entity\Question;
use App\QuestionsCrawler\QuestionCrawler;
use Doctrine\ORM\EntityManagerInterface;


class PopulateService
{
    private $crawler;
    private $entityManager;

    public function __construct(QuestionCrawler $crawler, EntityManagerInterface $entityManager)
    {
        $this->crawler = $crawler;
        $this->entityManager = $entityManager;
    }

    public function persisting()
    {
        $data = $this->crawler->getDataFromAPI($this->entityManager);

        foreach ($data as $category => $questions) {
            foreach ($questions as $question) {
                if (!isset(QuestionCrawler::CATEGORIES_MAP[$question['category']])) {
                    continue;
                }
                $appIdCategory = QuestionCrawler::CATEGORIES_MAP[$question['category']];
                $questionObj = $this->persistQuestion($question, $appIdCategory, $this->entityManager);

                $this->persistAnswers($question, $questionObj, $this->entityManager);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param $question
     * @param Question $questionObj
     * @return Answer
     */
    public function createAnswer(string $text, bool $isCorrect, int $id): Answer
    {
        $correctAnswersObj = new Answer();
        $correctAnswersObj->setText($text);
        $correctAnswersObj->setIsCorrect($isCorrect);
        $correctAnswersObj->setIdQuestion($id);

        return $correctAnswersObj;
    }

    /**
     * @param $question
     * @param int $appIdCategory
     * @return Question
     */
    public function createQuestion($question, int $appIdCategory): Question
    {
        $questionObj = new Question();
        $questionObj->setText($question['question']);
        $questionObj->setDifficulty($question['difficulty']);
        $questionObj->setIdCategory($appIdCategory);
        $questionObj->setCreatedAt(new \DateTime());

        return $questionObj;
    }

    /**
     * @param $question
     * @param Question $questionObj
     * @param EntityManagerInterface $entityManager
     */
    public function persistAnswers($question, Question $questionObj, EntityManagerInterface $entityManager): void
    {
        $correctAnswersObj = $this->createAnswer($question['correct_answer'], true, $questionObj->getId());
        $entityManager->persist($correctAnswersObj);

        foreach ($question['incorrect_answers'] as $incorrectAnswer) {
            $incorrectAnswerObj = $this->createAnswer($incorrectAnswer, false, $questionObj->getId());
            $entityManager->persist($incorrectAnswerObj);
        }
    }

    /**
     * @param $question
     * @param int $appIdCategory
     * @param EntityManagerInterface $entityManager
     * @return Question
     */
    public function persistQuestion($question, int $appIdCategory, EntityManagerInterface $entityManager): Question
    {
        $questionObj = $this->createQuestion($question, $appIdCategory);
        $entityManager->persist($questionObj);
        $entityManager->flush();

        return $questionObj;
    }
}