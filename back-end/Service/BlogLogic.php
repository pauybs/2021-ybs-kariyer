<?php

namespace App\Logic;


class BlogLogic
{

    /**
     * @param $user
     * @return null|Blog
     */
    public function createBlog($user): ?Blog
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        if($this->blogRepository->findOneBy(["slug"=>$this->createSlugify($data["blogTitle"]),"isDeleted"=>0]) instanceof Blog)
        {
            $slug = $this->createSlugify($data["blogTitle"].substr($user,0,4));
        }else {
            $slug = $this->createSlugify($data["blogTitle"]);
        }
        $blog = new Blog();

        $blog
           ->setUser($this->entityManager->find(User::class,$user))
            ->setBlogTitle($data["blogTitle"])
            ->setBlogContent($data["blogContent"])
            ->setSlug($slug)
            ->setViews(0)
            ->setStatus(0)
            ->setIsDeleted(0)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($blog);
        $this->entityManager->flush();
        return $blog;

    }


    /**
     * @param $id
     * @return Blog|bool
     */
    public function updateBlog($id): ?Blog
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $data = $request->all();
        $blog = $this->blogRepository->find($id);
        if ($blog instanceof Blog) {

            $slug = $this->createSlugify($data["blogTitle"] . substr($blog->getUser()->getId(), 0, 4));

            $blog
                ->setBlogTitle($data["blogTitle"])
                ->setBlogContent($data["blogContent"])
                ->setSlug($slug)
                ->setStatus(0)
                ->setUpdateAt(new \DateTime());
            $this->entityManager->flush();
        }
    }

    /**
     * @param $id
     * @return null|Blog
     */
    public function removeBlog($id): ?Blog
    {
        $blog = $this->blogRepository->find($id);
        if($blog instanceof Blog)
        {
            $blog->setIsDeleted(1)
                ->setStatus(0);
            $this->entityManager->flush();

                return $blog;
        } else {
            return null;
        }
    }
}