<?php
namespace App\Controller;


class BlogController extends AbstractController
{
    /**
     * @Route("/api/add-blog", name="api_add_blog_post_action", methods={"POST"})
     * @return array
     */
    public function addBlogPostAction(): array
    {
            $user = $this->getUser()->getId();

            if($this->blogLogic->createBlog($user) instanceof Blog)
            {

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
     * @Route("/api/update-blog/{id}", name="api_update_blog_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateBlogGetAction($id): array
    {
        if($id && $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0]) instanceof Blog)
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0]);
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
     * @Route("/api/update-blog/{id}", name="api_update_blog_put_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function updateBlogPutAction($id): array
    {
        if
        (
            $id && $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0]) instanceof Blog && $this->blogRepository->find($id)->getUser()->getId() == $this->getUser()->getId()
        )
        {
            $blog =  $this->blogRepository->findOneBy(["id"=>$id,"isDeleted"=>0]);
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
     * @Route("/api/delete-blog/{id}", name="api_blog_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function blogDeleteAction($id): array
    {
        if ($id && $this->blogRepository->find($id) instanceof Blog && $this->blogRepository->find($id)->getUser()->getId() == $user) {
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
     * @Route("/api/list-blog", name="api_list_blog_get_action", methods={"GET"})
     * @return array
     */
    public function listBlogGetAction()
    {
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
     * @Route("/api/list-blog-last", name="api_list_blog_last_get_action", methods={"GET"})
     * @return array
     */
    public function listBlogLastGetAction()
    {
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
     * @Route("/api/list-my-blog", name="api_list_my_blog_get_action", methods={"GET"})
     * @return array
     */
    public function listMyBlogGetAction()
    {
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
     * @Route("/api/list-my-blog-evaluation", name="api_list_my_blog_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function listMyBlogEvaluationGetAction(): array
    {
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
        if($this->blogRepository->findOneBy(["slug"=>$slug,"status"=>1,"isDeleted"=>0]) instanceof Blog)
        {
            $blog = $this->blogRepository->findOneBy(["slug"=>$slug,"status"=>1,"isDeleted"=>0]);
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


}