<?php

namespace App\Logic;

class UniversityLogic
{
    /**
     * @param $universityName
     * @return University|null
     */
    public function getUniversityFromName($universityName): ?University
    {
        return $this->universityRepository->findOneBy(["universityName" => $universityName]);
    }


    public function createUniversity(): ?University
    {
        $university = new University();

        $university
            ->setUniversityName($universityName)
            ->setUniversityContent($universityContent)
            ->setUniversityLogo($universityLogo)
            ->setUniversityCity($this->entityManager->find(City::class, $universityCity))
            ->setStatus(1)
            ->setSlug($this->createSlugify($universityName))
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($university);
        $this->entityManager->flush();

        return $university;

    }


    /**
     * @param $id
     * @return University|null
     */
    public function updateUniversity($id): ?University
    {
        if($university instanceof University)
        {
            $university
                ->setUniversityName($universityName)
                ->setUniversityContent($universityContent)
                ->setUniversityLogo($universityLogo)
                ->setUniversityCity($this->entityManager->find(City::class, $universityCity))
                ->setSlug($this->createSlugify($universityName))
                ->setUpdateAt(new \DateTime());
            $this->entityManager->flush();

            return $university;
        } else {
            return null;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeUniversity($id): bool
    {
        if($university instanceof University)
        {
            if(!$university->getGraduatedUniversity()->getValues() &&
                !$university->getStudentUniversity()->getValues()  &&
                !$university->getUniversityManagerUniversity()->getValues()
            )
            {
                $this->entityManager->remove($university);
                $this->entityManager->flush();

                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }
}