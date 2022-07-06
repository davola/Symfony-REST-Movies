<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use App\Entity\Movie;
use App\Entity\Rating;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MovieDataPersister implements DataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function supports($data): bool
    {
        return $data instanceof Movie;
    }

    /**
     * @param Movie $data
     */
    public function persist($data)
    {
        if (empty($data->getCasts()->getValues())){
            throw new ValidationException('casts: This value should not be null.');
        }
        if (empty($ratings = $data->getRatings()->getValues())){
            throw new ValidationException('ratings: This value should not be null.');
        }
        /** @var Rating $rating */
        foreach ($ratings as $rating){
            if (is_string($rating->getValue())){
                throw new ValidationException('ratings: The value can\'t be string. Make a float instead.');
            }
            if (is_numeric($rating->getName())){
                throw new ValidationException('ratings: The name can\'t be a number. Make a string instead.');
            }
        }
        if (!is_string($data->getDirectorName())){
            throw new ValidationException('director: The name must be a string instead.');
        }
        $data->setOwner($this->security->getUser());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
