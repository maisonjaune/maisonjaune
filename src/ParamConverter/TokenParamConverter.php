<?php

namespace App\ParamConverter;

use App\Entity\Token;
use App\Repository\TokenRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TokenParamConverter implements ParamConverterInterface
{
    public function __construct(private TokenRepository $tokenRepository)
    {
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Token::class;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $allowedTypes = array_key_exists('types', $configuration->getOptions())
            ? $configuration->getOptions()['types']
            : [];

        $token = $this->tokenRepository->findOneByValue($request->get($configuration->getName()));

        if (null === $token) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $configuration->getClass()));
        }

        if (!$token->isValid()) {
            throw new NotFoundHttpException(sprintf('%s was expired.', $configuration->getClass()));
        }

        if (!in_array($token->getType(), $allowedTypes)) {
            throw new NotFoundHttpException(sprintf('%s type is not allowed.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $token);

        return true;
    }
}