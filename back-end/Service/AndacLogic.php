<?php
namespace App\Logic;


class AndacLogic
{
    /**
     * @param $ownerUser
     * @param $writerUser
     * @param $content
     * @param $universityId
     * @return Andac|null
     */
    public function createAndac($ownerUser,$writerUser,$content,$universityId): ?Andac
    {
        $request = $this->requestStack->getCurrentRequest();
        if($this->andacRepository->findOneBy(["ownerUser"=>$ownerUser,"writerUser"=>$writerUser,"university"=>$universityId]) instanceof Andac)
        {
            return null;
        }
        $andac = new Andac();
        $andac
            ->setOwnerUser($this->entityManager->find(User::class, $ownerUser))
            ->setWriterUser($this->entityManager->find(User::class, $writerUser))
            ->setUniversity($this->entityManager->find(University::class, $universityId))
            ->setContent($content)
            ->setStatus(0)
            ->setIsDeleted(0)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($andac);
        $this->entityManager->flush();
        return $andac;
    }

    /**
     * @param $id
     * @param $content
     * @return Andac|null
     */
    public function updateAndac($id,$content): ?Andac
    {
        $request = $this->requestStack->getCurrentRequest();
        $andac = $this->andacRepository->find($id);
        $andac
            ->setContent($content)
            ->setUpdateAt(new \DateTime());
        $this->entityManager->persist($andac);
        $this->entityManager->flush();
        return $andac;
    }

    public function removeAndac($id): ?Andac
    {
        $andac = $this->andacRepository->find($id);
        $andac
            ->setIsDeleted(1)
            ->setStatus(0);
        $this->entityManager->persist($andac);
        $this->entityManager->flush();
        return $andac;
    }

    public function approvedAndac($id): ?Andac
    {
        $andac = $this->andacRepository->find($id);
        $andac
            ->setStatus(1);
        $this->entityManager->persist($andac);
        $this->entityManager->flush();
        return $andac;
    }

}