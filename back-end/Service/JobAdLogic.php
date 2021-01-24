<?php

namespace App\Logic;


class JobAdLogic
{
    /**
     * @param $userId
     * @return JobAd|null
     */
    public function createJobAd($userId): ?JobAd
    {
        $jobAd = new JobAd();

        $jobAd
            ->setJobTitle($jobTitle)
            ->setJobContent($jobContent)
            ->setJobCompany($jobCompany)
            ->setJobCity($this->entityManager->find(City::class, $jobCity))
            ->setJobPosition($this->entityManager->find(Position::class, $jobPosition))
            ->setJobType($jobType)
            ->setUser($this->entityManager->find(User::class, $userId))
            ->setJobViews(0)
            ->setStatus(0)
            ->setSlug($slug)
            ->setCreatedAt(new \DateTime())
            ->setIsDeleted(0);
        if($workplaceSector)
        {
            $jobAd->setWorkplaceSector($this->entityManager->find(WorkplaceSector::class, $workplaceSector));
        }
        $this->entityManager->persist($jobAd);
        $this->entityManager->flush();

        return $jobAd;

    }


    /**
     * @param $slug
     * @param $userId
     * @return JobAd|bool
     */
    public function updateJobAd($slug,$userId): ?JobAd
    {
        if($jobAd instanceof JobAd)
        {

            $slug = $this->createSlugify($jobTitle).'-'.substr($userId, 0, 4);
            $jobAd
                ->setJobTitle($jobTitle)
                ->setJobContent($jobContent)
                ->setJobCompany($jobCompany)
                ->setJobCity($this->entityManager->find(City::class, $jobCity))
                ->setJobPosition($this->entityManager->find(Position::class, $jobPosition))
                ->setJobType($jobType)
                ->setStatus(0)
                ->setSlug($slug)
                ->setUpdateAt(new \DateTime());
            if($workplaceSector)
            {
                $jobAd->setWorkplaceSector($this->entityManager->find(WorkplaceSector::class, $workplaceSector));
            }else {
                $jobAd->setWorkplaceSector(null);
            }
            $this->entityManager->flush();

            return $jobAd;
        } else {
            return false;
        }
    }

    /**
     * @param $slug
     * @param $userId
     * @return null|JobAd
     */
    public function removeJobAd($slug,$userId): ?JobAd
    {
        $jobAd = $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId]);
        if($jobAd instanceof JobAd)
        {
            $jobAd->setIsDeleted(1);
            $jobAd->setStatus(0);
            $this->entityManager->flush();

            return $jobAd;
        } else {
            return null;
        }
    }

    /**
     * @param $slug
     * @param $status
     * @return bool|JobAd
     */
    public function evaluationJobAd($slug,$status): ?JobAd
    {
        $jobAd = $this->jobAdRepository->findOneBy(["slug"=>$slug]);
        if($jobAd instanceof JobAd)
        {
            $jobAd->setStatus($status);
            $this->entityManager->flush();

            return $jobAd;
        } else {
            return false;
        }
    }

}