<?php

namespace App\Security;


use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Doctrine\ORM\EntityManagerInterface;


class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $urlGenerator;
    private Security $security;
    private $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator,Security $security,EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security =$security;
        $this->entityManager = $entityManager;

    }


    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName ): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
       // $entityManager=$this->get(EntityManagerInterface::class);

        $user = $token->getUser()->getRoles();
        //$user1=$token->getUser();
        //$entityManager = $this->getDoctrine()->getManager();
    //$client= new Client();
        if (in_array('client',$user)){
            //$client=$entityManager->getRepository(Client::class)->findOneBy(['username'=>$user1->getUserIdentifier()]);
            //$session = $request->getSession();
            //$session->set('user', $user);
            //$session->set('user', 'besma');
            //$user1 = $this->security->getUser();
            return new RedirectResponse($this->urlGenerator->generate('app_welcomepage'));
        }
        elseif (in_array('artiste',$user)){
            return new RedirectResponse($this->urlGenerator->generate('app_welcomepageArtiste'));
        }
        elseif (in_array('admin',$user)){
            return new RedirectResponse($this->urlGenerator->generate('app_admin_index'));
        }
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
