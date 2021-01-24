<?php
namespace App\Controller;


class QuestionController extends AbstractController
{
    /**
     * @Route("/api/add-question", name="api_add_question_post_action", methods={"POST"})
     * @return array
     */
    public function addQuestionPostAction() : array
    {
        $questionTitle = $request->request->get('questionTitle');

        if($this->questionLogic->getQuestionFromTitle($questionTitle) instanceof Question)
        {
            $payload->setStatus(false)->setMessages(['Bu soru daha önce sorulmuş !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $userId = $this->getUser()->getId();
        if($this->questionLogic->createQuestion($userId) instanceof Question)
        {
            $logData = [
                'user' => $this->getUser()->getId(),
                'content' => ''.$questionTitle.' isimli soruyu sordunuz.',
                'ip' => $_SERVER["REMOTE_ADDR"],
                'type' => 1
            ];
            $this->logService->saveLog($logData);
            $payload->setStatus(true)->setMessages(['Soru başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Soru kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/update-question/{slug}", name="api_update_question_put_action", methods={"PUT"})
     * @param $slug
     * @return array
     */
    public function updateQuestionPutAction($slug) : array
    {
        if($slug && $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]) instanceof Question)
        {

            $questionFetch = $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($questionFetch->getStatus() == 2)
            {
                $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            $questionTitle = $this->requestStack->getCurrentRequest()->request->get('questionTitle');
            $question = $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($question->getQuestionTitle() != $questionTitle && $this->questionLogic->getQuestionFromTitle($questionTitle) instanceof Question)
            {
                $payload->setStatus(false)->setMessages(['Bu soru zaten kayıtlı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            if($question instanceof Question)
            {
                $this->questionLogic->updateQuestion($slug,$userId);
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$questionTitle.' isimli soruyu güncellediniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);


                $payload->setStatus(true)->setMessages(['Soru başarılı şekilde güncellendi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(['Soru güncellenirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];

            }

        }

        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/update-question/{slug}", name="api_update_question_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function updateQuestionGetAction($slug) : array
    {
        if($slug && $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]) instanceof Question)
        {
            $questionFetch = $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            if($questionFetch->getStatus() == 2)
            {
                $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            $question = $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId,"isDeleted"=>0]);
            $questionData = [
                'id' => $question->getId(),
                'questionTitle' => $question->getQuestionTitle(),
                'questionContent' => $question->getQuestionContent(),
                'status' => $question->getStatus(),
                'user' => [
                    'name' => $question->getUser()->getName(),
                    'surname' => $question->getUser()->getSurname(),
                    'username' => $question->getUser()->getUsernameProperty(),
                ],
                'slug' => $question->getSlug(),
                'createdAt' => $question->getCreatedAt(),
                'updateAt' => $question->getUpdateAt(),
                'isDeleted' => $question->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($questionData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/list-question", name="api_list_question_get_action", methods={"GET"})
     * @return array
     */
    public function listQuestionGetAction()
    {
        if($this->questionRepository->searchListQuestion($search))
        {
            $questionAll = $this->questionRepository->searchListQuestion($search);
            foreach ($questionAll as $item)
            {
                $questionData = [
                    'id' => $item->getId(),
                    'questionTitle' => $item->getQuestionTitle(),
                    'questionContent' => $item->getQuestionContent(),
                    'status' => $item->getStatus(),
                    'user' => [
                        'name' => $item->getUser()->getName(),
                        'surname' => $item->getUser()->getSurname(),
                        'username' => $item->getUser()->getUsernameProperty(),
                    ],
                    'slug' => $item->getSlug(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt(),
                    'isDeleted' => $item->getIsDeleted()
                ];
                $fullQuestion[] = $questionData;
            }
            if (isset($fullQuestion)) {

                $pagination = $this->paginator->paginate(
                    $fullQuestion, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-question", name="api_list_my_question_get_action", methods={"GET"})
     * @return array
     */
    public function listMyQuestionGetAction()
    {
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User)
        {
            $user = $this->userRepository->findOneBy(["username"=>$username]);
            $MyQuestionPrivacy = $this->userMetaRepository->findOneBy(["user"=>$user->getId(),"metaKey"=>"_myQuestion"])->getMetaValue();
            if($this->getUser() && $this->getUser()->getId() != $user->getId())
            {
                if(!$MyQuestionPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }else {
                if(!$this->getUser() && !$MyQuestionPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }

                if($this->questionRepository->searchListMyQuestion($search,$username))
                {
                    $questionAll = $this->questionRepository->searchListMyQuestion($search,$username);
                    foreach ($questionAll as $item)
                    {
                        $questionData = [
                            'id' => $item->getId(),
                            'questionTitle' => $item->getQuestionTitle(),
                            'questionContent' => $item->getQuestionContent(),
                            'status' => $item->getStatus(),
                            'user' => [
                                'name' => $item->getUser()->getName(),
                                'surname' => $item->getUser()->getSurname(),
                                'username' => $item->getUser()->getUsernameProperty(),
                            ],
                            'slug' => $item->getSlug(),
                            'createdAt' => $item->getCreatedAt(),
                            'updateAt' => $item->getUpdateAt(),
                            'isDeleted' => $item->getIsDeleted()
                        ];
                        $fullQuestion[] = $questionData;
                    }
                    if (isset($fullQuestion)) {

                        $pagination = $this->paginator->paginate(
                            $fullQuestion, /* query NOT result */
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

        }

        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-my-question-evaluation", name="api_list_my_question_evaluation_get_action", methods={"GET"})
     * @return array
     */
    public function listMyQuestionEvaluationGetAction(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = new Payload();
        $search = $request->query->get('search', null);
        $username = $request->query->get('username', null);
        $pageSize = $request->query->get('pageSize', 8);
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User)
        {
            $user = $this->userRepository->findOneBy(["username"=>$username]);
            if($user->getId() != $this->getUser()->getId())
            {
                $payload->setStatus(false)->setMessages(['Profil sahibi görebilir !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
            $MyQuestionPrivacy = $this->userMetaRepository->findOneBy(["user"=>$user->getId(),"metaKey"=>"_myQuestion"])->getMetaValue();
            if($this->getUser() && $this->getUser()->getId() != $user->getId())
            {
                if(!$MyQuestionPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }

            if($this->questionRepository->searchListMyQuestionEvaluation($search,$username))
            {
                $questionAll = $this->questionRepository->searchListMyQuestionEvaluation($search,$username);
                foreach ($questionAll as $item)
                {
                    $questionData = [
                        'id' => $item->getId(),
                        'questionTitle' => $item->getQuestionTitle(),
                        'questionContent' => $item->getQuestionContent(),
                        'status' => $item->getStatus(),
                        'user' => [
                            'name' => $item->getUser()->getName(),
                            'surname' => $item->getUser()->getSurname(),
                            'username' => $item->getUser()->getUsernameProperty(),
                        ],
                        'slug' => $item->getSlug(),
                        'createdAt' => $item->getCreatedAt(),
                        'updateAt' => $item->getUpdateAt(),
                        'isDeleted' => $item->getIsDeleted()
                    ];
                    $fullQuestion[] = $questionData;
                }
                if (isset($fullQuestion)) {

                    $pagination = $this->paginator->paginate(
                        $fullQuestion, /* query NOT result */
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

        }

        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/delete-question/{slug}", name="api_question_delete_action", methods={"DELETE"})
     * @param $slug
     * @return array
     */
    public function questionDeleteAction($slug)
    {
        $payload = new Payload();
        $userId = $this->getUser()->getId();
        if($slug && $this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId]) instanceof Question)
        {

            if($this->questionLogic->removeQuestion($slug, $userId) instanceof Question)
            {
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId])->getQuestionTitle().' isimli soruyu  sildiniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);

                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$this->questionRepository->findOneBy(["slug"=>$slug,"user"=>$userId])->getQuestionTitle().' isimli soru silindi.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 5
                ];
                $this->logService->saveLog($logData);

                $payload->setStatus(true)->setMessages(['Soru başarılı şekilde silindi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_ACCEPTED
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Soru silinirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }

        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);

        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }




    /**
     * @Route("/api/get-question/{slug}", name="api_get_question_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function getQuestionGetAction($slug) : array
    {
        if($slug && $this->questionRepository->findOneBy(["slug"=>$slug,"isDeleted"=>0,"status"=>1]) instanceof Question)
        {
            $question = $this->questionRepository->findOneBy(["slug"=>$slug,"isDeleted"=>0,"status"=>1]);
            $questionData = [
                'id' => $question->getId(),
                'questionTitle' => $question->getQuestionTitle(),
                'questionContent' => $question->getQuestionContent(),
                'status' => $question->getStatus(),
                'user' => [
                    'name' => $question->getUser()->getName(),
                    'surname' => $question->getUser()->getSurname(),
                    'username' => $question->getUser()->getUsernameProperty(),
                ],
                'slug' => $question->getSlug(),
                'createdAt' => $question->getCreatedAt(),
                'updateAt' => $question->getUpdateAt(),
                'isDeleted' => $question->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($questionData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Soru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

}