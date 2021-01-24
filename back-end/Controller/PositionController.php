<?php
namespace App\Controller;


class PositionController extends AbstractController
{

    /**
     * @Route("/api/admin/add-position", name="api_admin_add_position_post_action", methods={"POST"})
     * @return array
     */
    public function addPositionPostAction() : array
    {
        $positionName = $request->request->get('positionName');

        if($this->positionLogic->getPositionFromName($positionName) instanceof Position)
        {
            $payload->setStatus(false)->setMessages(['Bu pozisyon daha önce kayıt edildmiş !']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_NOT_FOUND
            ];
        }

        if($this->positionLogic->createPosition() instanceof Position)
        {

            $payload->setStatus(true)->setMessages(['Pozisyon başarılı şekilde kaydedildi.']);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_CREATED
            ];
        }

        $payload->setStatus(false)->setMessages(['Pozisyon kaydı sırasında hata oluştu !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];

    }

    /**
     * @Route("/api/admin/update-position/{id}", name="api_admin_update_position_put_action", methods={"PUT"})
     * @param $id
     * @return array
     */
    public function updatePositionPutAction($id) : array
    {
        if($id && $this->positionRepository->find($id) instanceof Position)
        {

            $positionName = $this->requestStack->getCurrentRequest()->request->get('positionName');
            $position = $this->positionRepository->find($id);
            if($position->getPositionName() != $positionName && $this->positionLogic->getPositionFromName($positionName) instanceof Position)
            {
                $payload->setStatus(false)->setMessages(['Bu isimde bir pozisyon zaten kayıtlı !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }

            if($position instanceof Position)
            {
                $this->positionLogic->updatePosition($id);


                $payload->setStatus(true)->setMessages(['Pozisyon başarılı şekilde güncellendi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_OK
                ];
            } else {

                $payload->setStatus(false)->setMessages(['Pozisyon güncellenirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];

            }

        }

        $payload->setStatus(false)->setMessages(['Pozisyon bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }


    /**
     * @Route("/api/admin/update-position/{id}", name="api_admin_update_position_get_action", methods={"GET"})
     * @param $id
     * @return array
     */
    public function updatePositionGetAction($id) : array
    {
        if($id && $this->positionRepository->find($id) instanceof Position)
        {
            $position = $this->positionRepository->find($id);
            $positionData = [
                'id' => $position->getId(),
                'positionName' => $position->getPositionName(),
            ];
            $payload->setStatus(true)->setExtras($positionData);
            return [
                'payload'=>$payload,
                'code'=>Response::HTTP_OK
            ];
        }

        $payload->setStatus(false)->setMessages(['Pozisyon bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/list-position", name="api_list_position_get_action", methods={"GET"})
     * @return array
     */
    public function listPositionGetAction()
    {
        if($this->positionRepository->searchListPosition($search))
        {
            $positionAll = $this->positionRepository->searchListPosition($search);
            foreach ($positionAll as $item)
            {
                $positionData = [
                    'id' => $item->getId(),
                    'positionName' => $item->getPositionName()
                ];
                $fullPosition[] = $positionData;
            }
            if (isset($fullPosition)) {

                $pagination = $this->paginator->paginate(
                    $fullPosition, /* query NOT result */
                    $request->query->getInt('page', 1), /*page number*/
                    99999 /*limit per page*/
                );

                return [
                    'status' => true,
                    'page'=>$pagination->getCurrentPageNumber(),
                    'pageCount'=>ceil($pagination->getTotalItemCount() / 99999),
                    'pageItemCount'=>$pagination->count(),
                    'total'=>$pagination->getTotalItemCount(),
                    'data'=>$pagination->getItems(),
                    'code' => Response::HTTP_OK
                ];

            }
        }
        $payload->setStatus(false)->setMessages(['Pozisyon bulunamadı !']);
        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }

    /**
     * @Route("/api/admin/delete-position/{id}", name="api_admin_position_delete_action", methods={"DELETE"})
     * @param $id
     * @return array
     */
    public function positionDeleteAction($id): array
    {
        if($id && $this->positionRepository->find($id) instanceof Position)
        {
            $deletePosition = $this->positionLogic->removePosition($id);
            if($deletePosition === true)
            {

                $payload->setStatus(true)->setMessages(['Pozisyon başarılı şekilde silindi.']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_ACCEPTED
                ];
            } else {
                $payload->setStatus(false)->setMessages(['Pozisyon silinirken hata oluştu !']);
                return [
                    'payload'=>$payload,
                    'code'=>Response::HTTP_NOT_FOUND
                ];
            }
        }

        $payload->setStatus(false)->setMessages(['Pozisyon bulunamadı !']);

        return [
            'payload'=>$payload,
            'code'=>Response::HTTP_NOT_FOUND
        ];
    }
}