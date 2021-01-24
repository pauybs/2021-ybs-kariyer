<?php
namespace App\Controller;


class StudentController extends AbstractController
{

    /**
     * @Route("/api/get-user-student", name="api_get_user_student_get_action", methods={"GET"})
     * @return array
     */
    public function getUserStudentAction()
    {
        if($university && $this->universityRepository->findOneBy(["slug"=>$university]) instanceof University)
        {
            $university = $this->universityRepository->findOneBy(["slug"=>$university]);
        if($this->studentRepository->findOneBy(["user"=>$user,"university"=>$university->getId()]) instanceof Student)
        {
            $student = $this->studentRepository->findOneBy(["user"=>$user,"university"=>$university->getId()]);
            $data = [
              'id' => $student->getId(),
              'email' => $student->getUser()->getEmail(),
              'university' => [
                  'id' => $student->getUniversity()->getId(),
                  'universityName' => $student->getUniversity()->getUniversityName(),
                  'slug' => $student->getUniversity()->getSlug()
              ],
              'isApproved' => $student->getIsApproved(),
              'status' => $student->getStatus(),
              'createdAt' => $student->getCreatedAt(),
              'updateAt' => $student->getUpdateAt()
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
     * @Route("/api/add-student", name="api_add_student_post_action", methods={"POST"})
     * @return array
     */
    public function addStudentPostAction()
    {
        if(!$this->universityRepository->findOneBy(["slug"=>$university]) instanceof University)
        {
            $error[]='Böyle bir üniversite yok !';
        } elseif ($this->studentLogic->getStudentFrom($user,$this->universityRepository->findOneBy(["slug"=>$university])->getId()) instanceof Student) {
            $error[]='Zaten öğrenci sistemine kayıtlısınız !';
        }

        if(!empty($error))
        {
            $payload->setStatus(false)->setMessages($error);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_BAD_REQUEST
            ];
        }

        $studentCreate = $this->studentLogic->createStudent($user);
        if($studentCreate instanceof Student)
        {
            $logData = [
              'user' => $user,
              'content' => 'Üniversite öğrenci sistemine başvuru yaptınız.',
              'ip' => $_SERVER["REMOTE_ADDR"],
              'type' => 1
            ];
            $this->logService->saveLog($logData);

            $payload->setStatus(true)->setMessages(['Başarılı şekilde öğrenci sistemine başvuru yaptınız.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }else{
            $payload->setStatus(false)->setMessages(['Başvuru sırasında hata meydana geldi !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }

    }


    /**
     * @Route("/api/update-student", name="api_update_student_post_action", methods={"PUT"})
     * @return array
     */
    public function updateStudentPutAction()
    {
        if (!$this->studentLogic->getStudentFrom($user) instanceof Student) {
            $error[] = 'Böyle bir öğrenci yok !';
        }
        if (!empty($error)) {
            $payload->setStatus(false)->setMessages($error);
            return [
                'payload' => $payload,
                'code' => Response::HTTP_BAD_REQUEST
            ];
        }

        $studentUpdate = $this->studentLogic->updateStudent($user);

    }


    /**
     * @Route("/api/delete-student/{id}", name="api_student_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function studentDeleteAction($id)
    {
        if($student instanceof Student)
        {
            if($student->getUser()->getId() == $user)
            {
                $deleteStudent = $this->studentLogic->removeStudent($id);
                if($deleteStudent === true)
                {
                    $logData = [
                        'user' => $user,
                        'content' => 'Üniversite öğrenci başvurunuzu sildiniz.',
                        'ip' => $_SERVER["REMOTE_ADDR"],
                        'type' => 1
                    ];
                    $this->logService->saveLog($logData);
                    $payload->setStatus(true)->setMessages(['Öğrenci başvurunuz başarıyla silindi !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_OK
                    ];
                }else {
                    $payload->setStatus(false)->setMessages(['Öğrenci kaydınız onaylandığı için silinemez !']);
                    return [
                        'payload'=>$payload,
                        'code'=>Response::HTTP_NOT_FOUND
                    ];
                }
            }else{
                $payload->setStatus(false)->setMessages(['Geçersiz işlem !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        } else  {
            $payload->setStatus(false)->setMessages(['Böyle bir öğrenci yok !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }
    }




}