<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class CategoryValidator  extends AbstractValidator
{
    /**
     * Возвращает список полей с правилами валидации
     *
     * @return Collection
     */
    protected function getConstraint(): Collection
    {
        return new Collection([
            // здесь перечисляем имена полей, которые ожидаем для валидации
            'title' => $this->getTitile(),
            'parent' => $this->getParent(),
        ]);
    }

    /**
     * Возвращает текст сообщения об ошибке не заполненого поля
     *
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
    private function getTitile(): array
    {
        return [
            new Assert\Regex([
                'pattern' => "/^\w+$/",
                'message' => 'Вы пытаетесь ввести недопустимые символы.',
            ]),
        ];
    }

    /**
     * Возвращает правила валидации для данных введённых в строку поиска
     *
     * @return array
     */
    private function getParent(): array
    {
        return [
            new Assert\Regex([
                'pattern' => "/^\d+$/",
                'message' => 'Вы пытаетесь ввести недопустимые символы.',
            ]),
        ];
    }
}