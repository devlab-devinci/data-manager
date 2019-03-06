<?php

namespace App\Controller\Admin;

use App\Entity\Box;
use App\Form\BoxType;
use App\Repository\BoxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

/**
 * @Route("/admin/box")
 */
class BoxController extends AbstractController
{
    /**
     * @Route("/index", name="admin_box_index", methods={"GET"})
     */
    public function index(BoxRepository $boxRepository): Response
    {
        return $this->render('admin/box/index.html.twig', [
            'boxes' => $boxRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_box_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $box = new Box();
        $form = $this->createForm(BoxType::class, $box);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $box->getUploadCsv();
            $fileName = $fileUploader->upload($file);
            $box->setUploadCsv($fileName);


            $d = dir("uploads/data");


            while (false !== ($r = $d->read())) {
                if( $r != '.' and $r != '..'){
                    $fileName = $r;
                }
            }
            $fileHandle = fopen("uploads/data/".$fileName, "r");
            $i = 0;
            while (($row = fgetcsv($fileHandle, 0, ";")) !== FALSE) {
                $repository = $this->getDoctrine()->getRepository(Box::class);
                $item = $repository->findBy(['name'=>$row[5]]);
                if ($i > 0 and count($item) == 0){
                    $newBox = new Box();
                    $newBox -> setName($row[5]);
                    $newBox -> setPrice(floatval(str_replace(',' , '.' ,$row[6])));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newBox);
                    $em->flush();
                }
                $i++;
            }
            unlink('uploads/data/'.$fileName);
            // ... persist the $product variable or any other work

            return $this->redirectToRoute('admin_box_index');
        }

        return $this->render('admin/box/new.html.twig', [
            'box' => $box,
            'form' => $form->createView(),
        ]);

    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/{id}", name="admin_box_show", methods={"GET"})
     */
    public function show(Box $box): Response
    {
        return $this->render('admin/box/show.html.twig', [
            'box' => $box,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_box_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Box $box): Response
    {
        $form = $this->createForm(BoxType::class, $box);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_box_index', [
                'id' => $box->getId(),
            ]);
        }

        return $this->render('Admin/box:edit.html.twig', [
            'box' => $box,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_box_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Box $box): Response
    {
        if ($this->isCsrfTokenValid('delete'.$box->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($box);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_box_index');
    }
}
