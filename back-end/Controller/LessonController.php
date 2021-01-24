<?php
namespace App\Controller;

class LessonController extends AbstractController
{

    /**
     * @Route("/api/admin/add-lesson", name="api_admin_add_lesson_post_action", methods={"POST"})
     * @return array
     */
    public function addLessonPostAction() : array
    {
        $lessonName = $request->request->get('lessonName');

        if($this->lessonLogic->getLessonFromName($lessonName) instanceof Lesson)
        {
            $payload->setStatus(false)->setMessages(['Bu ders daha önce kayıt edildmiş !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }

        if($this->lessonLogic->createLesson() instanceof Lesson)
        {
            $logData = [
                'user' => $this->getUser()->getId(),
                'content' => ''.$lessonName.' isimli dersi eklediniz.',
                'ip' => $_SERVER["REMOTE_ADDR"],
                'type' => 1
            ];
            $this->logService->saveLog($logData);
            $payload->setStatus(true)->setMessages(['Ders başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Ders kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/admin/update-lesson/{id}", name="api_admin_update_lesson_put_action", methods={"PUT"})
     * @param $id
     * @return array
     */
    public function updateLessonPutAction($id) : array
    {
        if($id && $this->lessonRepository->find($id) instanceof Lesson)
        {

            $lessonName = $this->requestStack->getCurrentRequest()->request->get('lessonName');
            $lesson = $this->lessonRepository->find($id);
            if($lesson->getLessonName() != $lessonName && $this->lessonLogic->getLessonFromName($lessonName) instanceof Lesson)
            {
                $payload->setStatus(false)->setMessages(['Bu isimde bir ders zaten kayıtlı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            if($lesson instanceof Lesson)
            {
                $this->lessonLogic->updateLesson($id);
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$lessonName.' isimli dersi güncellediniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);


                $payload->setStatus(true)->setMessages(['Ders başarılı şekilde güncellendi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(['Ders güncellenirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];

            }

        }

        $payload->setStatus(false)->setMessages(['Ders bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/admin/update-lesson/{id}", name="api_admin_update_lesson_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateLessonGetAction($id) : array
    {
        if($id && $this->lessonRepository->find($id) instanceof Lesson)
        {
            $lesson = $this->lessonRepository->find($id);
            $lessonData = [
                'id' => $lesson->getId(),
                'lessonName' => $lesson->getLessonName(),
                'lessonContent' => $lesson->getLessonContent(),
                'status' => $lesson->getStatus(),
                'slug' => $lesson->getSlug(),
                'createdAt' => $lesson->getCreatedAt(),
                'updateAt' => $lesson->getUpdateAt()
            ];
            $payload->setStatus(true)->setExtras($lessonData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Ders bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-lesson", name="api_list_lesson_get_action", methods={"GET"})
     * @return array
     */
    public function listLessonGetAction()
    {
        if($this->lessonRepository->searchListLesson($search))
        {
            $lessonAll = $this->lessonRepository->searchListLesson($search);
            foreach ($lessonAll as $item)
            {
                $lessonData = [
                    'id' => $item->getId(),
                    'lessonName' => $item->getLessonName(),
                    'lessonContent' => $item->getLessonContent(),
                    'status' => $item->getStatus(),
                    'slug' => $item->getSlug(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt()
                ];
                $fullLesson[] = $lessonData;
            }
            if (isset($fullLesson)) {

                $pagination = $this->paginator->paginate(
                    $fullLesson, /* query NOT result */
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
        $payload->setStatus(false)->setMessages(['Ders bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/admin/delete-lesson/{id}", name="api_admin_lesson_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function lessonDeleteAction($id)
    {
        $payload = new Payload();

        if($id && $this->lessonRepository->find($id) instanceof Lesson)
        {
            $deleteLesson = $this->lessonLogic->removeLesson($id);
            if($deleteLesson === true)
            {
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$id.' id numaralı dersi sildiniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);

                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$id.' id numaralı ders silindi.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 5
                ];
                $this->logService->saveLog($logData);

                $payload->setStatus(true)->setMessages(['Ders başarılı şekilde silindi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_ACCEPTED
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Ders silinirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }

        $payload->setStatus(false)->setMessages(['Ders bulunamadı !']);

        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }



    /**
     * @Route("/api/get-lesson/{slug}", name="api_get_lesson_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function getLessonGetAction($slug) : array
    {
        if($slug && $this->lessonRepository->findOneBy(["slug"=>$slug]) instanceof Lesson)
        {
            $lesson = $this->lessonRepository->findOneBy(["slug"=>$slug]);
            $lessonData = [
                'id' => $lesson->getId(),
                'lessonName' => $lesson->getLessonName(),
                'lessonContent' => $lesson->getLessonContent(),
                'slug' => $lesson->getSlug(),
                'status' => $lesson->getStatus(),
                'createdAt' => $lesson->getCreatedAt(),
                'updateAt' => $lesson->getUpdateAt()
            ];
            $payload->setStatus(true)->setExtras($lessonData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Lesson bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


}