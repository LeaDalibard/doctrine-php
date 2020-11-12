<?php

namespace App\Controller;

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
        $form = $this->createForm(StudentFormType::class);

        return $this->render("rest_student/student-form.html.twig", [
            "form_title" => "Register student",
            "form_student" => $form->createView(),
        ]);
    }
}
