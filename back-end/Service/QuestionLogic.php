<?php

namespace App\Logic;


class QuestionLogic
{

    /**
     * @param $questionTitle
     * @return Question|null
     */
    public function getQuestionFromTitle($questionTitle): ?Question
    {
        return $this->questionRepository->findOneBy(["questionTitle" => $questionTitle,"isDeleted"=>0]);
    }


    /**
     * @param $userId
     * @return Question|null
     */
    public function createQuestion($userId): ?Question
    {
        $question = new Question();

        $question
            ->setQuestionTitle($questionTitle)
            ->setQuestionContent($questionContent)
            ->setUser($this->entityManager->find(User::class, $userId))
            ->setViews(0)
            ->setStatus(0)
            ->setSlug($this->createSlugify($questionTitle))
            ->setCreatedAt(new \DateTime())
            ->setIsDeleted(0);
        $this->entityManager->persist($question);
        $this->entityManager->flush();

        return $question;

    }


    /**
     * @param $slug
     * @param $userId
     * @return Question|bool
     */
    public function updateQuestion($slug,$userId): ?Question
    {
        if($question instanceof Question)
        {
            $question
                ->setQuestionTitle($questionTitle)
                ->setQuestionContent($questionContent)
                ->setSlug($this->createSlugify($questionTitle))
                ->setStatus(0)
                ->setIsDeleted(0)
                ->setUpdateAt(new \DateTime());
            $this->entityManager->flush();

            return $question;
        } else {
            return false;
        }
    }

    /**
     * @param $slug
     * @param $userId
     * @return null|Question
     */
    public function removeQuestion($slug,$userId): ?Question
    {
        $question = $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId]);
        if($question instanceof Question)
        {
            $question->setIsDeleted(1);
            $this->entityManager->flush();

            return $question;
        } else {
            return null;
        }
    }

    /**
     * @param $slug
     * @param $status
     * @return bool|Question
     */
    public function evaluationQuestion($slug,$status): ?Question
    {
        $question = $this->questionRepository->findOneBy(["slug"=>$slug]);
        if($question instanceof Question)
        {
            $question->setStatus($status);
            $this->entityManager->flush();

            return $question;
        } else {
            return false;
        }
    }

    /**
     * @param $slug
     * @param $userId
     * @return bool|Question
     */
    public function closeStatusQuestion($slug, $userId): ?Question
    {
        $question = $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId]);
        if($question instanceof Question)
        {
            $question->setStatus(0);
            $this->entityManager->flush();

            return $question;
        } else {
            return false;
        }
    }
}