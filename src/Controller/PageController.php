<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use PHPSupabase;

final class PageController extends AbstractController
{
    #[Route('/newUser', name: 'app_new')]
    public function index(): JSONResponse
    {
        //Inicializamos el servicio
        $service = new PHPSupabase\Service(
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV6c2pla21vZWtwYmVjenB4bmpsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE3MDc3NzMsImV4cCI6MjA1NzI4Mzc3M30.54iqh4UWIDUKSpWH2H5bgFISsakkvJEvPR1UEhclmr8",
            "https://ezsjekmoekpbeczpxnjl.supabase.co/auth/v1"
        );

        //Creamos un usuario en supabase
        $auth = $service->createAuth();

        $user_metadata =  [
            'display_name' => 'Rommel',
        ];

        try {
            $auth->createUserWithEmailAndPassword('rommel.xana@gmail.com','123456',$user_metadata);
            $data = $auth->data();
            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $auth->getError()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/login', name: 'app_login')]
    public function login(SessionInterface $session): JSONResponse
    {
        //Inicializamos el servicio
        $service = new PHPSupabase\Service(
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV6c2pla21vZWtwYmVjenB4bmpsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE3MDc3NzMsImV4cCI6MjA1NzI4Mzc3M30.54iqh4UWIDUKSpWH2H5bgFISsakkvJEvPR1UEhclmr8",
            "https://ezsjekmoekpbeczpxnjl.supabase.co/auth/v1"
        );

        //Nos autenticamos con ub usuario
        $auth = $service->createAuth();

        try {
            $auth->signInWithEmailAndPassword('rommel.xana@gmail.com','123456');
            //Conseguimos la información de la autentificación
            $data = $auth->data();

            //conseguimos los tokens y los guardamos en una sesión
            $access_token = $data->access_token;;
            $refresh_token = $data->refresh_token;;

            $session->set('tokens', [
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ]);

            //Conseguimos la información del usuario con el token de accesso
            $tokens = $session->get('tokens');
            $user_data = $auth->getUser($tokens['access_token']);

            //User id || con la entidad guardariamos toda lo información en la entidad y guardriamos esa entidad en una sesión
            $user_id = $user_data->id;
            $session->set('user_id', $user_id);

            return new JsonResponse($user_data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $auth->getError()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): JSONResponse {
        # $session->remove('tokens');
        $session->clear();

        return new JsonResponse($session->all());
    }

    #[Route('/updateUser', name: 'app_update')]
    public function update(SessionInterface $session): JSONResponse {
        $service = new PHPSupabase\Service(
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV6c2pla21vZWtwYmVjenB4bmpsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE3MDc3NzMsImV4cCI6MjA1NzI4Mzc3M30.54iqh4UWIDUKSpWH2H5bgFISsakkvJEvPR1UEhclmr8",
            "https://ezsjekmoekpbeczpxnjl.supabase.co/auth/v1"
        );

        $auth = $service->createAuth();
        $tokens = $session->get('tokens');
        $access_token = $tokens['access_token'];

        $new_metadata = [
            'display_name' => 'Alexander',
        ];

        try {
            //EMAIL, PASSWORD, METADATA
            $data = $auth->updateUser($access_token, null, null, $new_metadata);
            return new JsonResponse(['update' => 'Se ha actualizado correcctamente']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $auth->getError()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
