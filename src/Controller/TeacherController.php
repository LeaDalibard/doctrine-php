<?php

namespace App\Controller;

use App\Entity\Student;
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

    /**
     * @Route("/teachers", name="teachers")
     */

    public function teachers()
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAll();

        return $this->render('teacher/teachers.html.twig', [
            "teachers" => $teachers
        ]);

    }

    /**
     * @Route("/teacher/{id}", name="teacher")
     */
    public function product(int $id): Response
    {
        $teacher=new Teacher();
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        $students=$teacher->getStudents();
        return $this->render("teacher/teacher.html.twig", [
            "teacher" => $teacher,
            "students"=>$students,
        ]);

    }

    /**
     * @Route("/delete-teacher/{id}", name="deleteTeacher")
     */
    public function deleteTeacher(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $teacher = $entityManager->getRepository(Teacher::class)->find($id);
        $entityManager->remove($teacher);
        $entityManager->flush();
        return $this->redirectToRoute("teachers");
    }

    /**
     * @Route("/update-teacher/{id}", name="updateTeacher")
     */
    public function updateTeacher(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $teacher = $entityManager->getRepository(Teacher::class)->find($id);
        $form = $this->createForm(TeacherFormType::class, $teacher);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }

        return $this->render("teacher/teacher-form.html.twig", [
            "form_title_teacher" => "Update teacher",
            "form_teacher" => $form->createView(),
        ]);
    }

}
