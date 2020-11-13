<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Form\StudentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RestStudentController extends AbstractController
{
    /**
     * @Route("/rest/student", name="rest_student")
     */
    public function index(): Response
    {
        return $this->render('rest_student/index.html.twig', [
            'controller_name' => 'RestStudentController',
        ]);
    }

    /**
     * @Route("/RestStudent", name="RestStudent")
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

        return $this->render("rest_student/student-form.html.twig", [
            "form_title" => "Register student",
            "form_student" => $form->createView(),
        ]);
    }
}
