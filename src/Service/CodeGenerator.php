<?php

namespace App\Service;


use App\Entity\Code;
use Doctrine\ORM\EntityManagerInterface;

class CodeGenerator
{

    private const VALID_LETTERS = ['A', 'B', 'C', 'D', 'E', 'F'];
    private const VALID_NUMBERS = [2, 3, 4, 5, 6, 7, 8, 9];
    private const CODE_LENGTH = 10;
    private const MAX_LETTERS_COUNT = 6;
    private const MAX_NUMBERS_COUNT = 4;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function createCodeEntity()
    {
        $code = new Code();
        $code->setCode($this->generateCode());
        $code->setDate(new \DateTime());
        $this->entityManager->persist($code);
        $this->entityManager->flush();
        return $code->getCode();
    }

    private function generateCode()
    {
        $lettersCount = 0;
        $numbersCount = 0;
        $code = '';

        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $validCharacters = array_merge(self::VALID_LETTERS, self::VALID_NUMBERS);

            switch (true) {
                case $lettersCount < self::MAX_LETTERS_COUNT && $numbersCount < self::MAX_NUMBERS_COUNT:
                    $characterOrder = rand(0, count($validCharacters) - 1);
                    break;
                case $lettersCount === self::MAX_LETTERS_COUNT:
                    $characterOrder = rand(count(self::VALID_LETTERS), count($validCharacters) - 1);
                    break;
                case $numbersCount === self::MAX_LETTERS_COUNT:
                    $characterOrder = rand(0, count(self::VALID_LETTERS) - 1);
                    break;
            }

            if($characterOrder < count(self::VALID_LETTERS)) {
                $lettersCount++;
            } else {
                $numbersCount++;
            }

            $code .= $validCharacters[$characterOrder];
        }

        return $code;
    }

    public function generateFewEntities($count)
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $this->createCodeEntity();
        }
        return $result;
    }
}