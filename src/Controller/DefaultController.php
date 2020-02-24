<?php


namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Message;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function Contact(Request $request, SerializerInterface $serializer)
    {
        $contact = New Contact();
        // je récupère l'ip du user avec la méthode getClientIp
        $ip = $request->getClientIp();
        // j'encode cette ip avec la méthode anonymize de la classe IpUtils
        $ipanonymize = IpUtils::anonymize($ip);
        // je set l'ip de l'utilisateur
        $contact->setIp($ipanonymize);

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && isset($_POST['g-recaptcha-response'])) {
            //j'utilise la function grecpatcha que j'ai crée pour décodé la requête POST
            if ($this->grecaptcha()->success == true) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();
            }
            $data = $request->getContent();
            return new JsonResponse($data);
        }

        return $this->render('client/contact.html.twig',
            [
                'form' => $form->createView()
            ]);
    }


    private function grecaptcha()
    {
        //  Je construis le POST request pour le recaptcha:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Lf7E9sUAAAAALmHU1YK0HjD1xcVrecAebmmY3Lx';
        $recaptcha_response = $_POST['g-recaptcha-response'];

        // Je decode le POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        return $recaptcha;
    }
}