<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MaterielRepository $materielRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeMateriels = $form->get('commandeMateriels')->getData();
            $isValid = true;
            $prixTotal = 0;
            $commande->setCreatedAt(new \DateTime());
            $commande->setUpdatedAt(new \DateTime());

            foreach ($commandeMateriels as $commandeMateriel) {
                $materiel = $commandeMateriel->getMateriel();
                $quantite = $commandeMateriel->getQuantite();

                if ($quantite <= 0) {
                    $form->get('commandeMateriels')->addError(new FormError(sprintf(
                        'La quantité demandée pour %s est invalide.',
                        $materiel->getName()
                    )));
                    $isValid = false;
                }

                if ($quantite > $materiel->getStock()) {
                    $form->get('commandeMateriels')->addError(new FormError(sprintf(
                        'La quantité demandée pour %s dépasse le stock disponible.',
                        $materiel->getName()
                    )));
                    $isValid = false;
                } else {
                    $materiel->setStock($materiel->getStock() - $quantite);
                    $entityManager->persist($materiel);

                    $prixTotal += $materiel->getPrix() * $quantite;
                }
            }

            if ($isValid) {
                $commande->setPrixTotal($prixTotal);
                $entityManager->persist($commande);
                $entityManager->flush();

                return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('commande/new.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Créer',
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $commandeMateriels = $commande->getCommandeMateriels();
            foreach($commandeMateriels as $commandeMateriel) {
                $materiel = $commandeMateriel->getMateriel();
                $materiel->setStock($materiel->getStock() + $commandeMateriel->getQuantite());
                $entityManager->persist($materiel);
            }
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
