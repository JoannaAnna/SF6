<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Products;
use App\Form\ProductsFormType;

#[Route('/admin/products', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        // On refuse l'accès pour ajouter le produit à toute personne qui n'a pas ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée un nouveau produit 
        $product = new Products();

        // On crée le formulaire
        $formProduct = $this->createForm(ProductsFormType::class, $product);

        return $this->render('admin/products/add.html.twig',
        [
            'formProduct' =>$formProduct->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Products $product): Response
    {
        // On vérifie si l’utilisateur peut éditeur les produits avec le voter.
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function dejete(Products $product): Response
    {
        // On vérifie si l'utilisateur peut supprimer les produits avec le voter.
        $this->denyAccessUnlessGranted('PRODUCT_DELTE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}

