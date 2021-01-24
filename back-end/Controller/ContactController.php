<?php
namespace App\Controller;

class ContactController extends AbstractController
{
    /**
     * @Route("/api/contact", name="api_contact_post_action", methods={"POST"})
     * @return array
     */
    public function contactPostAction() : array
    {
        $fullName = $request->request->get('fullName');
        $email = $request->request->get('email');
        $content = $request->request->get('content');
        if($this->contactLogic->createContact($fullName,$email,$content) instanceof Contact)
        {
            $payload->setStatus(true);
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