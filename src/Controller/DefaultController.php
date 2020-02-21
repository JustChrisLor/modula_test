<?php


namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Message;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route ("/", name="accueil")
     */
    public function Accueil()
    {
        return $this->render('client/accueil.html.twig');
    }

    /**
     * @Route ("/admin/dashboard", name="admin_dashboard")
     */
    public function AdminDashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route ("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function Contact(Request $request)
    {
        $contact = new Contact();
        // je récupère l'ip du user avec la méthode getClientIp
        $ip = $request->getClientIp();
        // j'encode cette ip avec la méthode anonymize de la classe IpUtils
        $ipanonymize = IpUtils::anonymize($ip);
        $contact->setIp($ipanonymize);
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && isset($_POST['g-recaptcha-response'])) {
            // Je construis le POST request:
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = '6Lf7E9sUAAAAALmHU1YK0HjD1xcVrecAebmmY3Lx';
            $recaptcha_response = $_POST['g-recaptcha-response'];


            // Je decode le POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);

            if ($recaptcha->success == true) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();
                $this->addFlash('success', 'Votre email à bien été envoyé');
                return $this->redirectToRoute('contact');
            }  else{
                $this->addFlash('error', 'Une erreur est survenue veuillez re-remplir le formulaire');
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('client/contact.html.twig',
            [
                'contact' => $contact,
                'form' => $form->createView()
            ]);
    }
}