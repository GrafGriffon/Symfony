<?php

namespace App\Validation;

use App\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class RegistrationValidator extends AbstractValidator
{
    /**
     * Возвращает список полей с правилами валидации
     *
     * @return Collection
     */
    protected function getConstraint(): Collection
    {
        return new Collection([
            'phone' => $this->getPhoneRules(),
            'username' => $this->minLenRules(),
            'firstName' => $this->minLenRules(),
            'lastName' => $this->minLenRules(),
            'password' => $this->minLenRules(),
            'email' => $this->getMailRules(),
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

    private function getPhoneRules(): array
    {
        return [
            new Assert\NotBlank([
                'message' => $this->getMessageForNotBlank(),
            ]),
            new Assert\Regex([
                'pattern' => "/^\+\d{7,}$/",
                'message' => 'Вы пытаетесь ввести недопустимые символы. Введите номер телефона в формате +************',
            ]),
        ];
    }

    private function getMailRules(): array
    {
        return [
            new Assert\Email([
                'message' => 'Вы пытаетесь ввести не email',
            ]),
        ];
    }

    private function minLenRules(): array
    {
        return [
            new Assert\NotBlank([
                'message' => $this->getMessageForNotBlank(),
            ]),
            new Assert\Length([
                'min' => 2,
                'max' => 45,
            ]),
        ];
    }
}