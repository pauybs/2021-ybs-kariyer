<?php
namespace App\Controller;


class BlogController extends AbstractController
{


    /**
     * @Route("/api/update-blog-image-home/{id}", name="api_update_blog_image_home_post_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function updateBlogImageHomePostAction($id): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
//        $errorsValidator = $this->blogValidator->validate();
//        if($errorsValidator)
//        {
//            $payload->setStatus(false)->setMessages($errorsValidator);
//            return [
//                'payload'=>$payload,
//                'code'=>Response::HTTP_BAD_REQUEST
//            ];
//        }

        $user = $this->getUser()->getId();
        if($id && $this->blogRepository->find($id) instanceof Blog) {
            $blog = $this->blogRepository->find($id);
            if ($blog->getUser()->getId() != $this->getUser()->getId()) {
                $payload->setStatus(false);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_NOT_FOUND
                ];
            }

            if($this->blogLogic->updateImageHome($id) === true)
            {
                $payload->setStatus(true);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_OK
                ];
            }
        }


        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    // TODO admin Blog Image Home POST
    /**
     * @Route("/api/admin/update-blog-image-home/{id}", name="api_admin_update_blog_image_home_post_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function adminUpdateBlogImageHomePostAction($id): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();


        if($id && $this->blogRepository->find($id) instanceof Blog) {
            $blog = $this->blogRepository->find($id);

            if($this->blogLogic->updateImageHome($id) === true)
            {
                $payload->setStatus(true);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_OK
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
     * @Route("/api/add-blog", name="api_add_blog_post_action", methods={"POST"})
     * @return array
     */
    public function addBlogPostAction(): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
        $errorsValidator = $this->blogValidator->validate();
        if($errorsValidator)
        {
            $payload->setStatus(false)->setMessages($errorsValidator);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_BAD_REQUEST
            ];
        }

        $user = $this->getUser()->getId();

        if(($blogEntity = $this->blogLogic->createBlog($user)) && $blogEntity instanceof Blog)
        {
            $telegramMessage = "%name% %surname% ( %username% ) kullanıcısı %blogTitle% başlıklı blog yazısını ekledi, en kısa sürede lütfen değerlendiriniz. Değerlendirme Linki = %link%";
            $telegramMessage = str_replace(
                ["%name%","%surname%","%username%","%blogTitle%","%link%"],
                [$this->getUser()->getName(), $this->getUser()->getSurname(), $this->getUser()->getUsernameProperty(), $blogEntity->getBlogTitle(), "https://ybskariyer.com/admin/blog-degerlendir/".$blogEntity->getId()],
                $telegramMessage
            );
            $this->notificationLogic->telegramNotification($telegramMessage);

            $payload->setStatus(true)->setMessages(['Blog başarılı şekilde yapıldı.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }else {
            $payload->setStatus(false)->setMessages(['Blog paylaşım sırasında hata meydana geldi !']);
            return [
                'payload' => $payload,
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/add-podcast", name="api_add_podcast_post_action", methods={"POST"})
     * @return array
     */
    public function addPostCastPostAction(): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
        $errorsValidator = $this->postCastValidator->validate();
        if($errorsValidator)
        {
            $payload->setStatus(false)->setMessages($errorsValidator);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_BAD_REQUEST
            ];
        }

        $user = $this->getUser()->getId();

        if(($blogEntity = $this->blogLogic->createPostCast($user)) && $blogEntity instanceof Blog)
        {
            $telegramMessage = "%name% %surname% ( %username% ) kullanıcısı %blogTitle% başlıklı postcast yazısını ekledi, en kısa sürede lütfen değerlendiriniz. Değerlendirme Linki = %link%";
            $telegramMessage = str_replace(
                ["%name%","%surname%","%username%","%blogTitle%","%link%"],
                [$this->getUser()->getName(), $this->getUser()->getSurname(), $this->getUser()->getUsernameProperty(), $blogEntity->getBlogTitle(), "https://ybskariyer.com/admin/postcast-degerlendir/".$blogEntity->getId()],
                $telegramMessage
            );
            $this->notificationLogic->telegramNotification($telegramMessage);

            $payload->setStatus(true)->setMessages(['Postcast başarılı şekilde yapıldı.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }else {
            $payload->setStatus(false)->setMessages(['Postcast paylaşım sırasında hata meydana geldi !']);
            return [
                'payload' => $payload,
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/update-blog/{id}", name="api_update_blog_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateBlogGetAction($id): array
    {

        $payload=new Payload();

        if($id && $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0,"type" => 1]) instanceof Blog)
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0,"type" => 1]);
            if($blog->getStatus() == 2)
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            if($blog->getUser()->getId() != $this->getUser()->getId())
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            if($this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2]))
            {
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();
            } else {
                $imagesHome = null;
            }


            $data = [
                'id' => $blog->getId(),
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
                'updateAt' => $blog->getUpdateAt(),
                'isDeleted' => $blog->getIsDeleted()
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
     * @Route("/api/update-postcast/{id}", name="api_update_postcast_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updatePostCastGetAction($id): array
    {

        $payload=new Payload();

        if($id && $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0,"type"=>2]) instanceof Blog)
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0,"type"=>2]);
            if($blog->getStatus() == 2)
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            if($blog->getUser()->getId() != $this->getUser()->getId())
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            if($this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2]))
            {
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();
            } else {
                $imagesHome = null;
            }


            $data = [
                'id' => $blog->getId(),
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'youtube' => $blog->getYoutube(),
                'spotify' => $blog->getSpotify(),
                'soundcloud' => $blog->getSoundcloud(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
                'updateAt' => $blog->getUpdateAt(),
                'isDeleted' => $blog->getIsDeleted()
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

    // TODO admin Blog GET
    /**
     * @Route("/api/admin/update-blog/{id}", name="api_admin_update_blog_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function adminUpdateBlogGetAction($id): array
    {

        $payload=new Payload();

        if($id && $this->blogRepository->findOneBy(["id"=>$id]) instanceof Blog)
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id]);


            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            if($this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2]))
            {
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();
            } else {
                $imagesHome = null;
            }


            $data = [
                'id' => $blog->getId(),
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
                'updateAt' => $blog->getUpdateAt(),
                'isDeleted' => $blog->getIsDeleted()
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

    // TODO admin PostCast GET
    /**
     * @Route("/api/admin/update-postcast/{id}", name="api_admin_update_postcast_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function adminUpdatePostCastGetAction($id): array
    {

        $payload=new Payload();

        if($id && $this->blogRepository->findOneBy(["id"=>$id,"type"=>2]) instanceof Blog)
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"type"=>2]);


            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            if($this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2]))
            {
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();
            } else {
                $imagesHome = null;
            }


            $data = [
                'id' => $blog->getId(),
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'youtube' => $blog->getYoutube(),
                'spotify' => $blog->getSpotify(),
                'soundcloud' => $blog->getSoundcloud(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
                'updateAt' => $blog->getUpdateAt(),
                'isDeleted' => $blog->getIsDeleted()
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
     * @Route("/api/admin/evaluation-blog/{id}", name="api_admin_evaluation_blog_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function adminEvaluationBlogGetAction($id): array
    {

        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $status = $request->query->get('status',null);
        $text = $request->query->get('text',null);
        if($id && $this->blogRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0]) instanceof Blog)
        {
            if($this->blogLogic->evaluationBlog($id,$status) instanceof Blog)
            {
                $blogEntity = $this->blogRepository->findOneBy(["id"=>$id]);
                if($status == 2)
                {
                    $evaluationData = [
                        "entity" => "Blog",
                        "entityId" => $id,
                        "message" => $text
                    ];
                    $this->evaluationMessageLogic->saveEvaluationMessage($evaluationData);

                    $message = $blogEntity->getBlogTitle()." başlıklı blog yazınız reddedildi. Reddedilme sebebi = ".$text;

                    $notificationMessageData = [
                        "message" => $message,
                        "userId" => $blogEntity->getUser()->getId()
                    ];
                    $this->bus->dispatch(new NotificationMessage($notificationMessageData));

                    $emailData = [
                        'email' => $blogEntity->getUser()->getEmail(),
                        'title' => $blogEntity->getBlogTitle()." başlıklı blog yazınız reddedildi.",
                        'content' => $message,
                        'name' => $blogEntity->getUser()->getName(),
                        'surname' => $blogEntity->getUser()->getSurname(),
                        'type' => 'mail'
                    ];
                    $this->bus->dispatch(new MailMessage($emailData));
                }else {
                    $message = $blogEntity->getBlogTitle()." başlıklı blog yazınız onaylandı.";

                    $notificationMessageData = [
                        "message" => $message,
                        "userId" => $blogEntity->getUser()->getId()
                    ];
                    $this->bus->dispatch(new NotificationMessage($notificationMessageData));

                    $emailData = [
                        'email' => $blogEntity->getUser()->getEmail(),
                        'title' => $message,
                        'content' => $message,
                        'name' => $blogEntity->getUser()->getName(),
                        'surname' => $blogEntity->getUser()->getSurname(),
                        'type' => 'mail'
                    ];
                    $this->bus->dispatch(new MailMessage($emailData));
                }

                $payload->setStatus(true);
                return  [
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
     * @Route("/api/admin/evaluation-postcast/{id}", name="api_admin_evaluation_postcast_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function adminEvaluationPostCastGetAction($id): array
    {

        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $status = $request->query->get('status',null);
        $text = $request->query->get('text',null);
        if($id && $this->blogRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0, "type" => 2]) instanceof Blog)
        {
            if($this->blogLogic->evaluationPostCast($id,$status) instanceof Blog)
            {
                $blogEntity = $this->blogRepository->findOneBy(["id"=>$id]);
                if($status == 2)
                {
                    $evaluationData = [
                        "entity" => "Blog",
                        "entityId" => $id,
                        "message" => $text
                    ];
                    $this->evaluationMessageLogic->saveEvaluationMessage($evaluationData);

                    $message = $blogEntity->getBlogTitle()." başlıklı postcast yazınız reddedildi. Reddedilme sebebi = ".$text;

                    $notificationMessageData = [
                        "message" => $message,
                        "userId" => $blogEntity->getUser()->getId()
                    ];
                    $this->bus->dispatch(new NotificationMessage($notificationMessageData));

                    $emailData = [
                        'email' => $blogEntity->getUser()->getEmail(),
                        'title' => $blogEntity->getBlogTitle()." başlıklı postcast yazınız reddedildi.",
                        'content' => $message,
                        'name' => $blogEntity->getUser()->getName(),
                        'surname' => $blogEntity->getUser()->getSurname(),
                        'type' => 'mail'
                    ];
                    $this->bus->dispatch(new MailMessage($emailData));
                }else {
                    $message = $blogEntity->getBlogTitle()." başlıklı postcast yazınız onaylandı.";

                    $notificationMessageData = [
                        "message" => $message,
                        "userId" => $blogEntity->getUser()->getId()
                    ];
                    $this->bus->dispatch(new NotificationMessage($notificationMessageData));

                    $emailData = [
                        'email' => $blogEntity->getUser()->getEmail(),
                        'title' => $message,
                        'content' => $message,
                        'name' => $blogEntity->getUser()->getName(),
                        'surname' => $blogEntity->getUser()->getSurname(),
                        'type' => 'mail'
                    ];
                    $this->bus->dispatch(new MailMessage($emailData));
                }

                $payload->setStatus(true);
                return  [
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
     * @Route("/api/admin/update-blog-evaluation/{id}", name="api_admin_update_blog_evaluation_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function adminUpdateBlogEvaluationGetAction($id): array
    {

        $payload=new Payload();

        if($id && $this->blogRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0]) instanceof Blog)
        {
            $blog = $this->blogRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0]);

            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            if($this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2]))
            {
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();
            } else {
                $imagesHome = null;
            }


            $data = [
                'id' => $blog->getId(),
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
                'updateAt' => $blog->getUpdateAt(),
                'isDeleted' => $blog->getIsDeleted()
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
     * @Route("/api/admin/update-postcast-evaluation/{id}", name="api_admin_update_postcast_evaluation_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function adminUpdatePostCastEvaluationGetAction($id): array
    {

        $payload=new Payload();

        if($id && $this->blogRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0, "type" => 2]) instanceof Blog)
        {
            $blog = $this->blogRepository->findOneBy(["id"=>$id,"status"=>0,"isDeleted"=>0, "type" => 2]);

            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            if($this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2]))
            {
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();
            } else {
                $imagesHome = null;
            }


            $data = [
                'id' => $blog->getId(),
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'youtube' => $blog->getYoutube(),
                'spotify' => $blog->getSpotify(),
                'soundcloud' => $blog->getSoundcloud(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
                'updateAt' => $blog->getUpdateAt(),
                'isDeleted' => $blog->getIsDeleted()
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
     * @Route("/api/update-blog/{id}", name="api_update_blog_put_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function updateBlogPutAction($id): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
//        $errorsValidator = $this->universityPostValidator->validate();
//        if($errorsValidator)
//        {
//            $payload->setStatus(false)->setMessages($errorsValidator);
//            return [
//                'payload'=>$payload,
//                'code'=>Response::HTTP_BAD_REQUEST
//            ];
//        }
        $user = $this->getUser()->getId();
        if
        (
            $id && $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0,"type" => 1]) instanceof Blog && $this->blogRepository->find($id)->getUser()->getId() == $this->getUser()->getId()
        )
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0,"type" => 1]);
            if($blog->getStatus() == 2)
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            if($this->blogLogic->updateBlog($id) instanceof Blog)
            {
                $telegramMessage = "%name% %surname% ( %username% ) kullanıcısı %blogTitle% başlıklı blog yazısını güncelledi, en kısa sürede lütfen değerlendiriniz. Değerlendirme Linki = %link%";
                $telegramMessage = str_replace(
                    ["%name%","%surname%","%username%","%blogTitle%","%link%"],
                    [$this->getUser()->getName(), $this->getUser()->getSurname(), $this->getUser()->getUsernameProperty(), $blog->getBlogTitle(), "https://ybskariyer.com/admin/blog-degerlendir/".$blog->getId()],
                    $telegramMessage
                );
                $this->notificationLogic->telegramNotification($telegramMessage);

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

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/update-postcast/{id}", name="api_update_postcast_put_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function updatePostCastPutAction($id): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
//        $errorsValidator = $this->universityPostValidator->validate();
//        if($errorsValidator)
//        {
//            $payload->setStatus(false)->setMessages($errorsValidator);
//            return [
//                'payload'=>$payload,
//                'code'=>Response::HTTP_BAD_REQUEST
//            ];
//        }
        $user = $this->getUser()->getId();
        if
        (
            $id && $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0, "type" => 2]) instanceof Blog && $this->blogRepository->find($id)->getUser()->getId() == $this->getUser()->getId()
        )
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0, "type" => 2]);
            if($blog->getStatus() == 2)
            {
                $payload->setStatus(false);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            if($this->blogLogic->updatePostCast($id) instanceof Blog)
            {
                $telegramMessage = "%name% %surname% ( %username% ) kullanıcısı %blogTitle% başlıklı postcast yazısını güncelledi, en kısa sürede lütfen değerlendiriniz. Değerlendirme Linki = %link%";
                $telegramMessage = str_replace(
                    ["%name%","%surname%","%username%","%blogTitle%","%link%"],
                    [$this->getUser()->getName(), $this->getUser()->getSurname(), $this->getUser()->getUsernameProperty(), $blog->getBlogTitle(), "https://ybskariyer.com/admin/postcast-degerlendir/".$blog->getId()],
                    $telegramMessage
                );
                $this->notificationLogic->telegramNotification($telegramMessage);

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

        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    // TODO admin update BLOG PUT
    /**
     * @Route("/api/admin/update-blog/{id}", name="api_admin_update_blog_put_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function adminUpdateBlogPutAction($id): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
//        $errorsValidator = $this->universityPostValidator->validate();
//        if($errorsValidator)
//        {
//            $payload->setStatus(false)->setMessages($errorsValidator);
//            return [
//                'payload'=>$payload,
//                'code'=>Response::HTTP_BAD_REQUEST
//            ];
//        }

        $blog =  $this->blogRepository->findOneBy(["id"=>$id]);

        if($this->blogLogic->updateBlog($id) instanceof Blog)
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
    }

    // TODO admin update postcast PUT
    /**
     * @Route("/api/admin/update-postcast/{id}", name="api_admin_update_postcast_put_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function adminUpdatePostCastPutAction($id): array
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $payload=new Payload();
//        $errorsValidator = $this->universityPostValidator->validate();
//        if($errorsValidator)
//        {
//            $payload->setStatus(false)->setMessages($errorsValidator);
//            return [
//                'payload'=>$payload,
//                'code'=>Response::HTTP_BAD_REQUEST
//            ];
//        }

        $blog =  $this->blogRepository->findOneBy(["id"=>$id, "type" => 2]);

        if($this->blogLogic->updatePostCast($id) instanceof Blog)
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
    }


    /**
     * @Route("/api/delete-blog/{id}", name="api_blog_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function blogDeleteAction($id): array
    {
        $payload = new Payload();
        $user = $this->getUser()->getId();
        if ($id && $this->blogRepository->findOneBy(["id" => $id, "type" => 1]) instanceof Blog && $this->blogRepository->findOneBy(["id" => $id, "type" => 1])->getUser()->getId() == $user) {
            if ($this->blogLogic->removeBlog($id) instanceof Blog) {
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
        $payload->setStatus(false)->setMessages(['Böyle bir blog yok !']);
        return [
            'payload' => $payload,
            'code' => Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/delete-postcast/{id}", name="api_postcast_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function postCastDeleteAction($id): array
    {
        $payload = new Payload();
        $user = $this->getUser()->getId();
        if ($id && $this->blogRepository->findOneBy(["id" => $id, "type" => 2]) instanceof Blog && $this->blogRepository->findOneBy(["id" => $id, "type" => 2])->getUser()->getId() == $user) {
            if ($this->blogLogic->removePostCast($id) instanceof Blog) {
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
        $payload->setStatus(false)->setMessages(['Böyle bir blog yok !']);
        return [
            'payload' => $payload,
            'code' => Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-blog", name="api_list_blog_get_action", methods={"GET"})
     * @return array
     */
    public function listBlogGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->searchListBlog($search))
        {
            foreach ($this->blogRepository->searchListBlog($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2]) ?
                    $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl() : null;


                $data=[
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-postcast", name="api_list_postcast_get_action", methods={"GET"})
     * @return array
     */
    public function listPostCastGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->searchListPostCast($search))
        {
            foreach ($this->blogRepository->searchListPostCast($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2]) ?
                    $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl() : null;


                $data=[
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'youtube' => $item->getYoutube(),
                    'spotify' => $item->getSpotify(),
                    'soundcloud' => $item->getSoundcloud(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['PostCast bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    // TODO admin List Blog
    /**
     * @Route("/api/admin/list-blog", name="api_admin_list_blog_get_action", methods={"GET"})
     * @return array
     */
    public function adminListBlogGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->adminsearchListBlogAll($search))
        {
            foreach ($this->blogRepository->adminsearchListBlogAll($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2]) ?
                    $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl() : null;


                $data=[
                    "id" => $item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    // TODO admin List Blog
    /**
     * @Route("/api/admin/list-postcast", name="api_admin_list_postcast_get_action", methods={"GET"})
     * @return array
     */
    public function adminListPostCastGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->adminsearchListPostCastAll($search))
        {
            foreach ($this->blogRepository->adminsearchListPostCastAll($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2]) ?
                    $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl() : null;


                $data=[
                    "id" => $item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'youtube' => $item->getYoutube(),
                    'spotify' => $item->getSpotify(),
                    'soundcloud' => $item->getSoundcloud(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['PostCast bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/admin/list-blog-evaluation", name="api_admin_list_blog_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function adminListBlogEvaluationGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->adminSearchListBlog($search))
        {
            foreach ($this->blogRepository->adminSearchListBlog($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2]) ? $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl() : null;


                $data=[
                    'id'=>$item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/admin/list-postcast-evaluation", name="api_admin_list_postcast_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function adminListPostCastEvaluationGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->adminSearchListPostCast($search))
        {
            foreach ($this->blogRepository->adminSearchListPostCast($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2]) ? $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl() : null;


                $data=[
                    'id'=>$item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'youtube' => $item->getYoutube(),
                    'spotify' => $item->getSpotify(),
                    'soundcloud' => $item->getSoundcloud(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-blog-last", name="api_list_blog_last_get_action", methods={"GET"})
     * @return array
     */
    public function listBlogLastGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->searchListLastBlog($search))
        {
            foreach ($this->blogRepository->searchListLastBlog($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl();


                $data=[
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-postcast-last", name="api_list_postcast_last_get_action", methods={"GET"})
     * @return array
     */
    public function listPostCastLastGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->blogRepository->searchListLastPostCast($search))
        {
            foreach ($this->blogRepository->searchListLastPostCast($search) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl();


                $data=[
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'youtube' => $item->getYoutube(),
                    'spotify' => $item->getSpotify(),
                    'soundcloud' => $item->getSoundcloud(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-blog", name="api_list_my_blog_get_action", methods={"GET"})
     * @return array
     */
    public function listMyBlogGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        $username = $request->query->get('username', null);
        if(!$this->userRepository->findOneBy(["username"=>$username,"status"=>1]) instanceof User)
        {
            $payload->setStatus(false)->setMessages(['User Geçersiz !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $user = $this->userRepository->findOneBy(["username"=>$username]);
        $MyBlogPrivacy = $this->userMetaRepository->findOneBy(["user"=>$user->getId(),"metaKey"=>"_myBlog"])->getMetaValue();
        if($this->getUser() && $this->getUser()->getId() != $user->getId())
        {
            if(!$MyBlogPrivacy)
            {
                $payload->setStatus(false)->setMessages(['Blog bulunamadı ya da gizli !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }else {
            if(!$this->getUser() && !$MyBlogPrivacy)
            {
                $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }
        if($this->blogRepository->searchListMyBlog($search,$username))
        {
            foreach ($this->blogRepository->searchListMyBlog($search,$username) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl();

                $data=[
                    'id' =>$item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-postcast", name="api_list_my_postcast_get_action", methods={"GET"})
     * @return array
     */
    public function listMyPostCastGetAction()
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        $username = $request->query->get('username', null);
        if(!$this->userRepository->findOneBy(["username"=>$username,"status"=>1]) instanceof User)
        {
            $payload->setStatus(false)->setMessages(['User Geçersiz !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $user = $this->userRepository->findOneBy(["username"=>$username]);
        $MyBlogPrivacy = $this->userMetaRepository->findOneBy(["user"=>$user->getId(),"metaKey"=>"_myPostCast"])->getMetaValue();
        if($this->getUser() && $this->getUser()->getId() != $user->getId())
        {
            if(!$MyBlogPrivacy)
            {
                $payload->setStatus(false)->setMessages(['PostCast bulunamadı ya da gizli !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }else {
            if(!$this->getUser() && !$MyBlogPrivacy)
            {
                $payload->setStatus(false)->setMessages(['Postcast bulunamadı ya da gizli !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }
        if($this->blogRepository->searchListMyPostCast($search,$username))
        {
            foreach ($this->blogRepository->searchListMyPostCast($search,$username) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl();

                $data=[
                    'id' =>$item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'youtube' => $item->getYoutube(),
                    'spotify' => $item->getSpotify(),
                    'soundcloud' => $item->getSoundcloud(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-blog-evaluation", name="api_list_my_blog_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function listMyBlogEvaluationGetAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        $username = $request->query->get('username', null);
        if(!$this->userRepository->findOneBy(["username"=>$username,"status"=>1]) instanceof User)
        {
            $payload->setStatus(false)->setMessages(['User Geçersiz !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $user = $this->userRepository->findOneBy(["username"=>$username,"status"=>1]);
        if($user->getId() != $this->getUser()->getId())
        {
            $payload->setStatus(false)->setMessages(['Profil sahibi görebilir !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        if($this->blogRepository->searchListMyBlogEvaluation($search,$username))
        {
            foreach ($this->blogRepository->searchListMyBlogEvaluation($search,$username) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl();

                $data=[
                    'id' =>$item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                    'evaluationMessage' => $this->evaluationMessageLogic->getEvaluationMessage('Blog', $item->getId()) ? $this->evaluationMessageLogic->getEvaluationMessage('Blog', $item->getId())->getMessage() : null
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-postcast-evaluation", name="api_list_my_postcast_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function listMyPostCastEvaluationGetAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $pageSize = $request->query->get('pageSize', 8);
        $username = $request->query->get('username', null);
        if(!$this->userRepository->findOneBy(["username"=>$username,"status"=>1]) instanceof User)
        {
            $payload->setStatus(false)->setMessages(['User Geçersiz !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $user = $this->userRepository->findOneBy(["username"=>$username,"status"=>1]);
        if($user->getId() != $this->getUser()->getId())
        {
            $payload->setStatus(false)->setMessages(['Profil sahibi görebilir !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        if($this->blogRepository->searchListMyPostCastEvaluation($search,$username))
        {
            foreach ($this->blogRepository->searchListMyPostCastEvaluation($search,$username) as $item)
            {
                $images = $this->blogImageRepository->findBy(["blog"=>$item->getId(),"type"=>1]);
                if($images)
                {
                    $fullDataImg = [];
                    foreach ($images as $itemImg)
                    {
                        $dataImg = [
                            'id' => $itemImg->getId(),
                            'url' => $itemImg->getUrl(),
                            'type'=>$itemImg->getType()
                        ];
                        $fullDataImg[]  = $dataImg;
                    }
                }
                if(empty($fullDataImg))
                {
                    $fullDataImg = null;
                }
                $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$item->getId(),"type"=>2])->getUrl();

                $data=[
                    'id' =>$item->getId(),
                    'user'=> [
                        'name'=>$item->getUser()->getName(),
                        'surname'=>$item->getUser()->getSurname(),
                        'username'=>$item->getUser()->getUsernameProperty()
                    ],
                    'blogTitle'=>$item->getBlogTitle(),
                    'blogContent'=>strip_tags($item->getBlogContent()),
                    'slug' => $item->getSlug(),
                    'youtube' => $item->getYoutube(),
                    'spotify' => $item->getSpotify(),
                    'soundcloud' => $item->getSoundcloud(),
                    'status' => $item->getStatus(),
                    'images' => $fullDataImg,
                    'imageHome'=>$imagesHome,
                    'views' => $item->getViews(),
                    'createdAt' => $item->getCreatedAt(),
                    'evaluationMessage' => $this->evaluationMessageLogic->getEvaluationMessage('Blog', $item->getId()) ? $this->evaluationMessageLogic->getEvaluationMessage('Blog', $item->getId())->getMessage() : null
                ];
                $fullData[]=$data;
            }
            if (isset($fullData)) {

                $pagination = $this->paginator->paginate(
                    $fullData, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/detail-blog/{slug}", name="api_detail_blog_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function detailBlogGetAction($slug): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        if($this->blogRepository->findOneBy(["slug"=>$slug,"status"=>1,"isDeleted"=>0,"type"=>1]) instanceof Blog)
        {
            $this->blogLogic->viewsBlog($slug);
            $blog = $this->blogRepository->findOneBy(["slug"=>$slug,"status"=>1,"isDeleted"=>0,"type"=>1]);
            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();

            $data=[
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
            ];
            $payload->setStatus(true)->setExtras($data);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }
        $payload->setStatus(false)->setMessages(['Blog bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/detail-postcast/{slug}", name="api_detail_postcast_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function detailPostCastGetAction($slug): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        if($this->blogRepository->findOneBy(["slug"=>$slug,"status"=>1,"isDeleted"=>0,"type"=>2]) instanceof Blog)
        {
            $this->blogLogic->viewsBlog($slug);
            $blog = $this->blogRepository->findOneBy(["slug"=>$slug,"status"=>1,"isDeleted"=>0,"type"=>2]);
            $images = $this->blogImageRepository->findBy(["blog"=>$blog->getId(),"type"=>1]);
            if($images)
            {
                $fullDataImg = [];
                foreach ($images as $itemImg)
                {
                    $dataImg = [
                        'id' => $itemImg->getId(),
                        'url' => $itemImg->getUrl(),
                        'type'=>$itemImg->getType()
                    ];
                    $fullDataImg[]  = $dataImg;
                }
            }
            if(empty($fullDataImg))
            {
                $fullDataImg = null;
            }
            $imagesHome = $this->blogImageRepository->findOneBy(["blog"=>$blog->getId(),"type"=>2])->getUrl();

            $data=[
                'user'=> [
                    'name'=>$blog->getUser()->getName(),
                    'surname'=>$blog->getUser()->getSurname(),
                    'username'=>$blog->getUser()->getUsernameProperty()
                ],
                'blogTitle'=>$blog->getBlogTitle(),
                'blogContent'=>$blog->getBlogContent(),
                'slug' => $blog->getSlug(),
                'youtube' => $blog->getYoutube(),
                'spotify' => $blog->getSpotify(),
                'soundcloud' => $blog->getSoundcloud(),
                'status' => $blog->getStatus(),
                'images' => $fullDataImg,
                'imageHome'=>$imagesHome,
                'views' => $blog->getViews(),
                'createdAt' => $blog->getCreatedAt(),
            ];
            $payload->setStatus(true)->setExtras($data);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }
        $payload->setStatus(false)->setMessages(['PostCast bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


}