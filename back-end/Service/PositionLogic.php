<?php

namespace App\Logic;

class PositionLogic
{

    /**
     * @param $positionName
     * @return Position|null
     */
    public function getPositionFromName($positionName): ?Position
    {
        return $this->positionRepository->findOneBy(["positionName" => $positionName]);
    }


    /**
     * @return Position|null
     */
    public function createPosition(): ?Position
    {
        $position = new Position();

        $position
            ->setPositionName($positionName);
        $this->entityManager->persist($position);
        $this->entityManager->flush();

        return $position;

    }

    /**
     * @param $positionName
     * @return Position|null
     */
    public function createPositionAuto($positionName): ?Position
    {
        $position = new Position();

        $position
            ->setPositionName($positionName);
        $this->entityManager->persist($position);
        $this->entityManager->flush();

        return $position;

    }



    /**
     * @param $id
     * @return bool|Position
     */
    public function updatePosition($id): ?Position
    {
        if($position instanceof Position)
        {
            $position
                ->setPositionName($positionName);
            $this->entityManager->flush();

            return $position;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function removePosition($id): bool
    {
        $position = $this->positionRepository->find($id);
        if($position instanceof Position)
        {
                $this->entityManager->remove($position);
                $this->entityManager->flush();
                return true;


        } else {
            return false;
        }
    }
}