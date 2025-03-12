<?php

namespace App\Controller;

use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use PHPSupabase;

final class NotasController extends AbstractController
{
    #[Route('/notas', name: 'app_notas')]
    public function index(SessionInterface $session): JsonResponse
    {
        $service = new PHPSupabase\Service(
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV6c2pla21vZWtwYmVjenB4bmpsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE3MDc3NzMsImV4cCI6MjA1NzI4Mzc3M30.54iqh4UWIDUKSpWH2H5bgFISsakkvJEvPR1UEhclmr8",
            "https://ezsjekmoekpbeczpxnjl.supabase.co/auth/v1"
        );

        //Si está activada la seguridad RLS tenemos que pasaer el token de accceso
        $tokens = $session->get('tokens');
        $accessToken = $tokens['access_token'];

        $notas = $service->setBearerToken($accessToken)->initializeDatabase('notas', 'id');
        //$notas = $service->initializeDatabase('notas','id');

        $newNota = [
            'titulo' => 'Nota creada desde Symony 2',
        ];

        try {
            $data = $notas->insert($newNota);
            return new JsonResponse($data);
        } catch (Exception $e) {
            return new JsonResponse(['error' =>  $e->getMessage()], 500);
        }
    }

    #[Route('/updateNota', name: 'app_updateNote')]
    public function update(SessionInterface $session): JsonResponse
    {
        $service = new PHPSupabase\Service(
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV6c2pla21vZWtwYmVjenB4bmpsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE3MDc3NzMsImV4cCI6MjA1NzI4Mzc3M30.54iqh4UWIDUKSpWH2H5bgFISsakkvJEvPR1UEhclmr8",
            "https://ezsjekmoekpbeczpxnjl.supabase.co/auth/v1"
        );

        //Si está activada la seguridad RLS tenemos que pasaer el token de accceso
        $tokens = $session->get('tokens');
        $accessToken = $tokens['access_token'];

        //$notas = $service->setBearerToken($accessToken)->initializeDatabase('notas', 'id');
        $notas = $service->initializeDatabase('notas','id');

        $updateNota = [
            'titulo' => 'Nota actualizada desde Symony',
        ];

        try {
            $data = $notas->update('5',$updateNota);
            return new JsonResponse($data);
        } catch (Exception $e) {
            return new JsonResponse(['error' =>  $e->getMessage()], 500);
        }
    }

    #[Route('/deleteNota', name: 'app_deleteNote')]
    public function delete(SessionInterface $session): JsonResponse
    {
        $service = new PHPSupabase\Service(
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV6c2pla21vZWtwYmVjenB4bmpsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE3MDc3NzMsImV4cCI6MjA1NzI4Mzc3M30.54iqh4UWIDUKSpWH2H5bgFISsakkvJEvPR1UEhclmr8",
            "https://ezsjekmoekpbeczpxnjl.supabase.co/auth/v1"
        );

        //Si está activada la seguridad RLS tenemos que pasaer el token de accceso
        $tokens = $session->get('tokens');
        $accessToken = $tokens['access_token'];

        //$notas = $service->setBearerToken($accessToken)->initializeDatabase('notas', 'id');
        $notas = $service->initializeDatabase('notas','id');

        try {
            $data = $notas->delete('6');
            return new JsonResponse($data);
        } catch (Exception $e) {
            return new JsonResponse(['error' =>  $e->getMessage()], 500);
        }
    }
}
