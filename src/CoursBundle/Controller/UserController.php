<?php

namespace CoursBundle\Controller;

use CoursBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * User controller.
 *
 * @Route("api/user")
 */
class UserController extends Controller
{
    /**
     * Lists all users entities.
     * @Route("/all", name="user_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $posts=$this->getDoctrine()->getRepository('CoursBundle:User')->findAll();

        if (!count($posts)){
            $response=array(

                'code'=>1,
                'message'=>'No posts found!',
                'errors'=>null,
                'result'=>null

            );


            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }


        $data=$this->get('jms_serializer')->serialize($posts,'json');

        $response=array(

            'code'=>0,
            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)

        );

        return new JsonResponse($response,200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Method({"POST"})
     * @Route("/new", name="user_new")
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        $post=$this->get('jms_serializer')->deserialize($data,'CoursBundle\Entity\User','json');




        $em=$this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();


        $response=array(

            'code'=>0,
            'message'=>'Post created!',
            'errors'=>null,
            'result'=>null

        );

        return new JsonResponse($response,Response::HTTP_CREATED);

    }

    /**
     * Finds and displays a user entity.
     * @Method({"GET"})
     * @Route("/{id}", name="user_show")
     */
    public function showAction( $id)
    {
        $post=$this->getDoctrine()->getRepository('CoursBundle:User')->find($id);


        if (empty($post)){
            $response=array(
                'code'=>1,
                'message'=>'cours not found',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data=$this->get('jms_serializer')->serialize($post,'json');


        $response=array(

            'code'=>0,
            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)

        );

        return new JsonResponse($response,200);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"PUT"})
     * @return JsonResponse
     */
    public function editAction(Request $request,  $id)
    {
        $post=$this->getDoctrine()->getRepository('CoursBundle:User')->find($id);

        if (empty($post))
        {
            $response=array(

                'code'=>1,
                'message'=>'user Not found !',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $body=$request->getContent();


        $data=$this->get('jms_serializer')->deserialize($body,'CoursBundle\Entity\User','json');



        $post->setName($data->getName());
        $post->setEmail($data->getEmail());
        $post->setNumber($data->getNumber());
        $post->setRole($data->getRole());

        $em=$this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $response=array(

            'code'=>0,
            'message'=>'Post updated!',
            'errors'=>null,
            'result'=>null

        );

        return new JsonResponse($response,200);

    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}/delete", name="user_delete")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $post=$this->getDoctrine()->getRepository('CoursBundle:User')->find($id);

        if (empty($post)) {

            $response=array(

                'code'=>1,
                'message'=>'user Not found !',
                'errors'=>null,
                'result'=>null

            );


            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $response=array(

            'code'=>0,
            'message'=>'post deleted !',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,200);
    }



}
