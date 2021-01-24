<?php

namespace App\Logic;


class CityLogic
{

    /**
     * @param $cityName
     * @return City|null
     */
    public function getCityFromName($cityName): ?City
    {
        return $this->cityRepository->findOneBy(["cityName" => $cityName]);
    }


    /**
     * @return City|null
     */
    public function createCity($cityName,$cityCode): ?City
    {
        $request = $this->requestStack->getCurrentRequest();

        $city = new City();

        $city
            ->setCityName($cityName)
            ->setCityCode($cityCode);
        $this->entityManager->persist($city);
        $this->entityManager->flush();

        return $city;

    }

}