<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Products;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/products', name: 'app_admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        // On refuse l'accès pour ajouter le produit à toute personne qui n'a pas ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée un nouveau produit 
        $product = new Products();

        // On crée le formulaire
        $formProduct = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $formProduct->handleRequest($request);

        //dd($formProduct);

        // On vérifie si le formulaire est soumis et valide
        if($formProduct->isSubmitted() && $formProduct->isValid())
        {
            // On arrondit le prix
            $prix = $product->getPrice()*100;
            $product->setPrice($prix);

            // On stocke le produit en BDD
            $em->persist($product);
            $em->flush();

            // On redirige
            return $this->redirectToRoute('app_admin_products_index');
        }

        return $this->render('admin/products/add.html.twig',
        [
            'formProduct' =>$formProduct->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, 
        EntityManagerInterface $em): Response
    {
        // On vérifie si l’utilisateur peut éditeur les produits avec le voter.
        // $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        // On refuse l'accès pour ajouter le produit à toute personne qui n'a pas ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');

        // On divise le prix par 100
        $prix = $product->getPrice()/100;
        $product->setPrice($prix);

        // On crée le formulaire
        $formProduct = $this->createForm(ProductsFormType::class, $product);

        // On traite la requête du formulaire
        $formProduct->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if($formProduct->isSubmitted() && $formProduct->isValid())
        {
            // On arrondit le prix
            $prix = $product->getPrice()*100;
            $product->setPrice($prix);

            // On stocke le produit en BDD
            $em->persist($product);
            $em->flush();

            // On redirige
            return $this->redirectToRoute('app_admin_products_index');
        }

        return $this->render('admin/products/edit.html.twig', compact('formProduct'));
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function dejete(Products $product): Response
    {
        // On vérifie si l'utilisateur peut supprimer les produits avec le voter.
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}

