<?php
namespace App\Controller;

class AndacController extends AbstractController
{
    /**
     * @Route("/api/add-andac/{username}", name="api_add_andac_post_action", methods={"POST"})
     * @param $username
     * @return array
     */
    public function addAndacPostAction($username) : array
    {
        $request = $this->requestStack->getCurrentRequest();
        if(!$username || !$this->userRepository->findOneBy(["username"=>$username,"status"=>1]))
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $writerUser = $this->getUser()->getId();
        $content = $request->request->get('content');
        $universityId = $request->request->get('universityId');
        if(!$this->universityRepository->find($universityId) instanceof University)
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User)
        {
            $ownerUser = $this->userRepository->findOneBy(["username"=>$username])->getId();
            if($this->graduatedRepository->findOneBy(["university"=>$universityId,"user"=>$ownerUser,"status"=>1,"isApproved"=>1]) instanceof Graduated &&
                $this->graduatedRepository->findOneBy(["university"=>$universityId,"user"=>$writerUser,"status"=>1,"isApproved"=>1]) instanceof Graduated)
            {
                if($this->andacLogic->createAndac($ownerUser,$writerUser,$content,$universityId) instanceof Andac)
                {
                    $payload->setStatus(true);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                }
            }
        }

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/detail-andac/{id}", name="api_detail_andac_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function detailAndacGetAction($id) : array
    {
        if(!$id || !$this->andacRepository->findOneBy(["id"=>$id,"status"=>1,"isDeleted"=>0]) instanceof Andac)
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }else {
            $andac = $this->andacRepository->find($id);
                $data=[
                    'id' =>$andac->getId(),
                    'ownerUser'=>[
                        'username'=>$andac->getOwnerUser()->getUsernameProperty(),
                        'name'=>$andac->getOwnerUser()->getName(),
                        'surname'=>$andac->getOwnerUser()->getSurname()
                    ],
                    'writerUser'=>[
                        'username'=>$andac->getWriterUser()->getUsernameProperty(),
                        'name'=>$andac->getWriterUser()->getName(),
                        'surname'=>$andac->getWriterUser()->getSurname()
                    ],
                    'content'=>$andac->getContent(),
                    'university'=>[
                        'universityName'=>$andac->getUniversity()->getUniversityName(),
                        'slug'=>$andac->getUniversity()->getSlug()
                    ],
                    'status'=>$andac->getStatus(),
                    'createdAt'=>$andac->getCreatedAt(),
                    'updateAt'=>$andac->getUpdateAt(),
                    'isDeleted'=>$andac->getIsDeleted()
                ];
                $payload->setStatus(true)->setExtras($data);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];


        }

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
         * @Route("/api/update-andac/{id}", name="api_update_andac_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateAndacGetAction($id) : array
    {
        if(!$id || !$this->andacRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0]) instanceof Andac)
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }else {
            $andac = $this->andacRepository->find($id);
            $userId = $this->getUser()->getId();
            if($andac->getOwnerUser()->getId() == $userId || $andac->getWriterUser()->getId() == $userId)
            {
                $data=[
                    'id' =>$andac->getId(),
                    'ownerUser'=>[
                        'username'=>$andac->getOwnerUser()->getUsernameProperty(),
                        'name'=>$andac->getOwnerUser()->getName(),
                        'surname'=>$andac->getOwnerUser()->getSurname()
                    ],
                    'writerUser'=>[
                        'username'=>$andac->getWriterUser()->getUsernameProperty(),
                        'name'=>$andac->getWriterUser()->getName(),
                        'surname'=>$andac->getWriterUser()->getSurname()
                    ],
                    'content'=>$andac->getContent(),
                    'university'=>[
                        'universityName'=>$andac->getUniversity()->getUniversityName(),
                        'slug'=>$andac->getUniversity()->getSlug()
                    ],
                    'status'=>$andac->getStatus(),
                    'createdAt'=>$andac->getCreatedAt(),
                    'updateAt'=>$andac->getUpdateAt(),
                    'isDeleted'=>$andac->getIsDeleted()
                ];
                $payload->setStatus(true)->setExtras($data);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            }

        }

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/update-andac/{id}", name="api_update_andac_post_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function updateAndacPostAction($id) : array
    {
        $content = $request->request->get('content');
        if(!$id || !$this->andacRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0]) instanceof Andac || !isset($content) || empty($content))
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        } else {
            $andac = $this->andacRepository->find($id);
            $userId = $this->getUser()->getId();
            if($andac->getOwnerUser()->getId() == $userId || $andac->getWriterUser()->getId() == $userId)
            {
                if($this->andacLogic->updateAndac($id, $content) instanceof Andac)
                {
                    $payload->setStatus(true);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                }
            }
        }
        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/delete-andac/{id}", name="api_delete_andac_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function deleteAndacDeleteAction($id) : array
    {
        $content = $request->request->get('content');
        if(!$id || !$this->andacRepository->findOneBy(["id"=>$id,"status"=>0]) instanceof Andac)
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        } else {
            $andac = $this->andacRepository->findOneBy(["id"=>$id,"status"=>0]);
            $userId = $this->getUser()->getId();
            if($andac->getOwnerUser()->getId() == $userId || $andac->getWriterUser()->getId() == $userId)
            {
                if($this->andacLogic->removeAndac($id) instanceof Andac)
                {
                    $payload->setStatus(true);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                }
            }
        }
        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/approved-andac/{id}", name="api_approved_andac_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function approvedAndacGetAction($id) : array
    {
        $userId = $this->getUser()->getId();
        if(!$id || !$this->andacRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0,"ownerUser"=>$userId]) instanceof Andac)
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        } else {
                if($this->andacLogic->approvedAndac($id) instanceof Andac)
                {
                    $payload->setStatus(true);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                }
        }
        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-andac/{username}", name="api_list_andac_get_action", methods={"GET"})
     * @param $username
     * @return array
     */
    public function listAndacGetAction($username): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if(!$username || !$this->userRepository->findOneBy(["username"=>$username]) instanceof User)
        {
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $user = $this->userRepository->findOneBy(["username"=>$username]);
        $MyAndacPrivacy = $this->userMetaRepository->findOneBy(["user" => $user->getId(), "metaKey" => "_myAndac"])->getMetaValue();
        if ($this->getUser() && $this->getUser()->getId() != $user->getId()) {
            if (!$MyAndacPrivacy) {
                $payload->setStatus(false)->setMessages(['Staj İlanı bulunamadı ya da gizli !']);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_NOT_FOUND
                ];
            }
        }else {
            if(!$this->getUser() && !$MyAndacPrivacy)
            {
                $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }
            if ($this->andacRepository->findBy(["ownerUser" => $user->getId(), "status" => 1])) {
                $andacAll = $this->andacRepository->findBy(["ownerUser" => $user->getId(), "status" => 1]);
                foreach ($andacAll as $item) {
                    $andacData = [
                        'id' => $item->getId(),
                        'ownerUser' => [
                            'username' => $item->getOwnerUser()->getUsernameProperty(),
                            'name' => $item->getOwnerUser()->getName(),
                            'surname' => $item->getOwnerUser()->getSurname()
                        ],
                        'writerUser' => [
                            'username' => $item->getWriterUser()->getUsernameProperty(),
                            'name' => $item->getWriterUser()->getName(),
                            'surname' => $item->getWriterUser()->getSurname()
                        ],
                        'content' => $item->getContent(),
                        'university' => [
                            'universityName' => $item->getUniversity()->getUniversityName(),
                            'slug' => $item->getUniversity()->getSlug()
                        ],
                        'createdAt' => $item->getCreatedAt(),
                    ];
                    $fullAndac[] = $andacData;
                }
                if (isset($fullAndac)) {

                    $pagination = $this->paginator->paginate(
                        $fullAndac, /* query NOT result */
                        $request->query->getInt('page', 1), /*page number*/
                        100 /*limit per page*/
                    );

                    return [
                        'status' => true,
                        'page' => $pagination->getCurrentPageNumber(),
                        'pageCount' => ceil($pagination->getTotalItemCount() / 100),
                        'pageItemCount' => $pagination->count(),
                        'total' => $pagination->getTotalItemCount(),
                        'data' => $pagination->getItems(),
                        'code' => Response::HTTP_OK
                    ];

                }
            }
        $payload->setStatus(false)->setMessages(['Andaç bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-write-andac", name="api_list_write_andac_get_action", methods={"GET"})
     * @return array
     */
    public function listAndacWriteGetAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();

        if($this->andacRepository->findBy(["writerUser"=>$this->getUser()->getId(),"isDeleted"=>0]))
        {
                foreach ($this->andacRepository->findBy(["writerUser"=>$this->getUser()->getId(),"isDeleted"=>0]) as $item)
                {
                    $andacData = [
                        'id' =>$item->getId(),
                        'ownerUser'=>[
                            'username'=>$item->getOwnerUser()->getUsernameProperty(),
                            'name'=>$item->getOwnerUser()->getName(),
                            'surname'=>$item->getOwnerUser()->getSurname()
                        ],
                        'writerUser'=>[
                            'username'=>$item->getWriterUser()->getUsernameProperty(),
                            'name'=>$item->getWriterUser()->getName(),
                            'surname'=>$item->getWriterUser()->getSurname()
                        ],
                        'content'=>$item->getContent(),
                        'university'=>[
                            'universityName'=>$item->getUniversity()->getUniversityName(),
                            'slug'=>$item->getUniversity()->getSlug()
                        ],
                        'createdAt'=>$item->getCreatedAt(),
                    ];
                    $fullAndac[] = $andacData;
                }
                if (isset($fullAndac)) {

                    $pagination = $this->paginator->paginate(
                        $fullAndac, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Andaç bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-owner-andac", name="api_list_owner_andac_get_action", methods={"GET"})
     * @return array
     */
    public function listAndacOwnerGetAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();

        if($this->andacRepository->findBy(["ownerUser"=>$this->getUser()->getId(),"isDeleted"=>0,"status"=>0]))
        {
            foreach ($this->andacRepository->findBy(["ownerUser"=>$this->getUser()->getId(),"status"=>0,"isDeleted"=>0]) as $item)
            {
                $andacData = [
                    'id' =>$item->getId(),
                    'ownerUser'=>[
                        'username'=>$item->getOwnerUser()->getUsernameProperty(),
                        'name'=>$item->getOwnerUser()->getName(),
                        'surname'=>$item->getOwnerUser()->getSurname()
                    ],
                    'writerUser'=>[
                        'username'=>$item->getWriterUser()->getUsernameProperty(),
                        'name'=>$item->getWriterUser()->getName(),
                        'surname'=>$item->getWriterUser()->getSurname()
                    ],
                    'content'=>$item->getContent(),
                    'university'=>[
                        'universityName'=>$item->getUniversity()->getUniversityName(),
                        'slug'=>$item->getUniversity()->getSlug()
                    ],
                    'createdAt'=>$item->getCreatedAt(),
                ];
                $fullAndac[] = $andacData;
            }
            if (isset($fullAndac)) {

                $pagination = $this->paginator->paginate(
                    $fullAndac, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Andaç bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-andac-university", name="api_list_andac_university_get_action", methods={"GET"})
     * @return array
     */
    public function listAndacUniversityGetAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();

        if($this->graduatedRepository->findBy(["user"=>$this->getUser()->getId(),"status"=>1,"isApproved"=>1]))
        {
            foreach ($this->graduatedRepository->findBy(["user"=>$this->getUser()->getId(),"status"=>1,"isApproved"=>1]) as $item)
            {
                $universityData = [
                    'universityName'=>$item->getUniversity()->getUniversityName(),
                    'slug' => $item->getUniversity()->getSlug(),
                    'universityLogo'=>$item->getUniversity()->getUniversityLogo()
                ];
                $fullUniversity[] = $universityData;
            }
            if (isset($fullUniversity)) {

                $pagination = $this->paginator->paginate(
                    $fullUniversity, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Üniversity bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-andac-university/{slug}", name="api_list_andac_university_user_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function listAndacUniversityGetUserAction($slug): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        if(!$slug || !$this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University)
        {
            $payload->setStatus(false)->setMessages(['Üniversity bulunamadı !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $university = $this->universityRepository->findOneBy(["slug"=>$slug]);
        if($this->graduatedRepository->findOneBy(["user"=>$this->getUser()->getId(),"university"=>$university->getId(),"status"=>1,"isApproved"=>1]) instanceof Graduated)
        {
            if($this->graduatedRepository->findBy(["university"=>$university->getId(),"status"=>1,"isApproved"=>1]))
            {
                foreach ($this->graduatedRepository->findBy(["university"=>$university->getId(),"status"=>1,"isApproved"=>1]) as $item)
                {
                    $graduatedData = [
                       'username'=>$item->getUser()->getUsernameProperty(),
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname()
                    ];
                    $fullUniversity[] = $graduatedData;
                }
            }

            if (isset($fullUniversity)) {

                $pagination = $this->paginator->paginate(
                    $fullUniversity, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Üniversity bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

}