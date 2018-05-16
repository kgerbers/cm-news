<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Service\NewsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{

    /**
     * @Route("/settings", name="settings")
     */
    public function settingsAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $feed_list = $em->getRepository('AppBundle:Feed')->findAll();
        $user = $em->getRepository('AppBundle:User')->findOneBy(['email' => $this->getUser()->getEmail()]);

        $feed_choices = [];
        foreach ($feed_list as $feed) {
            $feed_choices[$feed->getTitle()] = $feed->getId();
        }

        $properties = $user->getProperties();
        if (!$properties) {
            $properties = [];
        }

        $form = $this->createFormBuilder($user);
        $form
            ->add('email', EmailType::class)
            ->add('fullname', TextType::class)
            ->add('_password', PasswordType::class, ['mapped'=>false, 'required' => false])
            ->add('_password_check', PasswordType::class, ['mapped'=>false, 'required' => false])
            ->add('properties_main_background_color', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'data' => array_key_exists('main_background_color', $properties) ? $properties['main_background_color']:''
            ])
            ->getForm();

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $formData = $request->request->get('form');
            if ($formData['_password'] && $formData['_password'] !== $formData['_password_check']) {
                $this->addFlash('danger', 'Passwords are not the same');
                return $this->render('default/settings.html.twig', [
                    'feed_list' => $feed_list,
                    'form' => $form->createView()
                ]);
            }

            if ($formData['properties_main_background_color']) {
                $properties['main_background_color'] = $formData['properties_main_background_color'];
            }

            $user->setProperties($properties);

            if ($formData['_password']) {
                $password = $encoder->encodePassword($user, $formData['_password']);
                $user->setPassword($password);
            }

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Settings saved');
        }

        return $this->render('default/settings.html.twig', [
            'feed_list' => $feed_list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}
