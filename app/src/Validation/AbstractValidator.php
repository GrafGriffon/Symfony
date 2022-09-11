<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    /**
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * Возвращает правила валидации
     *
     * @return Collection
     */
    abstract protected function getConstraint(): Collection;

    /**
     * Валидирует данные и возвращает массив с ошибками (или пустой массив, если ошибок нет)
     *
     * @param array $requestFields
     *
     * @return array
     */
    public function validate(array $requestFields): array
    {
        $errors = [];

        foreach ($this->validator->validate($requestFields, $this->getConstraint()) as $violation) {
            $field = preg_replace(['/\]\[/', '/\[|\]/'], ['.', ''], $violation->getPropertyPath());
            $errors[$field] = $violation->getMessage();
        }

        return $errors;
    }
}
