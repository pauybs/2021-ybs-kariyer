<?php
namespace App\Controller;


class CityController extends AbstractController
{
    /**
     * @Route("/api/list-city", name="api_list_city_get_action", methods={"GET"})
     * @return array
     */
    public function listCityGetAction(): array
    {
        if($this->cityRepository->searchListCity($search))
        {
            $cityAll = $this->cityRepository->searchListCity($search);
            foreach ($cityAll as $item)
            {
                $cityData = [
                    'id' => $item->getId(),
                    'cityName' => $item->getCityName(),
                    'cityCode' => $item->getCityCode()
                ];
                $fullCity[] = $cityData;
            }
            if (isset($fullCity)) {

                $pagination = $this->paginator->paginate(
                    $fullCity, /* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    100 /*limit per page*/
                );

                return [
                    'status' => true,
                    'page'=>$pagination->getCurrentPageNumber(),
                    'pageCount'=>ceil($pagination->getTotalItemCount() / 100),
                    'pageItemCount'=>$pagination->count(),
                    'total'=>$pagination->getTotalItemCount(),
                    'data'=>$pagination->getItems(),
                    'code' => Response::HTTP_OK
                ];

            }
        }
        $payload->setStatus(false)->setMessages(['City bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

}