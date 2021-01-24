<?php
namespace App\Controller;

class InternAdController extends AbstractController
{
    /**
     * @Route("/api/add-intern-ad", name="api_add_intern_ad_post_action", methods={"POST"})
     * @return array
     */
    public function addInternAdPostAction() : array
    {
        $userId = $this->getUser()->getId();
        if($this->internAdLogic->createInternAd($userId) instanceof InternAd)
        {
            $logData = [
                'user' => $this->getUser()->getId(),
                'content' => 'Yeni bir staj ilanı eklediniz.',
                'ip' => $_SERVER["REMOTE_ADDR"],
                'type' => 1
            ];
            $this->logService->saveLog($logData);
            $payload->setStatus(true)->setMessages(['Staj İlanı başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Staj İlan kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/update-intern-ad/{slug}", name="api_update_intern_ad_put_action", methods={"PUT"})
     * @param $slug
     * @return array
     */
    public function updateInternAdPutAction($slug) : array
    {
        $userId = $this->getUser()->getId();
        if($slug && $this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]) instanceof InternAd)
        {
            $internAd = $this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($internAd->getStatus() == 2)
            {
                $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            if($this->internAdLogic->updateInternAd($slug,$userId) instanceof InternAd)
            {
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => 'Bir iş ilanını güncellediniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);
                $payload->setStatus(true)->setMessages(['İlan başarılı şekilde güncellendi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(['İlan güncellenirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];

            }

        }

        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/update-intern-ad/{slug}", name="api_update_intern_ad_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function updateInternAdGetAction($slug) : array
    {
        $userId = $this->getUser()->getId();
        if($slug && $this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]) instanceof InternAd)
        {
            $internAd = $this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($internAd->getStatus() == 2)
            {
                $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            $internAdData = [
                'id' => $internAd->getId(),
                'internTitle' => $internAd->getInternTitle(),
                'internContent' => $internAd->getInternContent(),
                'internCompany' => $internAd->getInternCompany(),
                'status' => $internAd->getStatus(),
                'user'=>[
                    'name'=>$internAd->getUser()->getName(),
                    'surname'=>$internAd->getUser()->getSurname(),
                    'username'=>$internAd->getUser()->getUsernameProperty()
                ],
                'internCity'=>[
                    'id'=>$internAd->getInternCity()->getId(),
                    'cityName'=>$internAd->getInternCity()->getCityName()
                ],
                'internPosition'=>[
                    'id'=>$internAd->getInternPosition()->getId(),
                    'positionName'=>$internAd->getInternPosition()->getPositionName()
                ],
                'workplaceSector'=> $internAd->getWorkplaceSector() ? [
                  'id' => $internAd->getWorkplaceSector()->getId(),
                  'sectorName'=> $internAd->getWorkplaceSector()->getSectorName()
                ] : null,
                'internType'=>$internAd->getInternType(),
                'internViews'=>$internAd->getInternViews(),
                'slug' => $internAd->getSlug(),
                'createdAt' => $internAd->getCreatedAt(),
                'updateAt' => $internAd->getUpdateAt(),
                'isDeleted' => $internAd->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($internAdData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-intern-ad", name="api_list_intern_ad_get_action", methods={"GET"})
     * @return array
     */
    public function listInternAdGetAction()
    {
        if($this->internAdRepository->searchListInternAd($search))
        {
            $internAdAll = $this->internAdRepository->searchListInternAd($search);
            foreach ($internAdAll as $item)
            {
                $internAdData = [
                    'id' => $item->getId(),
                    'internTitle' => $item->getInternTitle(),
                    'internContent' => $item->getInternContent(),
                    'internCompany' => $item->getInternCompany(),
                    'status' => $item->getStatus(),
                    'user'=>[
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'internCity'=>[
                        'id'=>$item->getInternCity()->getId(),
                        'cityName'=>$item->getInternCity()->getCityName()
                    ],
                    'internPosition'=>[
                        'id'=>$item->getInternPosition()->getId(),
                        'positionName'=>$item->getInternPosition()->getPositionName()
                    ],
                    'workplaceSector'=> $item->getWorkplaceSector() ? [
                        'id' => $item->getWorkplaceSector()->getId(),
                        'sectorName'=> $item->getWorkplaceSector()->getSectorName()
                    ] : null,
                    'internType'=>$item->getInternType(),
                    'internViews'=>$item->getInternViews(),
                    'slug' => $item->getSlug(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt(),
                    'isDeleted' => $item->getIsDeleted()
                ];
                $fullInternAd[] = $internAdData;
            }
            if (isset($fullInternAd)) {

                $pagination = $this->paginator->paginate(
                    $fullInternAd, /* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    $pageSize /*limit per page*/
                );

                return [
                    'status' => true,
                    'page'=>$pagination->getCurrentPageNumber(),
                    'pageCount'=>ceil($pagination->getTotalItemCount() / $pageSize),
                    'pageItemCount'=>$pagination->count(),
                    'total'=>$pagination->getTotalItemCount(),
                    'data'=>$pagination->getItems(),
                    'code' => Response::HTTP_OK
                ];

            }
        }
        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-intern-ad", name="api_list_my_intern_ad_get_action", methods={"GET"})
     * @return array
     */
    public function listMyInternAdGetAction()
    {
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User) {
            $user = $this->userRepository->findOneBy(["username" => $username]);
            $MyInternAdPrivacy = $this->userMetaRepository->findOneBy(["user" => $user->getId(), "metaKey" => "_myInternAd"])->getMetaValue();
            if ($this->getUser() && $this->getUser()->getId() != $user->getId()) {
                if (!$MyInternAdPrivacy) {
                    $payload->setStatus(false)->setMessages(['Staj İlanı bulunamadı ya da gizli !']);
                    return [
                        'payload' => $payload,
                        'code' => Response::HTTP_NOT_FOUND
                    ];
                }
            }else {
                if(!$this->getUser() && !$MyInternAdPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }
            if ($this->internAdRepository->searchListMyInternAd($search, $username)) {
                $internAdAll = $this->internAdRepository->searchListMyInternAd($search, $username);
                foreach ($internAdAll as $item) {
                    $internAdData = [
                        'id' => $item->getId(),
                        'internTitle' => $item->getInternTitle(),
                        'internContent' => $item->getInternContent(),
                        'internCompany' => $item->getInternCompany(),
                        'status' => $item->getStatus(),
                        'user' => [
                            'name' => $item->getUser()->getName(),
                            'surname' => $item->getUser()->getSurname(),
                            'username' => $item->getUser()->getUsernameProperty()
                        ],
                        'internCity' => [
                            'id' => $item->getInternCity()->getId(),
                            'cityName' => $item->getInternCity()->getCityName()
                        ],
                        'internPosition' => [
                            'id' => $item->getInternPosition()->getId(),
                            'positionName' => $item->getInternPosition()->getPositionName()
                        ],
                        'workplaceSector' => $item->getWorkplaceSector() ? [
                            'id' => $item->getWorkplaceSector()->getId(),
                            'sectorName' => $item->getWorkplaceSector()->getSectorName()
                        ] : null,
                        'internType' => $item->getInternType(),
                        'internViews' => $item->getInternViews(),
                        'slug' => $item->getSlug(),
                        'createdAt' => $item->getCreatedAt(),
                        'updateAt' => $item->getUpdateAt(),
                        'isDeleted' => $item->getIsDeleted()
                    ];
                    $fullInternAd[] = $internAdData;
                }
                if (isset($fullInternAd)) {

                    $pagination = $this->paginator->paginate(
                        $fullInternAd, /* query NOT result */
                        $request->query->getInt('page', 1), /*page number*/
                        $pageSize /*limit per page*/
                    );

                    return [
                        'status' => true,
                        'page' => $pagination->getCurrentPageNumber(),
                        'pageCount' => ceil($pagination->getTotalItemCount() / $pageSize),
                        'pageItemCount' => $pagination->count(),
                        'total' => $pagination->getTotalItemCount(),
                        'data' => $pagination->getItems(),
                        'code' => Response::HTTP_OK
                    ];

                }
            }
        }
        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-intern-ad-evaluation", name="api_list_my_intern_ad_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function listMyInternAdEvaluationGetAction(): array
    {
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User) {
            $user = $this->userRepository->findOneBy(["username" => $username]);
            if($user->getId() != $this->getUser()->getId())
            {
                $payload->setStatus(false)->setMessages(['Profil sahibi görebilir !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            if ($this->internAdRepository->searchListMyInternAdEvaluation($search, $username)) {
                $internAdAll = $this->internAdRepository->searchListMyInternAdEvaluation($search, $username);
                foreach ($internAdAll as $item) {
                    $internAdData = [
                        'id' => $item->getId(),
                        'internTitle' => $item->getInternTitle(),
                        'internContent' => $item->getInternContent(),
                        'internCompany' => $item->getInternCompany(),
                        'status' => $item->getStatus(),
                        'user' => [
                            'name' => $item->getUser()->getName(),
                            'surname' => $item->getUser()->getSurname(),
                            'username' => $item->getUser()->getUsernameProperty()
                        ],
                        'internCity' => [
                            'id' => $item->getInternCity()->getId(),
                            'cityName' => $item->getInternCity()->getCityName()
                        ],
                        'internPosition' => [
                            'id' => $item->getInternPosition()->getId(),
                            'positionName' => $item->getInternPosition()->getPositionName()
                        ],
                        'workplaceSector' => $item->getWorkplaceSector() ? [
                            'id' => $item->getWorkplaceSector()->getId(),
                            'sectorName' => $item->getWorkplaceSector()->getSectorName()
                        ] : null,
                        'internType' => $item->getInternType(),
                        'internViews' => $item->getInternViews(),
                        'slug' => $item->getSlug(),
                        'createdAt' => $item->getCreatedAt(),
                        'updateAt' => $item->getUpdateAt(),
                        'isDeleted' => $item->getIsDeleted()
                    ];
                    $fullInternAd[] = $internAdData;
                }
                if (isset($fullInternAd)) {

                    $pagination = $this->paginator->paginate(
                        $fullInternAd, /* query NOT result */
                        $request->query->getInt('page', 1), /*page number*/
                        $pageSize /*limit per page*/
                    );

                    return [
                        'status' => true,
                        'page' => $pagination->getCurrentPageNumber(),
                        'pageCount' => ceil($pagination->getTotalItemCount() / $pageSize),
                        'pageItemCount' => $pagination->count(),
                        'total' => $pagination->getTotalItemCount(),
                        'data' => $pagination->getItems(),
                        'code' => Response::HTTP_OK
                    ];

                }
            }
        }
        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/delete-intern-ad/{slug}", name="api_intern_ad_delete_action", methods={"DELETE"})
     * @param $slug
     * @return array
     */
    public function internAdDeleteAction($slug)
    {
        $payload = new Payload();
        $userId = $this->getUser()->getId();
        if($slug && $this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId]) instanceof InternAd)
        {

            if($this->internAdLogic->removeInternAd($slug, $userId) instanceof InternAd)
            {
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId])->getInternTitle().' isimli ilanı  sildiniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);

                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$this->internAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId])->getInternTitle().' isimli ilan silindi.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 5
                ];
                $this->logService->saveLog($logData);

                $payload->setStatus(true)->setMessages(['İlan başarılı şekilde silindi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_ACCEPTED
                ];
            } else {
                $payload->setStatus(false)->setMessages(['İlan silinirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }

        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);

        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }




    /**
     * @Route("/api/get-intern-ad/{slug}", name="api_get_intern_ad_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function getInternAdGetAction($slug) : array
    {
        if($slug && $this->internAdRepository->findOneBy(["slug"=>$slug,"isDeleted"=>0,"status"=>1]) instanceof InternAd)
        {
            $internAd = $this->internAdRepository->findOneBy(["slug"=>$slug,"isDeleted"=>0,"status"=>1]);
            $internAdData = [
                'id' => $internAd->getId(),
                'internTitle' => $internAd->getInternTitle(),
                'internContent' => $internAd->getInternContent(),
                'internCompany' => $internAd->getInternCompany(),
                'status' => $internAd->getStatus(),
                'user'=>[
                    'name'=>$internAd->getUser()->getName(),
                    'surname'=>$internAd->getUser()->getSurname(),
                    'username'=>$internAd->getUser()->getUsernameProperty()
                ],
                'internCity'=>[
                    'id'=>$internAd->getInternCity()->getId(),
                    'cityName'=>$internAd->getInternCity()->getCityName()
                ],
                'internPosition'=>[
                    'id'=>$internAd->getInternPosition()->getId(),
                    'positionName'=>$internAd->getInternPosition()->getPositionName()
                ],
                'workplaceSector'=> $internAd->getWorkplaceSector() ? [
                    'id' => $internAd->getWorkplaceSector()->getId(),
                    'sectorName'=> $internAd->getWorkplaceSector()->getSectorName()
                ] : null,
                'internType'=>$internAd->getInternType(),
                'internViews'=>$internAd->getInternViews(),
                'slug' => $internAd->getSlug(),
                'createdAt' => $internAd->getCreatedAt(),
                'updateAt' => $internAd->getUpdateAt(),
                'isDeleted' => $internAd->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($internAdData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }
}