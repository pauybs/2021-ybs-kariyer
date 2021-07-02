<?php

namespace App\Logic;


class EvaluationMessageLogic
{

    /**
     * @param $data
     * @return EvaluationMessage
     */
    public function saveEvaluationMessage($data): EvaluationMessage
    {
//        $data = [
//            "message" => "ABC Nedeni yüzündne reddedikt",
//            "entity" => "Blog",
//            "entityId" => "asfa-asfdsa-fdsafda-fdasf"
//        ];
        $entityManager = $this->entityManager;
        $evaluationMessage = new EvaluationMessage();
        $evaluationMessage->setMessage($data["message"]);
        switch ($data["entity"]) {
            case "Blog":
                $evaluationMessage->setBlog($entityManager->find(Blog::class, $data["entityId"]));
                break;
            case "JobAd":
                $evaluationMessage->setJobAd($entityManager->find(JobAd::class, $data["entityId"]));
                break;
            case "InternAd":
                $evaluationMessage->setInternAd($entityManager->find(InternAd::class, $data["entityId"]));
                break;
            case "Student":
                $evaluationMessage->setStudent($entityManager->find(Student::class, $data["entityId"]));
                break;
            case "Graduated":
                $evaluationMessage->setGraduated($entityManager->find(Graduated::class, $data["entityId"]));
                break;
            case "Question":
                $evaluationMessage->setQuestion($entityManager->find(Question::class, $data["entityId"]));
                break;
            case "UniversityManagerApplication":
                $evaluationMessage->setUniversityManagerApplication($entityManager->find(UniversityManagerApplication::class, $data["entityId"]));
                break;
        }
        $entityManager->persist($evaluationMessage);
        $entityManager->flush();
        return $evaluationMessage;
    }

    /**
     * @param $entity
     * @param $entityId
     * @return EvaluationMessage|null
     */
    public function getEvaluationMessage($entity, $entityId): ?EvaluationMessage
    {
        switch ($entity) {
            case "Blog":
                return $this->evaluationMessageRepository->findOneBy(["blog"=>$entityId]);
            case "JobAd":
                return $this->evaluationMessageRepository->findOneBy(["jobAd"=>$entityId]);
            case "InternAd":
                return $this->evaluationMessageRepository->findOneBy(["internAd"=>$entityId]);
            case "Student":
                return $this->evaluationMessageRepository->findOneBy(["student"=>$entityId]);
            case "Graduated":
                return $this->evaluationMessageRepository->findOneBy(["graduated"=>$entityId]);
            case "Question":
                return $this->evaluationMessageRepository->findOneBy(["question"=>$entityId]);
            case "UniversityManagerApplication":
                return $this->evaluationMessageRepository->findOneBy(["universityManagerApplication"=>$entityId]);
        }
        return null;
    }
}