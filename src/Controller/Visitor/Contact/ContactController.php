<?php

namespace App\Controller\Visitor\Contact;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendEmailService;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'visitor.contact.create')]
    public function create(Request $request, 
    ContactRepository $contactRepository,
    SendEmailService $sendEmailService
    ): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $contactRepository->save($contact, true);

            $sendEmailService->send([
                "sender_email"          => $contact->getEmail(),
                "sender_name"           => $contact->getFirstName() . " " . $contact->getLastName(),
                "recipient_email"       => "jibe78@hotmail.com",
                "subject"               => "Demande d'informations l'association Divercity",
                "html_template"         => "email/contact.html.twig",
                "context"               => [
                    "message"           => $contact->getMessage(),
                    "contact_email"     => $contact->getEmail(),
                    "first_name"        => $contact->getFirstName(),
                    "last_name"         => $contact->getLastName(),
                    "telephone"         => $contact->getPhone(),
                    ]
                ]);
                
            $this->addFlash("success", "Votre message a bien été envoyé! Nous vous contacterons dans le plus brefs délais!");
            return $this->redirectToRoute("visitor.contact.create");      
        }

        return $this->render('pages/visitor/contact/create.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
