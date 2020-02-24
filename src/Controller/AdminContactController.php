<?php


namespace App\Controller;


use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContactController extends AbstractController
{
    /**
     * @Route ("/admin/listeMessage", name="admin_liste_message")
     * @param ContactRepository $contactRepository
     * @return Response
     */
    public function listeMessage(ContactRepository $contactRepository){

        // La méthode findbyDate a été crée dans le Contactrepository afin de trier par ordre décroissant
        $messages = $contactRepository->findByDate();
        return $this->render('admin/listeMessage.html.twig', [
            'messages' => $messages
        ]);
    }

    /**
     * @Route ("/admin/listeMessageDetails/{id}", name="admin_liste_message_show", methods={"GET"})
     * @param Contact $contact
     * @return Response
     */
    public function listeMessageShow(Contact $contact){
        return $this->render('admin/listeMessageShow.html.twig', [
            'contact' => $contact
        ]);
    }
}