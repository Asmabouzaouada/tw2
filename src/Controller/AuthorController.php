<?php



namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Entity\Book;
class AuthorController extends AbstractController
{
   
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'app_show_author')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'n' => $name,
        ]);
    }
    #[Route('/showlist', name: 'app_show_list')]
    public function list()
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    
        return $this->render('author/list.html.twig', ['authors' => $authors]);
    }
    #[Route('/author/details/{id}', name: 'app_author_details')]
    public function authorDetails($id): Response
    {
       
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

        
        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }

        
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé.');
        }

        
        return $this->render('author/showAuthor.html.twig', [
            'author' => $author
        ]);
  
    
    }

    #[Route('/Affiche', name: 'app_Affiche')]
    public function Affiche(AuthorRepository $repository ):response
    {
        $author=$repository->findAll(); 
        return $this->render('/author/Affiche.html.twig', ['authors' => $author]);

    }
    #[Route('/Add', name: 'app_AddAuthor')]
   
    public function addAuthor(EntityManagerInterface $entityManager): Response
    {
        // Créer un nouvel objet Author avec des données statiques
        $author = new Author();
        $author->setUsername('JohnDoe');
        $author->setEmail('johndoe@example.com');

        // Utiliser l'EntityManager pour enregistrer l'auteur dans la base de données
        $entityManager->persist($author);
        $entityManager->flush();

        // Retourner une réponse pour indiquer que l'auteur a été ajouté
        return new Response('Auteur ajouté avec succès : ' . $author->getUsername());
    }

  #[Route('/addAuthor2', name: 'app_add_author', methods: ['GET', 'POST'])]
    public function addAuthor2(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la requête est de type POST (le formulaire a été soumis)
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $username = $request->request->get('username');
            $email = $request->request->get('email');

            // Créer un nouvel objet Author et définir les valeurs
            $author = new Author();
            $author->setUsername($username);
            $author->setEmail($email);

            // Sauvegarder l'auteur dans la base de données
            $entityManager->persist($author);
            $entityManager->flush();

            // Rediriger ou retourner un message de succès
            return new Response('Auteur ajouté avec succès : ' . $author->getUsername());
        }

        // Afficher le formulaire si la requête est de type GET
        return $this->render('author/addAuthor.html.twig');
    }
    #[Route('/author/edit/{id}', name: 'app_edit_author')]
public function editAuthor(Request $request, EntityManagerInterface $entityManager, Author $author): Response
{
    if ($request->isMethod('POST')) {
        $author->setUsername($request->request->get('username'));
        $author->setEmail($request->request->get('email'));

        $entityManager->flush();

        return $this->redirectToRoute('app_Affiche');
    }

    return $this->render('author/edit.html.twig', [
        'author' => $author,
    ]);
}

    // Méthode de suppression d'un auteur
    #[Route('/author/delete/{id}', name: 'app_delete_author', methods: ['POST'])]
    public function deleteAuthor(EntityManagerInterface $entityManager, Author $author): Response
    {
        if ($author)
       {
        $entityManager->remove($author);
        $entityManager->flush();
       }
       

        return $this->redirectToRoute('app_Affiche');
    }

   
    }
    



