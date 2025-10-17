<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


     #[Route('/author/{name}', name: 'show_author')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/index.html.twig', [
            'name' => $name
        ]);
    }


    #[Route('/authors', name: 'list_authors')]
     public function listAuthors(): Response
     {
       $authors = [
        ['id' => 1, 'picture' => '/images/Victor_Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
                  ];

         return $this->render('author/list.html.twig', [
               'authors' => $authors
               ]);
    }
    #[Route('/author/details/{id}', name: 'author_details')]
public function authorDetails(int $id): Response
{
    $authors = [
        ['id' => 1, 'picture' => '/images/Victor_Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];

    $author = null;
    foreach ($authors as $a) {
        if ($a['id'] === $id) {
            $author = $a;
            break;
        }
    }

    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    return $this->render('author/showAuthor.html.twig', [
        'author' => $author
    ]);
}


#[Route('/Listuser', name: 'list_user')]
    public function ListUser(UserRepository $r , ManagerRegistry $mr): Response //1- injection de dépendance
    {
        $result = $r->findAll();  // 2- appel de la méthode findAll() recuperation de tous les utilisateurs
         //3- transmission des données à la vue
        return $this->render('user/listBD.html.twig', [
            'result' => $result,
        ]);
    }

      #[Route('/addUser', name: 'addUser')]
    public function addUser(ManagerRegistry $mr): Response 
     //3- l'injection de dépendance managerRegistry
    {
        //1- creation de l'instance de l'entité User
        $user = new User();
        //2- affectation des valeurs aux attributs de l'entité
        $user->setName("Alice");
        $user->setAge(28);
        $user->setEmail("alice@example.com");
        //4-recuperation de l'entity manager
        $em=$mr->getManager(); 
        //5- persister l'entité
        $em->persist($user);
        //6- flush
        $em->flush();
        //7- redirection vers la liste des utilisateurs
        return $this->redirectToRoute('list_user');
    }

      #[Route('/updateUser/{id}', name: 'updateUser')]
     public function updateUser(ManagerRegistry $mr,$id, UserRepository $repo): Response 
     //3- l'injection de dépendance managerRegistry + repository + id
    {
        //1- recuperation de l'utilisateur à modifier
       $user = $repo->find($id);
        //2- affectation des nouvelles valeurs aux attributs de l'entité
        $user->setName("amir");
        $user->setAge(29);
        $user->setEmail("amir@yazidi.com");
        //4-recuperation de l'entity manager
        $em=$mr->getManager(); 
        //5- persister l'entité
        $em->persist($user);
        //6- flush
        $em->flush();
        //7- redirection vers la liste des utilisateurs
        return $this->redirectToRoute('list_user');
    }


    
    #[Route('/deleteUser/{id}', name: 'deleteUser')]
    public function deleteUser(ManagerRegistry $mr, $id, UserRepository $repo): Response 
    {
        //1- recuperation de l'utilisateur à supprimer
        $user = $repo->find($id);

        //2- recuperation de l'entity manager
        $em = $mr->getManager();

        //3- suppression de l'entité
        $em->remove($user);

        //4- flush
        $em->flush();

        //5- redirection vers la liste des utilisateurs
        return $this->redirectToRoute('list_user');
    }


}
