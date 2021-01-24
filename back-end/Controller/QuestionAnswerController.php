<?php
namespace App\Controller;


class QuestionAnswerController extends AbstractController
{

    /**
     * @Route("/api/add-question-answer", name="api_add_question_answer_post_action", methods={"POST"})
     * @return array
     */
    public function addQuestionAnswerPostAction() : array
    {
        $userId = $this->getUser()->getId();
        if($this->questionAnswerLogic->createQuestionAnswer($userId) instanceof QuestionAnswer)
        {
            $logData = [
                'user' => $this->getUser()->getId(),
                'content' => ''.$this->questionRepository->find($request->request->get('questionId'))->getQuestionTitle() .' isimli soruya cevap verdiniz.',
                'ip' => $_SERVER["REMOTE_ADDR"],
                'type' => 1
            ];
            $this->logService->saveLog($logData);
            $payload->setStatus(true)->setMessages(['Cevap başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Cevap kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/update-question-answer/{id}", name="api_update_question_answer_put_action", methods={"PUT"})
     * @param $id
     * @return array
     */
    public function updateQuestionPutAction($id) : array
    {
        $userId = $this->getUser()->getId();
        if($id && $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId,"isDeleted"=>0,"status"=>1]) instanceof QuestionAnswer)
        {
            $questionAnswer = $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId,"isDeleted"=>0,"status"=>1]);


            if($questionAnswer instanceof QuestionAnswer)
            {
                $this->questionAnswerLogic->updateQuestionAnswer($userId,$id);
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$questionAnswer->getQuestion()->getQuestionTitle().' isimli soruyu vermiş olduğunuz cevabı güncellediniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);


                $payload->setStatus(true)->setMessages(['Cevap başarılı şekilde güncellendi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(['Cevap güncellenirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];

            }

        }

        $payload->setStatus(false)->setMessages(['Cevap bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/update-question-answer/{id}", name="api_update_question_answer_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateQuestionAnswerGetAction($id) : array
    {
        if($id && $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId,"isDeleted"=>0,"status"=>1]) instanceof QuestionAnswer)
        {
            $questionAnswer = $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId,"isDeleted"=>0,"status"=>1]);
            $questionAnswerData = [
                'id'=>$questionAnswer->getId(),
                'answer'=>$questionAnswer->getAnswer(),
                'user' => [
                    'name' => $questionAnswer->getUser()->getName(),
                    'surname' => $questionAnswer->getUser()->getSurname(),
                    'username' => $questionAnswer->getUser()->getUsernameProperty(),
                ],
                'question'=> [
                'id' => $questionAnswer->getQuestion()->getId(),
                'questionTitle' => $questionAnswer->getQuestion()->getQuestionTitle(),
                'questionContent' => $questionAnswer->getQuestion()->getQuestionContent(),
                'status' => $questionAnswer->getQuestion()->getStatus(),
                'user' => [
                    'name' => $questionAnswer->getQuestion()->getUser()->getName(),
                    'surname' => $questionAnswer->getQuestion()->getUser()->getSurname(),
                    'username' => $questionAnswer->getQuestion()->getUser()->getUsernameProperty(),
                ],
                'slug' => $questionAnswer->getQuestion()->getSlug(),
                'createdAt' => $questionAnswer->getQuestion()->getCreatedAt(),
                'updateAt' => $questionAnswer->getQuestion()->getUpdateAt(),
                'isDeleted' => $questionAnswer->getQuestion()->getIsDeleted()
            ],
                'status' => $questionAnswer->getStatus(),
                'createdAt' => $questionAnswer->getCreatedAt(),
                'updateAt' => $questionAnswer->getUpdateAt(),
                'isDeleted' => $questionAnswer->getIsDeleted()
            ];
            $payload->setStatus(true)->setExtras($questionAnswerData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Cevap bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-question-answer/{slug}", name="api_list_question_answer_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function listQuestionAnswerGetAction($slug)
    {
        if($this->questionAnswerRepository->searchListQuestionAnswer($slug))
        {
            $questionAnswerAll = $this->questionAnswerRepository->searchListQuestionAnswer($slug);
            foreach ($questionAnswerAll as $item)
            {
                $questionAnswerData = [
                    'id'=>$item->getId(),
                    'answer'=>$item->getAnswer(),
                    'user' => [
                        'name' => $item->getUser()->getName(),
                        'surname' => $item->getUser()->getSurname(),
                        'username' => $item->getUser()->getUsernameProperty(),
                    ],
                    'status' => $item->getStatus(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt(),
                    'isDeleted' => $item->getIsDeleted()
                ];
                $fullQuestionAnswer[] = $questionAnswerData;
            }
            if (isset($fullQuestionAnswer)) {

                $pagination = $this->paginator->paginate(
                    $fullQuestionAnswer, /* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    50 /*limit per page*/
                );

                return [
                    'status' => true,
                    'page'=>$pagination->getCurrentPageNumber(),
                    'pageCount'=>ceil($pagination->getTotalItemCount() / 50),
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
     * @Route("/api/list-my-question-answer", name="api_list_my_question_answer_get_action", methods={"GET"})
     * @return array
     */
    public function listMyQuestionAnswerGetAction()
    {
        if($this->userRepository->findOneBy(["username"=>$username]) instanceof User) {
            $user = $this->userRepository->findOneBy(["username" => $username]);
            $MyQuestionAnswerPrivacy = $this->userMetaRepository->findOneBy(["user" => $user->getId(), "metaKey" => "_myQuestionAnswer"])->getMetaValue();
            if ($this->getUser() && $this->getUser()->getId() != $user->getId()) {
                if (!$MyQuestionAnswerPrivacy) {
                    $payload->setStatus(false)->setMessages(['Cevap bulunamadı ya da gizli !']);
                    return [
                        'payload' => $payload,
                        'code' => Response::HTTP_NOT_FOUND
                    ];
                }
            }else {
                if(!$this->getUser() && !$MyQuestionAnswerPrivacy)
                {
                    $payload->setStatus(false)->setMessages(['Soru bulunamadı ya da gizli !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }
            if ($this->questionAnswerRepository->searchListMyQuestionAnswer($search, $username)) {
                $questionAnswerAll = $this->questionAnswerRepository->searchListMyQuestionAnswer($search, $username);
                foreach ($questionAnswerAll as $item) {
                    $questionAnswerData = [
                        'id' => $item->getId(),
                        'answer' => $item->getAnswer(),
                        'user' => [
                            'name' => $item->getUser()->getName(),
                            'surname' => $item->getUser()->getSurname(),
                            'username' => $item->getUser()->getUsernameProperty(),
                        ],
                        'question' => [
                            'id' => $item->getQuestion()->getId(),
                            'questionTitle' => $item->getQuestion()->getQuestionTitle(),
                            'questionContent' => $item->getQuestion()->getQuestionContent(),
                            'status' => $item->getQuestion()->getStatus(),
                            'user' => [
                                'name' => $item->getQuestion()->getUser()->getName(),
                                'surname' => $item->getQuestion()->getUser()->getSurname(),
                                'username' => $item->getQuestion()->getUser()->getUsernameProperty(),
                            ],
                            'slug' => $item->getQuestion()->getSlug(),
                            'createdAt' => $item->getQuestion()->getCreatedAt(),
                            'updateAt' => $item->getQuestion()->getUpdateAt(),
                            'isDeleted' => $item->getQuestion()->getIsDeleted()
                        ],
                        'status' => $item->getStatus(),
                        'createdAt' => $item->getCreatedAt(),
                        'updateAt' => $item->getUpdateAt(),
                        'isDeleted' => $item->getIsDeleted()
                    ];
                    $fullQuestionAnswer[] = $questionAnswerData;
                }
                if (isset($fullQuestionAnswer)) {

                    $pagination = $this->paginator->paginate(
                        $fullQuestionAnswer, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Cevap bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/delete-question-answer/{id}", name="api_question_answer_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function questionAnswerDeleteAction($id)
    {
        if($id && $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId]) instanceof QuestionAnswer)
        {

            if($this->questionAnswerLogic->removeQuestionAnswer($id, $userId) instanceof QuestionAnswer)
            {
                $questionAnswer = $this->questionAnswerRepository->findOneBy(["id"=>$id,"user"=>$userId]);
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$questionAnswer->getQuestion()->getQuestionTitle().' isimli soruya verdiğiniz cevabı sildiniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);

                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$questionAnswer->getQuestion()->getQuestionTitle().' isimli soruya verdiği cevabı sildi.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 5
                ];
                $this->logService->saveLog($logData);

                $payload->setStatus(true)->setMessages(['Cevap başarılı şekilde silindi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_ACCEPTED
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Cevap silinirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }

        $payload->setStatus(false)->setMessages(['Cevap bulunamadı !']);

        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

}