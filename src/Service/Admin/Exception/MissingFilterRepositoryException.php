<?php

namespace App\Service\Admin\Exception;

use App\Service\Admin\FilterRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class MissingFilterRepositoryException extends \LogicException
{
    public function __construct(EntityRepository $repository, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf("The repository %s need to implements %s", get_class($repository), FilterRepositoryInterface::class), $code, $previous);
    }
}