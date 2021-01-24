<?php
namespace App\Controller;


class UniversityController extends AbstractController
{

    /**
     * @Route("/api/admin/add-university", name="api_admin_add_university_post_action", methods={"POST"})
     * @return array
     */
    public function addUniversityPostAction() : array
    {
        $universityName = $request->request->get('universityName');

        if($this->universityLogic->getUniversityFromName($universityName) instanceof University)
        {
            $payload->setStatus(false)->setMessages(['Bu universite daha önce kayıt edildmiş !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_BAD_REQUEST
            ];
        }

        if($this->universityLogic->createUniversity() instanceof University)
        {
            $logData = [
                'user' => $this->getUser()->getId(),
                'content' => ''.$universityName.' isimli üniversiteyi eklediniz.',
                'ip' => $_SERVER["REMOTE_ADDR"],
                'type' => 1
            ];
            $this->logService->saveLog($logData);

            $payload->setStatus(true)->setMessages(['Üniversite başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/admin/update-university/{id}", name="api_admin_update_university_put_action", methods={"PUT"})
     * @param $id
     * @return array
     */
    public function updateUniversityPutAction($id) : array
    {
        if($id && $this->universityRepository->find($id) instanceof University)
        {

            $universityName = $this->requestStack->getCurrentRequest()->request->get('universityName');
            $university = $this->universityRepository->find($id);
            if($university->getUniversityName() != $universityName && $this->universityLogic->getUniversityFromName($universityName) instanceof University)
            {
                $payload->setStatus(false)->setMessages(['Bu isimde bir üniversite zaten kayıtlı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_BAD_REQUEST
                ];
            }

            if($this->universityLogic->updateUniversity($id) instanceof University)
            {
                $logData = [
                    'user' => $this->getUser()->getId(),
                    'content' => ''.$universityName.' isimli üniversiteyi güncellediniz.',
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'type' => 1
                ];
                $this->logService->saveLog($logData);

                $payload->setStatus(true)->setMessages(['Üniversite başarılı şekilde güncellendi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(['Üniversite güncellenirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];

            }

        }

        $payload->setStatus(false)->setMessages(['Üniversite bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/admin/update-university/{id}", name="api_admin_update_university_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updateUniversityGetAction($id) : array
    {
        if($id && $this->universityRepository->find($id) instanceof University)
        {
            $university = $this->universityRepository->find($id);
            $universityData = [
                'id' => $university->getId(),
                'universityName' => $university->getUniversityName(),
                'universityContent' => $university->getUniversityContent(),
                'universityLogo' => $university->getUniversityLogo(),
                'universityCity' => [
                    'id' => $university->getUniversityCity()->getId(),
                    'cityName' => $university->getUniversityCity()->getCityName(),
                    'cityCode' => $university->getUniversityCity()->getCityCode()
                ],
                'status' => $university->getStatus(),
                'createdAt' => $university->getCreatedAt(),
                'updateAt' => $university->getUpdateAt()
            ];
            $payload->setStatus(true)->setExtras($universityData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-university", name="api_list_university_get_action", methods={"GET"})
     * @return array|Payload
     */
    public function listUniversityGetAction()
    {
        if($this->universityRepository->searchListUniversity($search))
        {
            $universityAll = $this->universityRepository->searchListUniversity($search);
            foreach ($universityAll as $item)
            {
                $universityData = [
                    'id' => $item->getId(),
                    'universityName' => $item->getUniversityName(),
                    'universityContent' => $item->getUniversityContent(),
                    'universityLogo' => $item->getUniversityLogo(),
                    'slug' => $item->getSlug(),
                    'universityCity' => [
                        'id' => $item->getUniversityCity()->getId(),
                        'cityName' => $item->getUniversityCity()->getCityName(),
                        'cityCode' => $item->getUniversityCity()->getCityCode()
                    ],
                    'status' => $item->getStatus(),
                    'createdAt' => $item->getCreatedAt(),
                    'updateAt' => $item->getUpdateAt()
                ];
                $fullUniversity[] = $universityData;
            }
        }



        if (isset($fullUniversity)) {

            $pagination = $this->paginator->paginate(
                $fullUniversity, /* query NOT result */
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

        $payload->setStatus(false)->setMessages(['Üniversite bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/get-university/{slug}", name="api_get_university_get_action", methods={"GET"})
     * @param $slug
     * @return array
     */
    public function getUniversityGetAction($slug) : array
    {
        if($slug && $this->universityRepository->findOneBy(["slug"=>$slug]) instanceof University)
        {
            $university = $this->universityRepository->findOneBy(["slug"=>$slug]);
            $universityData = [
                'id' => $university->getId(),
                'universityName' => $university->getUniversityName(),
                'universityContent' => $university->getUniversityContent(),
                'universityLogo' => $university->getUniversityLogo(),
                'slug' => $university->getSlug(),
                'universityCity' => [
                    'id' => $university->getUniversityCity()->getId(),
                    'cityName' => $university->getUniversityCity()->getCityName(),
                    'cityCode' => $university->getUniversityCity()->getCityCode()
                ],
                'status' => $university->getStatus(),
                'createdAt' => $university->getCreatedAt(),
                'updateAt' => $university->getUpdateAt()
            ];
            $payload->setStatus(true)->setExtras($universityData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Üniversite bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

}