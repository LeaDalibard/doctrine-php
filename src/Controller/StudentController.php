<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Form\StudentFormType;
use App\Form\TeacherFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return $this->render('student/students.html.twig', [
            "students" => $students
        ]);
    }

    /**
     * @Route("/students-json/{id}", name="get_one_student", methods={"GET"})
     */
    public function getStudentJson($id): JsonResponse
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        $data = new JsonResponse([
            'id' => $student->getId(),
            'firstName' => $student->getFirstName(),
            'lastName' => $student->getLastName(),
            'email' => $student->getEmail(),
            'teacher' => [$student->getTeacher()->getFirstName(),$student->getTeacher()->getLastName()],
        ]);
        $data->setEncodingOptions( $data->getEncodingOptions() | JSON_PRETTY_PRINT );
        return $data;
    }

    /**
     * @Route("/students-json", name="get_students")
     */
    public function getStudentJson(): JsonResponse
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $data =  JsonResponse::fromJsonString($students);

        //$data=json_decode(json_encode($students, true,JSON_PRETTY_PRINT));
        return $this->render('student/json.html.twig', [
            "students" => $data
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
