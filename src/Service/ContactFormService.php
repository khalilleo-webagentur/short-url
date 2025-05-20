<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ContactForm;
use App\Repository\ContactFormRepository;
use DateTime;

final readonly class ContactFormService
{
    public function __construct(
        private ContactFormRepository $contactFormRepository,
    ) {
    }

    public function getById(int $id): ?ContactForm
    {
        return $this->contactFormRepository->find($id);
    }

    public function getByEmail(string $email): ?ContactForm
    {
        return $this->contactFormRepository->findOneBy(['email' => $email]);
    }

    public function getByName(string $name): ?ContactForm
    {
        return $this->contactFormRepository->findOneBy(['name' => $name]);
    }

    /**
     * @return ContactForm[]
     */
    public function getAll(): array
    {
        return $this->contactFormRepository->findBy(['isDeleted' => 0], ['id' => 'DESC']);
    }

    public function create(string $name, string $email, string $subject, string $message, ?string $remote): ContactForm
    {
        $contactForm = new ContactForm();
        $contactForm
            ->setName($name)
            ->setEmail($email)
            ->setSubject($subject)
            ->setMessage($message)
            ->setRemote($remote);

        $this->save($contactForm);

        return $contactForm;
    }

    public function save(ContactForm $model): ContactForm
    {
        $this->contactFormRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function delete(ContactForm $model): void
    {
        $this->contactFormRepository->remove($model, true);
    }
}
