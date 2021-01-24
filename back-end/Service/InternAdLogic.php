<?php

namespace App\Logic;

class InternAdLogic
{

    /**
     * @param $userId
     * @return InternAd|null
     */
    public function createInternAd($userId): ?InternAd
    {
        $internAd = new InternAd();

        $internAd
            ->setInternTitle($internTitle)
            ->setInternContent($internContent)
            ->setInternCompany($internCompany)
            ->setInternCity($this->entityManager->find(City::class, $internCity))
            ->setInternPosition($this->entityManager->find(Position::class, $internPosition))
            ->setInternType($internType)
            ->setUser($this->entityManager->find(User::class, $userId))
            ->setInternViews(0)
            ->setStatus(0)
            ->setSlug($slug)
            ->setCreatedAt(new \DateTime())
            ->setIsDeleted(0);
        if($workplaceSector)
        {
            $internAd->setWorkplaceSector($this->entityManager->find(WorkplaceSector::class, $workplaceSector));
        }
        $this->entityManager->persist($internAd);
        $this->entityManager->flush();

        return $internAd;

    }


    /**
     * @param $slug
     * @param $userId
     * @return InternAd|bool
     */
    public function updateInternAd($slug,$userId): ?InternAd
    {
            $internAd
                ->setInternTitle($internTitle)
                ->setInternContent($internContent)
                ->setInternCompany($internCompany)
                ->setInternCity($this->entityManager->find(City::class, $internCity))
                ->setInternPosition($this->entityManager->find(Position::class, $internPosition))
                ->setInternType($internType)
                ->setStatus(0)
                ->setSlug($slug)
                ->setUpdateAt(new \DateTime());
            if($workplaceSector)
            {
                $internAd->setWorkplaceSector($this->entityManager->find(WorkplaceSector::class, $workplaceSector));
            }else {
                $internAd->setWorkplaceSector(null);
            }
            $this->entityManager->flush();

    }

    /**
     * @param $slug
     * @param $userId
     * @return null|InternAd
     */
    public function removeInternAd($slug,$userId): ?InternAd
    {
        $internAd = $this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId]);
        if($internAd instanceof InternAd)
        {
            $internAd->setIsDeleted(1);
            $internAd->setStatus(0);
            $this->entityManager->flush();
            return $internAd;
        } else {
            return null;
        }
    }

    /**
     * @param $slug
     * @param $status
     * @return bool|InternAd
     */
    public function evaluationInternAd($slug,$status): ?InternAd
    {
        $internAd = $this->internAdRepository->findOneBy(["slug"=>$slug]);
        if($internAd instanceof InternAd)
        {
            $internAd->setStatus($status);
            $this->entityManager->flush();

            return $internAd;
        } else {
            return false;
        }
    }


}