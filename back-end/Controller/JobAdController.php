<?php
namespace App\Controller;

class JobAdController extends AbstractController
{

    /**
     * @Route("/api/add-job-ad", name="api_add_job_ad_post_action", methods={"POST"})
     * @return array
     */
    public function addJobAdPostAction() : array
    {
        $userId = $this->getUser()->getId();
        if($this->jobAdLogic->createJobAd($userId) instanceof JobAd)
        {
            $logData = [
                'user' => $this->getUser()->getId(),
                'content' => 'Yeni bir iş ilanı sordunuz.',
                'ip' => $_SERVER["REMOTE_ADDR"],
                'type' => 1
            ];
            $this->logService->saveLog($logData);
            $payload->setStatus(true)->setMessages(['İlan başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['İlan kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/update-job-ad/{slug}", name="api_update_job_ad_put_action", methods={"PUT"})
     * @param $slug
     * @return array
     */
    public function updateJobAdPutAction($slug) : array
    {
        $userId = $this->getUser()->getId();
        if($slug && $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]) instanceof JobAd)
        {
            $jobAdFetch = $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($jobAdFetch->getStatus() == 2)
            {
                $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            if($this->jobAdLogic->updateJobAd($slug,$userId) instanceof JobAd)
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
     * @Route("/api/update-job-ad/{slug}", name="api_update_job_ad_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function updateJobAdGetAction($slug) : array
    {
        if($slug && $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]) instanceof JobAd)
        {
            $jobAdFetch = $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($jobAdFetch->getStatus() == 2)
            {
                $payload->setStatus(false)->setMessages(['İlan bulunamadı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            $jobAd = $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            $jobAdData = [
                'id' => $jobAd->getId(),
                'jobTitle' => $jobAd->getJobTitle(),
                'jobContent' => $jobAd->getJobContent(),
                'jobCompany' => $jobAd->getJobCompany(),
                'status' => $jobAd->getStatus(),
                'user'=>[
                    'name'=>$jobAd->getUser()->getName(),
                    'surname'=>$jobAd->getUser()->getSurname(),
                    'username'=>$jobAd->getUser()->getUsernameProperty()
                ],
                'jobCity'=>[
                    'id'=>$jobAd->getJobCity()->getId(),
                    'cityName'=>$jobAd->getJobCity()->getCityName()
                ],
                'jobPosition'=>[
                    'id'=>$jobAd->getJobPosition()->getId(),
                    'positionName'=>$jobAd->getJobPosition()->getPositionName()
                ],
                'workplaceSector'=> $jobAd->getWorkplaceSector() ? [
                  'id' => $jobAd->getWorkplaceSector()->getId(),
                  'sectorName'=> $jobAd->getWorkplaceSector()->getSectorName()
                ] : null,
                'jobType'=>$jobAd->getJobType(),
                'jobViews'=>$jobAd->getJobViews(),
                'slug' => $jobAd->getSlug(),
                'createdAt' => $jobAd->getCreatedAt(),
                'updateAt' => $jobAd->getUpdateAt(),
                'isDeleted' => $jobAd->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($jobAdData);
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
     * @Route("/api/list-job-ad", name="api_list_job_ad_get_action", methods={"GET"})
     * @return array
     */
    public function listJobAdGetAction()
    {
        if($this->jobAdRepository->searchListJobAd($search))
        {
            $jobAdAll = $this->jobAdRepository->searchListJobAd($search);
            foreach ($jobAdAll as $item)
            {
                $jobAdData = [
                    'id' => $item->getId(),
                    'jobTitle' => $item->getJobTitle(),
                    'jobContent' => $item->getJobContent(),
                    'jobCompany' => $item->getJobCompany(),
                    'status' => $item->getStatus(),
                    'user'=>[
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'jobCity'=>[
                        'id'=>$item->getJobCity()->getId(),
                        'cityName'=>$item->getJobCity()->getCityName()
                    ],
                    'jobPosition'=>[
                        'id'=>$item->getJobPosition()->getId(),
                        'positionName'=>$item->getJobPosition()->getPositionName()
                    ],
                    'workplaceSector'=> $item->getWorkplaceSector() ?  [
                        'id' => $item->getWorkplaceSector()->getId(),
                        'sectorName'=> $item->getWorkplaceSector()->getSectorName()
                    ] : null,
                    'jobType'=>$item->getJobType(),
                    'jobViews'=>$item->getJobViews(),
                    'slug' => $item->getSlug(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt(),
                    'isDeleted' => $item->getIsDeleted()
                ];
                $fullJobAd[] = $jobAdData;
            }
            if (isset($fullJobAd)) {

                $pagination = $this->paginator->paginate(
                    $fullJobAd, /* query NOT result */
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
     * @Route("/api/list-my-job-ad", name="api_list_my_job_ad_get_action", methods={"GET"})
     * @return array
     */
    public function listMyJobAdGetAction()
    {
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User) {
            $user = $this->userRepository->findOneBy(["username"=>$username]);
            $MyJobAdPrivacy = $this->userMetaRepository->findOneBy(["user"=>$user->getId(),"metaKey"=>"_myJobAd"])->getMetaValue();
            if($this->getUser() && $this->getUser()->getId() != $user->getId())
            {
                if(!$MyJobAdPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['İlan bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }else {
                if(!$this->getUser() && !$MyJobAdPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }

            if ($this->jobAdRepository->searchListMyJobAd($search, $username)) {
                $jobAdAll = $this->jobAdRepository->searchListMyJobAd($search, $username);
                foreach ($jobAdAll as $item) {
                    $jobAdData = [
                        'id' => $item->getId(),
                        'jobTitle' => $item->getJobTitle(),
                        'jobContent' => $item->getJobContent(),
                        'jobCompany' => $item->getJobCompany(),
                        'status' => $item->getStatus(),
                        'user' => [
                            'name' => $item->getUser()->getName(),
                            'surname' => $item->getUser()->getSurname(),
                            'username' => $item->getUser()->getUsernameProperty()
                        ],
                        'jobCity' => [
                            'id' => $item->getJobCity()->getId(),
                            'cityName' => $item->getJobCity()->getCityName()
                        ],
                        'jobPosition' => [
                            'id' => $item->getJobPosition()->getId(),
                            'positionName' => $item->getJobPosition()->getPositionName()
                        ],
                        'workplaceSector' => $item->getWorkplaceSector() ? [
                            'id' => $item->getWorkplaceSector()->getId(),
                            'sectorName' => $item->getWorkplaceSector()->getSectorName()
                        ] : null,
                        'jobType' => $item->getJobType(),
                        'jobViews' => $item->getJobViews(),
                        'slug' => $item->getSlug(),
                        'createdAt' => $item->getCreatedAt(),
                        'updateAt' => $item->getUpdateAt(),
                        'isDeleted' => $item->getIsDeleted()
                    ];
                    $fullJobAd[] = $jobAdData;
                }
                if (isset($fullJobAd)) {

                    $pagination = $this->paginator->paginate(
                        $fullJobAd, /* query NOT result */
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
     * @Route("/api/list-my-job-ad-evaluation", name="api_list_my_job_ad_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function listMyJobAdEvaluationGetAction(): array
    {
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User) {
            $user = $this->userRepository->findOneBy(["username"=>$username]);
            if($user->getId() != $this->getUser()->getId())
            {
                $payload->setStatus(false)->setMessages(['Profil sahibi görebilir !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }


            if ($this->jobAdRepository->searchListMyJobAdEvaluation($search, $username)) {
                $jobAdAll = $this->jobAdRepository->searchListMyJobAdEvaluation($search, $username);
                foreach ($jobAdAll as $item) {
                    $jobAdData = [
                        'id' => $item->getId(),
                        'jobTitle' => $item->getJobTitle(),
                        'jobContent' => $item->getJobContent(),
                        'jobCompany' => $item->getJobCompany(),
                        'status' => $item->getStatus(),
                        'user' => [
                            'name' => $item->getUser()->getName(),
                            'surname' => $item->getUser()->getSurname(),
                            'username' => $item->getUser()->getUsernameProperty()
                        ],
                        'jobCity' => [
                            'id' => $item->getJobCity()->getId(),
                            'cityName' => $item->getJobCity()->getCityName()
                        ],
                        'jobPosition' => [
                            'id' => $item->getJobPosition()->getId(),
                            'positionName' => $item->getJobPosition()->getPositionName()
                        ],
                        'workplaceSector' => $item->getWorkplaceSector() ? [
                            'id' => $item->getWorkplaceSector()->getId(),
                            'sectorName' => $item->getWorkplaceSector()->getSectorName()
                        ] : null,
                        'jobType' => $item->getJobType(),
                        'jobViews' => $item->getJobViews(),
                        'slug' => $item->getSlug(),
                        'createdAt' => $item->getCreatedAt(),
                        'updateAt' => $item->getUpdateAt(),
                        'isDeleted' => $item->getIsDeleted()
                    ];
                    $fullJobAd[] = $jobAdData;
                }
                if (isset($fullJobAd)) {

                    $pagination = $this->paginator->paginate(
                        $fullJobAd, /* query NOT result */
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
     * @Route("/api/delete-job-ad/{slug}", name="api_job_ad_delete_action", methods={"DELETE"})
     * @param $slug
     * @return array
     */
    public function jobAdDeleteAction($slug)
    {
        if($slug && $this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId]) instanceof JobAd)
        {

            if($this->jobAdLogic->removeJobAd($slug, $userId) instanceof JobAd)
            {
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId])->getJobTitle().' isimli ilanı  sildiniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);

                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$this->jobAdRepository->findOneBy(["slug"=>$slug,"user"=>$userId])->getJobTitle().' isimli ilan silindi.',
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
     * @Route("/api/get-job-ad/{slug}", name="api_get_job_ad_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function getJobAdGetAction($slug) : array
    {
        if($slug && $this->jobAdRepository->findOneBy(["slug"=>$slug,"isDeleted"=>0,"status"=>1]) instanceof JobAd)
        {
            $jobAd = $this->jobAdRepository->findOneBy(["slug"=>$slug,"isDeleted"=>0,"status"=>1]);
            $jobAdData = [
                'id' => $jobAd->getId(),
                'jobTitle' => $jobAd->getJobTitle(),
                'jobContent' => $jobAd->getJobContent(),
                'jobCompany' => $jobAd->getJobCompany(),
                'status' => $jobAd->getStatus(),
                'user'=>[
                    'name'=>$jobAd->getUser()->getName(),
                    'surname'=>$jobAd->getUser()->getSurname(),
                    'username'=>$jobAd->getUser()->getUsernameProperty()
                ],
                'jobCity'=>[
                    'id'=>$jobAd->getJobCity()->getId(),
                    'cityName'=>$jobAd->getJobCity()->getCityName()
                ],
                'jobPosition'=>[
                    'id'=>$jobAd->getJobPosition()->getId(),
                    'positionName'=>$jobAd->getJobPosition()->getPositionName()
                ],
                'workplaceSector'=> $jobAd->getWorkplaceSector() ? [
                    'id' => $jobAd->getWorkplaceSector()->getId(),
                    'sectorName'=> $jobAd->getWorkplaceSector()->getSectorName()
                ] : null,
                'jobType'=>$jobAd->getJobType(),
                'jobViews'=>$jobAd->getJobViews(),
                'slug' => $jobAd->getSlug(),
                'createdAt' => $jobAd->getCreatedAt(),
                'updateAt' => $jobAd->getUpdateAt(),
                'isDeleted' => $jobAd->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($jobAdData);
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