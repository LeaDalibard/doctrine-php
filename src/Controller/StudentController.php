<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Form\StudentFormType;
use App\Form\TeacherFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/rest/student", name="student")
     */
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    /**
     * @Route("/Add-Student", name="AddStudent")
     */
    public function addStudent(Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

        }

        return $this->render("student/student-form.html.twig", [
            "form_title" => "Register student",
            "form_student" => $form->createView(),
        ]);
    }

    /**
     * @Route("/students", name="students")
     */

    public function students()
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        //$test=json_encode($students, JSON_PRETTY_PRINT);
        return $this->render('student/students.html.twig', [
            "students" => $students
        ]);
    }

    /**
     * @Route("/student/{id}", name="student")
     */
    public function product(int $id): Response
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        return $this->render("student/student.html.twig", [
            "student" => $student,
        ]);

    }

    /**
     * @Route("/update-student/{id}", name="updateStudent")
     */
    public function updateStudent(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $student = $entityManager->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentFormType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }

        return $this->render("student/student-form.html.twig", [
            "form_title" => "Update student",
            "form_student" => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-student/{id}", name="deleteStudent")
     */
    public function deleteStudent(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $student = $entityManager->getRepository(Student::class)->find($id);
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->redirectToRoute("students");
    }
    }