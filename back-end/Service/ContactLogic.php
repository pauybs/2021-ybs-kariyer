<?php

namespace App\Logic;

class ContactLogic
{

    /**
     * @param $fullName
     * @param $email
     * @param $content
     * @return Contact|null
     */
    public function createContact($fullName, $email, $content): ?Contact
    {
        $contact = new Contact();
        $contact
            ->setFullName($fullName)
            ->setEmail($email)
            ->setContent($content)
            ->setStatus(1)
            ->setIp($_SERVER["REMOTE_ADDR"])
            ->setCreatedAt(new \DateTime());
         $this->entityManager->persist($contact);
         $this->entityManager->flush();
         return $contact;
    }

    /**
     * @param $id
     * @param $status
     * @return Contact|null
     */
    public function evaluationContact($id,$status): ?Contact
    {
        $contact = $this->contactRepository->findOneBy(["id"=>$id]);
        $contact
            ->setStatus($status)
            ->setUpdateAt(new \DateTime());
        $this->entityManager->flush();
        return $contact;
    }

}