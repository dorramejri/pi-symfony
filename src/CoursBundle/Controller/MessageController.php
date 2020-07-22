<?php

namespace CoursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Message controller.
 *
 * @Route("api/message")
 */
class MessageController extends Controller
{


    /**
     * add msg.
     * @param Request $request
     * @Route("/send", name="addmess")
     * @return JsonResponse
     * @Method({"POST"})
     */
    public function addmessAction(Request $request)
    {
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls')
            ->setUsername('dorra.mejri@esprit.tn')
            ->setPassword('ilove22ilove**info');
        $mailer = new \Swift_Mailer($transport);

        $message=(new \Swift_Message('validation de participation '))
            ->setFrom('dorra.mejri@esprit.tn')
            ->setTo("dorra.mejri@esprit.tn")


                ->setBody( 'Code de validation de participation au cours : 1524','text/html');

       $res=$mailer->send($message);
// Send the message
        if(!($res)) {
            $response = array(
                'code' => 1,
                'message' => 'error',
                'error' => null,
                'result' => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $data=$this->get('jms_serializer')->serialize($res,'json');

            $response=array(

                'code'=>0,
                'message'=>'success',
                'errors'=>null,
                'result'=>json_decode($data)

            );


        return new JsonResponse($response,200);
    }

}
