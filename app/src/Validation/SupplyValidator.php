<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class SupplyValidator  extends AbstractValidator
{
    /**
     * @return Collection
     */
    protected function getConstraint(): Collection
    {
        return new Collection([
            'count' => $this->getNumber(),
        ]);
    }

    /**
     * @return string
     */
    private function getMessageForNotBlank(): string
    {
        return 'Поле обязательно к заполнению';
    }

    /**
     * Возвращает правила валидации для данных введённых в строку поиска
     *
     * @return array
     */
    private function getNumber(): array
    {
        return [
            new Assert\Regex([
                'pattern' => "/^\d+$/",
                'message' => 'Вы пытаетесь ввести недопустимые символы.',
            ]),
        ];
    }
}