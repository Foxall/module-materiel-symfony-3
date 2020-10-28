<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Materials;

class MaterialController extends Controller
{
    /**
     * @Route("/", name="material")
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Materials::class, );

        $materials = $repo->findAll();



        return $this->render('material/index.html.twig', [
            'controller_name' => 'MaterialController',
            'materials' => $materials,
        ]);
    }

    /**
     * @Route("/details/{id}", name="detail-material")
     */
    public function details($id)
    {
        $repo = $this->getDoctrine()->getRepository(Materials::class);
        $materials = $repo->find($id);

        return $this->render('material/details.html.twig', [
            'materials' => $materials,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier-material")
     */
    public function modifier($id, Request $request, ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Materials::class);
        $materials = $repo->find($id);

        $form = $this->createFormBuilder($materials)
                     ->add('Nom')
                     ->add('Prix')
                     ->add('Nombre')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($materials);
            $manager->flush();

            return $this->redirectToRoute('material');
        }

        return $this->render('material/modifier.html.twig', [
            'materials' => $materials,
            'formMaterial' => $form->createView(),
        ]);
    }
}