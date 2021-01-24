<?php

namespace App\Logic;


class QuestionAnswerLogic
{

    /**
     * @param $userId
     * @return QuestionAnswer|null
     */
    public function createQuestionAnswer($userId): ?QuestionAnswer
    {
        $questionAnswer = new QuestionAnswer();
        $questionAnswer
            ->setQuestion($this->entityManager->find(Question::class,$questionId))
            ->setAnswer($answer)
            ->setUser($this->entityManager->find(User::class, $userId))
            ->setStatus(1)
            ->setCreatedAt(new \DateTime())
            ->setIsDeleted(0);
        $this->entityManager->persist($questionAnswer);
        $this->entityManager->flush();

        return $questionAnswer;

    }


    /**
     * @param $userId
     * @return QuestionAnswer|bool
     */
    public function updateQuestionAnswer($userId,$id): ?QuestionAnswer
    {
        $questionAnswer = $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId]);
        if($questionAnswer instanceof QuestionAnswer)
        {
            $questionAnswer
                ->setAnswer($answer)
                ->setStatus(1)
                ->setUpdateAt(new \DateTime())
                ->setIsDeleted(0);
            $this->entityManager->flush();

            return $questionAnswer;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @param $userId
     * @return null|QuestionAnswer
     */
    public function removeQuestionAnswer($id,$userId): ?QuestionAnswer
    {
        $questionAnswer = $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId]);
        if($questionAnswer instanceof QuestionAnswer)
        {
            $questionAnswer->setIsDeleted(1);
            $this->entityManager->flush();

            return $questionAnswer;
        } else {
            return null;
        }
    }

}