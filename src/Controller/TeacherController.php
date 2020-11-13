<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacher")
     */
    public function index(): Response
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }
    /**
     * @Route("/TeacherForm", name="TeacherForm")
     */
    public function addTeacher(Request $request): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();

        }

        return $this->render("teacher/teacher-form.html.twig", [
            "form_title_teacher" => "Register teacher",
            "form_teacher" => $form->createView(),
        ]);
    }


}
