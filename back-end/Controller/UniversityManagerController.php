<?php
namespace App\Controller;


class UniversityManagerController extends AbstractController
{
    /**
     * @Route("/api/admin/add-university-manager", name="api_admin_add_university_manager_post_action", methods={"POST"})
     * @return array
     */
    public function addUniversityManagerPostAction() : array
    {
        $university = $request->request->get('university');
        $manager = $request->request->get('manager');

        if(!$this->universityRepository->find($university) instanceof University)
        {
            $payload->setStatus(false)->setMessages(['Böyle bir üniversite yok !']);

            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_BAD_REQUEST
            ];
        }
        if(!$this->userRepository->find($manager) instanceof User)
        {
            $payload->setStatus(false)->setMessages(['Böyle bir kullanıcı yok !']);

            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_BAD_REQUEST
            ];
        }

        if($this->universityManagerLogic->getUniversityManagerFrom($university, $manager) instanceof UniversityManager)
        {
            $payload->setStatus(false)->setMessages(['Bu kullanıcı zaten bu üniversitenin temsilcisi !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }

        if($this->universityManagerLogic->createManager() instanceof UniversityManager)
        {
            $payload->setStatus(true)->setMessages(['Üniversite Temsilcisi başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/admin/update-university-manager/{id}", name="api_admin_update_university_manager_put_action", methods={"PUT"})
     * @param $id
     * @return array
     */
    public function updateUniversityManagerPutAction($id) : array
    {
        if($id && $this->universityManagerRepository->find($id) instanceof UniversityManager)
        {

                $university = $this->requestStack->getCurrentRequest()->request->get('university');

                if($this->universityManagerLogic->updateManager($id) instanceof UniversityManager)
                {
                    $payload->setStatus(true)->setMessages(['Üniversite Temsilcisi başarılı şekilde güncellendi.']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                }
        }

        $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/admin/update-university-manager/{id}", name="api_admin_update_university_manager_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateUniversityManagerGetAction($id) : array
    {
        if($id && $this->universityManagerRepository->find($id) instanceof UniversityManager)
        {
            $managerData = $this->universityManagerRepository->find($id);
            $managerGetData = [
                'id' => $managerData->getId(),
                'university' => [
                    'id'=>$managerData->getUniversity()->getId(),
                    'universityName'=>$managerData->getUniversity()->getUniversityName(),
                    'universityContent'=>$managerData->getUniversity()->getUniversityContent(),
                    'universityLogo'=>$managerData->getUniversity()->getUniversityLogo(),
                    'status'=>$managerData->getUniversity()->getStatus(),
                    'createdAt'=>$managerData->getUniversity()->getCreatedAt(),
                    'updateAt'=>$managerData->getUniversity()->getUpdateAt()
                ],
                'manager' => [
                    'id' => $managerData->getManager()->getId(),
                    'email'=>$managerData->getManager()->getEmail(),
                    'roles'=>$managerData->getManager()->getRoles(),
                    'username'=>$managerData->getManager()->getUsernameProperty(),
                    'name'=>$managerData->getManager()->getName(),
                    'surname'=>$managerData->getManager()->getSurname(),
                    'phone'=>$managerData->getManager()->getPhone(),
                    'point'=>$managerData->getManager()->getPoint(),
                    'isVerified'=>$managerData->getManager()->getIsVerified(),
                    'status'=>$managerData->getManager()->getStatus(),
                    'createdAt'=>$managerData->getManager()->getCreatedAt(),
                    'updateAt'=>$managerData->getManager()->getUpdateAt()
                ],
                'status' => $managerData->getStatus(),
                'createdAt' => $managerData->getCreatedAt(),
            ];
            $payload->setStatus(true)->setExtras($managerGetData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/admin/list-university-manager", name="api_admin_list_university_manager_get_action", methods={"GET"})
     * @return array
     */
    public function listUniversityManagerGetAction()
    {
        if($this->universityManagerRepository->searchListManager($search))
        {
            $managerAll = $this->universityManagerRepository->searchListManager($search);
            foreach ($managerAll as $item)
            {
                $managerData = [
                    'id' => $item->getId(),
                    'university' => [
                        'id'=>$item->getUniversity()->getId(),
                        'universityName'=>$item->getUniversity()->getUniversityName(),
                        'universityContent'=>$item->getUniversity()->getUniversityContent(),
                        'universityLogo'=>$item->getUniversity()->getUniversityLogo(),
                        'status'=>$item->getUniversity()->getStatus(),
                        'createdAt'=>$item->getUniversity()->getCreatedAt(),
                        'updateAt'=>$item->getUniversity()->getUpdateAt()
                    ],
                    'manager' => [
                        'email'=>$item->getManager()->getEmail(),
                        'roles'=>$item->getManager()->getRoles(),
                        'username'=>$item->getManager()->getUsernameProperty(),
                        'name'=>$item->getManager()->getName(),
                        'surname'=>$item->getManager()->getSurname(),
                        'phone'=>$item->getManager()->getPhone(),
                        'point'=>$item->getManager()->getPoint(),
                        'isVerified'=>$item->getManager()->getIsVerified(),
                        'status'=>$item->getManager()->getStatus(),
                        'createdAt'=>$item->getManager()->getCreatedAt(),
                        'updateAt'=>$item->getManager()->getUpdateAt()
                    ],
                    'status' => $item->getStatus(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt()
                ];
                $fullManager[] = $managerData;
            }
            if (isset($fullManager)) {

                $pagination = $this->paginator->paginate(
                    $fullManager, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/admin/on-status-university-manager/{id}", name="api_admin_university_manager_on_status_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function universityManagerOnStatusAction($id) : array
    {
        if($id && $this->universityManagerRepository->find($id) instanceof UniversityManager)
        {
            $onStatusUniversityManager = $this->universityManagerLogic->onStatusManager($id);
            if($onStatusUniversityManager instanceof UniversityManager)
            {
                $payload->setStatus(true)->setMessages(['Üniversite Temsilcisi başarılı şekilde durumu aktif yapıldı.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi güncellenirken hata oluştu !']);
            }

            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/admin/close-status-univeristy-manager/{id}", name="api_admin_university_manager_close_status_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function universityManagerCloseStatusAction($id) : array
    {
        if ($id && $this->universityManagerRepository->find($id) instanceof UniversityManager) {
            $closeStatusUniversityManager = $this->universityManagerLogic->closeStatusManager($id);
            if ($closeStatusUniversityManager instanceof UniversityManager) {

                $payload->setStatus(true)->setMessages(['Üniversite Temsilcisi başarılı şekilde durumu pasif yapıldı.']);
                return [
                    'payload' => $payload,
                    'code' => Response::HTTP_OK
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi güncellenirken hata oluştu !']);
            }

            return [
                'payload' => $payload,
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite Temsilcisi bulunamadı !']);
        return [
            'payload' => $payload,
            'code' => Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/user-get-university-manager", name="api_user_get_university_manager_action", methods={"GET"})
     * @return array
     */
    public function getUserFormUniversityManagerAction(): array
    {
        if($this->universityManagerLogic->getUserFormUniversityManager($user) instanceof UniversityManager)
        {
            $payload->setStatus(false)->setMessages(['Üniversite temsilcisi değilsiniz !']);
            return [
                'payload' => $payload,
                'code' => Response::HTTP_NOT_FOUND
            ];
        }
    }

    /**
     * @Route("/api/university-manager/student-application-list/{slug}", name="api_university_manager_student_application_list_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function studentApplicationListGetAction($slug): array
    {
        if($slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University)
        {
            $uni = $this->universityRepository->findOneBy(["slug"=>$slug]);
            if($this->universityManagerRepository->findOneBy(["manager"=>$userId,"university"=>$uni->getId()]) instanceof UniversityManager)
            {
                if($this->studentRepository->findBy(["university"=>$uni->getId(),"status"=>0,"isApproved"=>0]))
                {
                    $studentAll = $this->studentRepository->findBy(["university"=>$uni->getId(),"status"=>0,"isApproved"=>0]);
                    foreach ($studentAll as $item)
                    {
                        $studentData = [
                            'id' => $item->getId(),
                            'user' => [
                                'id'=>$item->getUser()->getId(),
                                'username'=>$item->getUser()->getUsernameProperty(),
                                'email'=>$item->getUser()->getEmail(),
                                'name'=>$item->getUser()->getName(),
                                'surname'=>$item->getUser()->getSurname(),
                                'createdAt'=>$item->getUser()->getCreatedAt(),
                                'updateAt'=>$item->getUser()->getUpdateAt()
                            ],
                            'university' => [
                                'universityName'=>$item->getUniversity()->getUniversityName()
                            ],
                            'status' => $item->getStatus(),
                            'isApproved' => $item->getIsApproved(),
                            'createdAt' => $item->getCreatedAt(),
                            'updateAt' => $item->getUpdateAt()
                        ];
                        $fullStudent[] = $studentData;
                    }
                    if (isset($fullStudent)) {

                        $pagination = $this->paginator->paginate(
                            $fullStudent, /* query NOT result */
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
            }
            $payload->setStatus(false);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $payload->setStatus(false)->setMessages(['Öğrenci başvurusu bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/graduated-application-list/{slug}", name="api_university_manager_graduated_application_list_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function graduatedApplicationListGetAction($slug): array
    {
        if($slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University) {
            $uni = $this->universityRepository->findOneBy(["slug" => $slug]);
            if ($this->universityManagerRepository->findOneBy(["manager" => $userId, "university" => $uni->getId()]) instanceof UniversityManager) {
                if ($this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 0, "isApproved" => 0])) {
                    if($this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 0, "isApproved" => 0]))
                    {
                        $graduatedAll = $this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 0, "isApproved" => 0]);
                        foreach ($graduatedAll as $item)
                        {
                            $graduatedData = [
                                'id' => $item->getId(),
                                'user' => [
                                    'id'=>$item->getUser()->getId(),
                                    'username'=>$item->getUser()->getUsernameProperty(),
                                    'email'=>$item->getUser()->getEmail(),
                                    'name'=>$item->getUser()->getName(),
                                    'surname'=>$item->getUser()->getSurname(),
                                    'createdAt'=>$item->getUser()->getCreatedAt(),
                                    'updateAt'=>$item->getUser()->getUpdateAt()
                                ],
                                'university' => [
                                    'slug' => $item->getUniversity()->getSlug(),
                                    'universityName'=>$item->getUniversity()->getUniversityName()
                                ],
                                'graduationYear' => $item->getGraduationYear(),
                                'isBusiness' => $item->getIsBusiness(),
                                'isPublic' => $item->getIsPublic(),
                                'workplaceName' => $item->getWorkplaceName(),
                                'workplaceSector' => $item->getWorkplaceSector() ? $item->getWorkplaceSector()->getSectorName() : null,
                                'workingCity' => $item->getWorkingCity() ? [
                                    'id' => $item->getWorkingCity()->getId(),
                                    'cityName' => $item->getWorkingCity()->getCityName(),
                                    'cityCode' => $item->getWorkingCity()->getCityCode()
                                ] : null,
                                'workingPosition' => $item->getWorkingPosition() ? [
                                    'id' => $item->getWorkingPosition()->getId(),
                                    'positionName' => $item->getWorkingPosition()->getPositionName()
                                ] : null,
                                'status' => $item->getStatus(),
                                'isApproved' => $item->getIsApproved(),
                                'createdAt' => $item->getCreatedAt(),
                                'updateAt' => $item->getUpdateAt()
                            ];
                            $fullGraduated[] = $graduatedData;
                        }
                        if (isset($fullGraduated)) {

                            $pagination = $this->paginator->paginate(
                                $fullGraduated, /* query NOT result */
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
                }
            }
        }

        $payload->setStatus(false)->setMessages(['Mezun başvurusu bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/student-application-evaluation/{id}", name="api_university_manager_student_application_evaluation_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function studentApplicationEvaluationGetAction($id): array
    {
        if($this->studentRepository->find($id))
        {
            $student = $this->studentRepository->find($id);

                $studentData = [
                    'id' => $student->getId(),
                    'user' => [
                        'id'=>$student->getUser()->getId(),
                        'username'=>$student->getUser()->getUsernameProperty(),
                        'email'=>$student->getUser()->getEmail(),
                        'name'=>$student->getUser()->getName(),
                        'surname'=>$student->getUser()->getSurname(),
                        'createdAt'=>$student->getUser()->getCreatedAt(),
                        'updateAt'=>$student->getUser()->getUpdateAt()
                    ],
                    'university' => [
                        'slug' => $student->getUniversity()->getSlug(),
                        'universityName'=>$student->getUniversity()->getUniversityName()
                    ],
                    'status' => $student->getStatus(),
                    'isApproved' => $student->getIsApproved(),
                    'createdAt' => $student->getCreatedAt(),
                    'updateAt' => $student->getUpdateAt()
                ];
            $payload->setStatus(true)->setExtras($studentData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }
        $payload->setStatus(false)->setMessages(['Başvuru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/student-application-evaluation/{id}", name="api_university_manager_student_application_evaluation_post_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function studentApplicationEvaluationPostAction($id): array
    {
        if($id && $this->studentRepository->find($id) instanceof Student)
        {

            if($this->studentLogic->approvedStudent($approved,$id) instanceof Student)
            {
                $payload->setStatus(true)->setMessages(["Başvuru başarıyla güncellendi"]);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(["Hata meydana geldi !"]);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

        }
        $payload->setStatus(false)->setMessages(['Başvuru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/university-manager/graduated-application-evaluation/{id}", name="api_university_manager_graduated_application_evaluation_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function graduatedApplicationEvaluationGetAction($id): array
    {
        if($this->graduatedRepository->find($id))
        {
            $graduated = $this->graduatedRepository->find($id);

            $graduatedData = [
                'id' => $graduated->getId(),
                'user' => [
                    'id'=>$graduated->getUser()->getId(),
                    'username'=>$graduated->getUser()->getUsernameProperty(),
                    'email'=>$graduated->getUser()->getEmail(),
                    'name'=>$graduated->getUser()->getName(),
                    'surname'=>$graduated->getUser()->getSurname(),
                    'createdAt'=>$graduated->getUser()->getCreatedAt(),
                    'updateAt'=>$graduated->getUser()->getUpdateAt()
                ],
                'university' => [
                    'slug' => $graduated->getUniversity()->getSlug(),
                    'universityName'=>$graduated->getUniversity()->getUniversityName()
                ],
                'graduationYear' => $graduated->getGraduationYear(),
                'isBusiness' => $graduated->getIsBusiness(),
                'isPublic' => $graduated->getIsPublic(),
                'workplaceName' => $graduated->getWorkplaceName(),
                'workplaceSector' => $graduated->getWorkplaceSector() ? $graduated->getWorkplaceSector()->getSectorName() : null,
                'workingCity' => $graduated->getWorkingCity() ? [
                    'id' => $graduated->getWorkingCity()->getId(),
                    'cityName' => $graduated->getWorkingCity()->getCityName(),
                    'cityCode' => $graduated->getWorkingCity()->getCityCode()
                ] : null,
                'workingPosition' => $graduated->getWorkingPosition() ? [
                    'id' => $graduated->getWorkingPosition()->getId(),
                    'positionName' => $graduated->getWorkingPosition()->getPositionName()
                ] : null,
                'status' => $graduated->getStatus(),
                'isApproved' => $graduated->getIsApproved(),
                'createdAt' => $graduated->getCreatedAt(),
                'updateAt' => $graduated->getUpdateAt()
            ];
            $payload->setStatus(true)->setExtras($graduatedData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }
        $payload->setStatus(false)->setMessages(['Başvuru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/graduated-application-evaluation/{id}", name="api_university_manager_graduated_application_evaluation_post_action", methods={"POST"})
     * @param $id
     * @return array
     */
    public function graduatedApplicationEvaluationPostAction($id): array
    {
        if($this->graduatedRepository->find($id) instanceof Graduated)
        {

            if($this->graduatedLogic->approvedGraduated($approved,$id) instanceof Graduated)
            {
                $payload->setStatus(true)->setMessages(["Başvuru başarıyla güncellendi"]);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(["Hata meydana geldi !"]);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

        }
        $payload->setStatus(false)->setMessages(['Başvuru bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/university-manager/student-block/{student}", name="api_university_manager_student_block_post_action", methods={"POST"})
     * @param $student
     * @return array
     */
    public function blockStudentPostAction($student): array
    {
        if($this->studentRepository->find($student) instanceof Student)
        {
            if($this->studentLogic->blockStudent($student) === true)
            {
                $payload->setStatus(true)->setMessages(["Öğrenci Status durumu güncellendi !"]);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            }
            $payload->setStatus(false)->setMessages(["Hata meydana geldi !"]);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $payload->setStatus(false)->setMessages(["Böyle bir mezun yok !"]);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/graduated-block/{graduated}", name="api_university_manager_graduated_block_post_action", methods={"POST"})
     * @param $graduated
     * @return array
     */
    public function blockGraduatedPostAction($graduated): array
    {
        if($this->graduatedRepository->find($graduated) instanceof Graduated)
        {
            if($this->graduatedLogic->blockGraduated($graduated) === true)
            {
                $payload->setStatus(true)->setMessages(["Mezun Status durumu güncellendi !"]);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            }
            $payload->setStatus(false)->setMessages(["Hata meydana geldi !"]);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
        $payload->setStatus(false)->setMessages(["Böyle bir mezun yok !"]);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/admin/university-manager-list-user", name="api_university_manager_list_user_action", methods={"GET"})
     * @return array
     */
    public function listallUser() : array
    {
        foreach ($this->userRepository->findAll() as $item)
        {
            $data = [
                'id'=>$item->getId(),
                'email'=>$item->getEmail(),
                'username'=>$item->getUsernameProperty()
            ];
            $fullData[]=$data;
        }
        $payload->setStatus(true)->setExtras($fullData);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_OK
        ];
    }


    /**
     * @Route("/api/list-manager-university", name="api_list_manager_university_get_action", methods={"GET"})
     * @return array
     */
    public function listManagerUniversityGetAction() : array
    {
        if($this->universityManagerRepository->findBy(["manager"=>$userId]))
        {
            foreach ($this->universityManagerRepository->findBy(["manager"=>$userId]) as $item)
            {
                $data = [
                    'id' => $item->getId(),
                    'university'=> [
                        'id' =>$item->getUniversity()->getId(),
                        'universityName' => $item->getUniversity()->getUniversityName(),
                        'slug' => $item->getUniversity()->getSlug(),
                        'universityLogo' => $item->getUniversity()->getUniversityLogo()
                    ],
                    'manager' => [
                        'id' => $item->getManager()->getId(),
                        'name' => $item->getManager()->getName(),
                        'surname' => $item->getManager()->getSurname(),
                        'username' => $item->getManager()->getUsernameProperty()
                    ]
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

    /**
     * @Route("/api/university-manager/graduated-list/{slug}", name="api_university_manager_graduated_list_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function graduatedListGetAction($slug): array
    {
        if($slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University) {
            $uni = $this->universityRepository->findOneBy(["slug" => $slug]);
            if ($this->universityManagerRepository->findOneBy(["manager" => $userId, "university" => $uni->getId()]) instanceof UniversityManager) {
                if ($this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 1, "isApproved" => 1])) {
                    if($this->graduatedRepository->findBy(["university" => $uni->getId(), "status" =>1, "isApproved" => 1]))
                    {
                        //$graduatedAll = $this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 1, "isApproved" => 1]);
                        $graduatedAll = $this->graduatedRepository->searchManagerGraduated($searchName,$uni->getId(),$searchCompanyName,$searchCalisma,$searchSector,$searchCity,$searchPosition);
                        foreach ($graduatedAll as $item)
                        {
                            $graduatedData = [
                                'id' => $item->getId(),
                                'user' => [
                                    'id'=>$item->getUser()->getId(),
                                    'username'=>$item->getUser()->getUsernameProperty(),
                                    'email'=>$item->getUser()->getEmail(),
                                    'name'=>$item->getUser()->getName(),
                                    'surname'=>$item->getUser()->getSurname(),
                                    'createdAt'=>$item->getUser()->getCreatedAt(),
                                    'updateAt'=>$item->getUser()->getUpdateAt()
                                ],
                                'university' => [
                                    'slug' => $item->getUniversity()->getSlug(),
                                    'universityName'=>$item->getUniversity()->getUniversityName()
                                ],
                                'graduationYear' => $item->getGraduationYear(),
                                'isBusiness' => $item->getIsBusiness(),
                                'isPublic' => $item->getIsPublic(),
                                'workplaceName' => $item->getWorkplaceName(),
                                'workplaceSector' => $item->getWorkplaceSector() ? $item->getWorkplaceSector()->getSectorName() : null,
                                'workingCity' => $item->getWorkingCity() ? [
                                    'id' => $item->getWorkingCity()->getId(),
                                    'cityName' => $item->getWorkingCity()->getCityName(),
                                    'cityCode' => $item->getWorkingCity()->getCityCode()
                                ] : null,
                                'workingPosition' => $item->getWorkingPosition() ? [
                                    'id' => $item->getWorkingPosition()->getId(),
                                    'positionName' => $item->getWorkingPosition()->getPositionName()
                                ] : null,
                                'status' => $item->getStatus(),
                                'isApproved' => $item->getIsApproved(),
                                'createdAt' => $item->getCreatedAt(),
                                'updateAt' => $item->getUpdateAt()
                            ];
                            $fullGraduated[] = $graduatedData;
                        }
                        if (isset($fullGraduated)) {

                            $pagination = $this->paginator->paginate(
                                $fullGraduated, /* query NOT result */
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
            }
        }

        $payload->setStatus(false)->setMessages(['Mezun Listesi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/graduated-list-public/{slug}", name="api_graduated_list_public_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function graduatedListPublicGetAction($slug): array
    {
        if($slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University) {
            $uni = $this->universityRepository->findOneBy(["slug" => $slug]);

                    if($this->graduatedRepository->findBy(["university" => $uni->getId(), "status" =>1, "isApproved" => 1,"isPublic"=>1]))
                    {
                        //$graduatedAll = $this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 1, "isApproved" => 1]);
                        $graduatedAll = $this->graduatedRepository->searchManagerPublicGraduated($searchName,$uni->getId(),$searchCompanyName,$searchCalisma,$searchSector,$searchCity,$searchPosition);

                        foreach ($graduatedAll as $item)
                        {
                            $graduatedData = [
                                'user' => [
                                    'username'=>$item->getUser()->getUsernameProperty(),
                                    'name'=>$item->getUser()->getName(),
                                    'surname'=>$item->getUser()->getSurname(),
                                ],
                                'university' => [
                                    'slug' => $item->getUniversity()->getSlug(),
                                    'universityName'=>$item->getUniversity()->getUniversityName()
                                ],
                                'graduationYear' => $item->getGraduationYear(),
                                'isBusiness' => $item->getIsBusiness(),
                                'isPublic' => $item->getIsPublic(),
                                'workplaceName' => $item->getWorkplaceName(),
                                'workplaceSector' => $item->getWorkplaceSector() ? $item->getWorkplaceSector()->getSectorName() : null,
                                'workingCity' => $item->getWorkingCity() ? [
                                    'id' => $item->getWorkingCity()->getId(),
                                    'cityName' => $item->getWorkingCity()->getCityName(),
                                    'cityCode' => $item->getWorkingCity()->getCityCode()
                                ] : null,
                                'workingPosition' => $item->getWorkingPosition() ? [
                                    'id' => $item->getWorkingPosition()->getId(),
                                    'positionName' => $item->getWorkingPosition()->getPositionName()
                                ] : null,
                            ];
                            $fullGraduated[] = $graduatedData;
                        }
                        if (isset($fullGraduated)) {

                            $pagination = $this->paginator->paginate(
                                $fullGraduated, /* query NOT result */
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

        $payload->setStatus(false)->setMessages(['Mezun Listesi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/student-list/{slug}", name="api_university_manager_student_list_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function studentListGetAction($slug): array
    {
        if($slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University) {
            $uni = $this->universityRepository->findOneBy(["slug" => $slug]);
            if ($this->universityManagerRepository->findOneBy(["manager" => $userId, "university" => $uni->getId()]) instanceof UniversityManager) {
                if ($this->studentRepository->findBy(["university" => $uni->getId(), "status" => 1, "isApproved" => 1])) {
                    if($this->studentRepository->findBy(["university" => $uni->getId(), "status" =>1, "isApproved" => 1]))
                    {
                        //$graduatedAll = $this->graduatedRepository->findBy(["university" => $uni->getId(), "status" => 1, "isApproved" => 1]);
                        $studentAll = $this->studentRepository->searchManagerStudent($searchName,$uni->getId(),$searchSurname);
                        foreach ($studentAll as $item)
                        {
                            $studentData = [
                                'id' => $item->getId(),
                                'user' => [
                                    'id'=>$item->getUser()->getId(),
                                    'username'=>$item->getUser()->getUsernameProperty(),
                                    'email'=>$item->getUser()->getEmail(),
                                    'name'=>$item->getUser()->getName(),
                                    'surname'=>$item->getUser()->getSurname(),
                                    'createdAt'=>$item->getUser()->getCreatedAt(),
                                    'updateAt'=>$item->getUser()->getUpdateAt()
                                ],
                                'university' => [
                                    'slug' => $item->getUniversity()->getSlug(),
                                    'universityName'=>$item->getUniversity()->getUniversityName()
                                ],
                                'status' => $item->getStatus(),
                                'isApproved' => $item->getIsApproved(),
                                'createdAt' => $item->getCreatedAt(),
                                'updateAt' => $item->getUpdateAt()
                            ];
                            $fullStudent[] = $studentData;
                        }
                        if (isset($fullStudent)) {

                            $pagination = $this->paginator->paginate(
                                $fullStudent, /* query NOT result */
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
            }
        }

        $payload->setStatus(false)->setMessages(['Student Listesi bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/university-manager/graduated-statistics/{slug}", name="api_university_manager_graduated_statistics_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function graduatedStatisticsGetAction($slug): array
    {
        if ($slug && $this->universityRepository->findOneBy(["slug" => $slug]) instanceof University) {
            $uni = $this->universityRepository->findOneBy(["slug" => $slug]);
            if ($this->universityManagerRepository->findOneBy(["manager" => $userId, "university" => $uni->getId(), "status" => 1]) instanceof UniversityManager) {

                $data = [
                  'totalGraduated' => count($this->graduatedRepository->findBy(["university"=>$uni->getId(),"status"=>1,"isApproved"=>1])),
                  'workingGraduated' => count($this->graduatedRepository->findBy(["university"=>$uni->getId(),"status"=>1,"isApproved"=>1,"isBusiness"=>1])),
                  'unemployedGraduated' => count($this->graduatedRepository->findBy(["university"=>$uni->getId(),"status"=>1,"isApproved"=>1,"isBusiness"=>0])),
                  'workplaceSectorCount'=> $this->graduatedRepository->workPlaceSectorToTal($uni->getId()),
                  'cityCount' => $this->graduatedRepository->cityToTal($uni->getId()),
                  'positionCount'=> $this->graduatedRepository->positionToTal($uni->getId()),
                  'graduatedYearCount' => $this->graduatedRepository->graduationYearToTal($uni->getId()),
                  'graduatedWorkPlaceName' => $this->graduatedRepository->graduatedWorkPlaceName($uni->getId()),
                  'graduatedApprovedRed' => count($this->graduatedRepository->findBy(["university"=>$uni->getId(),"status"=>1,"isApproved"=>2])),
                  'graduatedBlock' => count($this->graduatedRepository->findBy(["university"=>$uni->getId(),"status"=>0,"isApproved"=>1]))
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
        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/university-manager/student-statistics/{slug}", name="api_university_manager_student_statistics_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function studentStatisticsGetAction($slug): array
    {
        if ($slug && $this->universityRepository->findOneBy(["slug" => $slug]) instanceof University) {
            $uni = $this->universityRepository->findOneBy(["slug" => $slug]);
            if ($this->universityManagerRepository->findOneBy(["manager" => $userId, "university" => $uni->getId(), "status" => 1]) instanceof UniversityManager) {

                $data = [
                    'totalStudent' => count($this->studentRepository->findBy(["status"=>1,"isApproved"=>1])),
                    'studentApprovedRed' => count($this->studentRepository->findBy(["status"=>1,"isApproved"=>2])),
                    'studentBlock' => count($this->studentRepository->findBy(["status"=>0,"isApproved"=>1]))
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
        $payload->setStatus(false);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }
}