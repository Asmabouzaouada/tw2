<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\BookRepository;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook', name: 'app_add_book', methods: ['GET', 'POST'])]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

           // return $this->rediectToRoute('app_book'); // Redirection vers la page des livres
        }

        return $this->render('book/addBook.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     
    #[Route('/listBook', name: 'app_AfficheBook')]
    public function listBook(BookRepository $repository ):response
    {
        $book=$repository->findAll(); 
        return $this->render('/book/listBook.html.twig', ['books' => $book]);

    }
    
    
    #[Route('/book/delete/{id}', name: 'app_delete_book', methods: ['POST'])]
    public function deleteBookr(EntityManagerInterface $entityManager, Book $book): Response
    {
        if ($book)
       {
        $entityManager->remove($book);
        $entityManager->flush();
       }
       

        return $this->redirectToRoute('app_AfficheBook');
    }



}
