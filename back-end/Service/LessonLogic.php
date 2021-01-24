<?php

namespace App\Logic;

class LessonLogic
{
    /**
     * @param $lessonName
     * @return University|null
     */
    public function getLessonFromName($lessonName): ?Lesson
    {
        return $this->lessonRepository->findOneBy(["lessonName" => $lessonName]);
    }


    public function createLessonAuto($lessonName, $lessonContent): ?Lesson
    {
        $lesson = new Lesson();

        $lesson
            ->setLessonName($lessonName)
            ->setLessonContent($lessonContent)
            ->setStatus(1)
            ->setSlug($this->createSlugify($lessonName))
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($lesson);
        $this->entityManager->flush();

        return $lesson;

    }

    public function createLesson(): ?Lesson
    {
        $lesson = new Lesson();

        $lesson
            ->setLessonName($lessonName)
            ->setLessonContent($lessonContent)
            ->setStatus(1)
            ->setSlug($this->createSlugify($lessonName))
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($lesson);
        $this->entityManager->flush();

        return $lesson;

    }


    /**
     * @param $id
     * @return Lesson|bool
     */
    public function updateLesson($id): ?Lesson
    {
        if($lesson instanceof Lesson)
        {
            $lesson
                ->setLessonName($lessonName)
                ->setLessonContent($lessonContent)
                ->setSlug($this->createSlugify($lessonName))
                ->setUpdateAt(new \DateTime());
            $this->entityManager->flush();

            return $lesson;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeLesson($id): bool
    {
        $lesson = $this->lessonRepository->find($id);
        if($lesson instanceof Lesson)
        {
           $this->entityManager->remove($lesson);
           $this->entityManager->flush();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool|Lesson
     */
    public function onStatusLesson($id): ?Lesson
    {
        $lesson = $this->lessonRepository->find($id);
        if($lesson instanceof Lesson)
        {
            $lesson->setStatus(1);
            $this->entityManager->flush();

            return $lesson;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool|Lesson
     */
    public function closeStatusLesson($id): ?Lesson
    {
        $lesson = $this->lessonRepository->find($id);
        if($lesson instanceof Lesson)
        {
            $lesson->setStatus(0);
            $this->entityManager->flush();

            return $lesson;
        } else {
            return false;
        }
    }
}