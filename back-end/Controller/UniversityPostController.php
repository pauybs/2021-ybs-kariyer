<?php
namespace App\Controller;

class UniversityPostController extends AbstractController
{

    /**
     * @Route("/api/get-manager-university/{slug}", name="api_get_manager_university_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function getManagerUniversityAction($slug)
    {
        if(
            $slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University &&
            $this->universityManagerRepository->findOneBy(["university"=>$this->universityRepository->findOneBy(["slug"=>$slug])->getId(),"manager"=>$user]) instanceof UniversityManager
        )
        {
            $manager = $this->universityManagerRepository->findOneBy(["university"=>$this->universityRepository->findOneBy(["slug"=>$slug])->getId(),"manager"=>$user]) ;
            $university = $this->universityRepository->findOneBy(["slug"=>$slug]);

            $data = [
              "manager" => [
                  'id' => $manager->getId()
              ],
              "university" => [
                  'id' =>$university->getId(),
                  'universityName' => $university->getUniversityName()
              ]
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
     * @Route("/api/list-manager-university-user/{slug}", name="api_list_manager_university_user_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function listManagerUniversityAction($slug): array
    {
        if
        (
            $slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University
        )
        {
            $manager = $this->universityManagerRepository->findBy(["university"=>$this->universityRepository->findOneBy(["slug"=>$slug])->getId(),"status"=>1]) ;

            if($manager)
            {
                foreach ($manager as $item)
                {
                    $data = [
                          'name' => $item->getManager()->getName(),
                          'surname' => $item->getManager()->getSurname(),
                          'username' => $item->getManager()->getUsernameProperty()
                    ];

                    $fullData[] = $data;
                }

                $payload->setStatus(true)->setExtras($fullData);
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

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/add-university-post", name="api_add_universityPost_post_action", methods={"POST"})
     * @return array
     */
    public function addUniversityPostAction()
    {
        $user = $this->getUser()->getId();
        if(
            $this->universityRepository->findOneBy(["id"=>$data["university"]]) instanceof University &&
            $this->universityManagerRepository->findOneBy(["university"=>$data["university"],"id"=>$data["manager"]]) instanceof UniversityManager
        )
        {
            $managerUserId = $this->universityManagerRepository->findOneBy(["university"=>$data["university"],"id"=>$data["manager"]])->getManager()->getId();
            if($managerUserId != $user)
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            if($this->universityPostLogic->createUniversityPost($data["manager"]) instanceof UniversityPost)
            {
                $payload->setStatus(true)->setMessages(['Paylaşım başarılı şekilde yapıldı.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_CREATED
                ];
            }else{
                $payload->setStatus(false)->setMessages(['Paylaşım sırasında hata meydana geldi !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
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
     * @Route("/api/update-university-post/{id}", name="api_update_universityPost_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateUniversityPostGetAction($id): array
    {
        if($id && $this->universityPostRepository->find($id) instanceof UniversityPost)
        {
            $post = $this->universityPostRepository->find($id);
            $images = $this->universityPostImageRepository->findBy(["post"=>$post]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            $data = [
                'id' => $post->getId(),
                'university' => [
                    'slug' => $post->getUniversity()->getSlug(),
                    'universityName' => $post->getUniversity()->getUniversityName(),
                    'universityLogo' => $post->getUniversity()->getUniversityLogo()
                ],
                'type' => $post->getType(),
                'status' => $post->getStatus(),
                'content' => $post->getContent(),
                'images' => $fullDataImg,
                'views' => $post->getViews(),
                'createdAt' => $post->getCreatedAt(),
                'updateAt' => $post->getUpdateAt(),
                'isDeleted' => $post->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($data);
            return  [
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
     * @Route("/api/update-university-post/{id}", name="api_update_universityPost_put_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function updateUniversityPostPutAction($id): array
    {
        if
        (
            $id && $this->universityPostRepository->find($id) instanceof UniversityPost
        )
        {
            $post = $this->universityPostRepository->find($id);
            $university = $post->getUniversity()->getId();
            if($this->universityManagerRepository->findOneBy(["manager"=>$user,"university"=>$university]) instanceof UniversityManager)
            {
                if($this->universityPostLogic->updateUniversityPost($id) instanceof UniversityPost)
                {
                    $payload->setStatus(true)->setMessages(['Güncelleme başarılı !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                } else {
                    $payload->setStatus(false)->setMessages(["Güncellemede hata oluştu !"]);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            } else {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
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
     * @Route("/api/delete-university-post/{id}", name="api_universityPost_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function universityPostDeleteAction($id): array
    {
        $universityPost = $this->universityPostRepository->find($id);
        if ($universityPost instanceof UniversityPost && $this->universityManagerRepository->findOneBy(["manager" => $user, "university" => $universityPost->getUniversity()->getId()]) instanceof UniversityManager) {
            if ($this->universityPostLogic->removeUniversityPost($id) instanceof UniversityPost) {
                $payload->setStatus(true)->setMessages(['Başarılı şekilde silindi !']);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_OK
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Silme esnasında hata meydana geldi !']);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_NOT_FOUND
                ];
            }
        }
        $payload->setStatus(false)->setMessages(['Böyle bir post yok !']);
        return [
            'payload' => $payload,
            'code' => Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-university-post-manager/{slug}", name="api_list_university_post_manager_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function listUniversityPostManagerGetAction($slug) : array
    {
        if($this->universityPostRepository->listUniversityPostManager($slug))
        {
            foreach ($this->universityPostRepository->listUniversityPostManager($slug) as $item)
            {
                $images = $this->universityPostImageRepository->findBy(["post"=>$item->getId()]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'url' => $itemImg->getUrl()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $data = [
                    'id' => $item->getId(),
                    'university' => [
                        'universityName' => $item->getUniversity()->getUniversityName(),
                        'universityLogo' => $item->getUniversity()->getUniversityLogo()
                    ],
                    'type' => $item->getType(),
                    'status' => $item->getStatus(),
                    'content' => substr($item->getContent(), 0, 200),
                    'images' => $fullDataImg,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt(),
                    'isDeleted' => $item->getIsDeleted()
                ];
                $fullData[]  = $data;
            }
            $payload->setStatus(true)->setExtras($fullData);
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
}