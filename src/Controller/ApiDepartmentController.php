<?php

namespace App\Controller;

use App\Entity\Department;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiDepartmentController extends AbstractFOSRestController
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Department::class))
     *     )
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Input Error",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Department::class))
     *     )
     * )
     * @SWG\Tag(name="All departments")
     * @Rest\Get("/api/department", name="get_api_departments")
     * @Rest\View()
     */
    public function getDepartmentsAction(ObjectManager $manager)
    {
        $deps = $manager->getRepository(Department::class)->findAll();
        if (null === $deps) {
            throw new  HttpException(500, "No Department has been registered");
        }
        return $deps;
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(@SWG\Items(ref=@Model(type=Department::class)))
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Input Error",
     *     @SWG\Schema(@SWG\Items(ref=@Model(type=Department::class)))
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="the department unique identifier."
     * )
     * @SWG\Tag(name="Get department by id")
     * @Rest\Get("/api/department/{id}", name="get_api_department", requirements={"id" = "\d+"})
     * @Rest\View()
     */
    public function getDepartmentAction(ObjectManager $manager, $id)
    {
        $deps = $manager->getRepository(Department::class)->find($id);
        if (null === $deps) {
            throw new  HttpException(500, "No Department found for id ".$id);
        }
        $view = new View($deps);
        return $view;
    }
}
