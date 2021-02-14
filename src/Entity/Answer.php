<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idQuestion;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_correct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuestion(): ?int
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(int $idQuestion): self
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function isCorrect(): ?bool
    {
        return $this->is_correct === 1;
    }

    public function setIsCorrect(bool $is_correct): self
    {
        $this->is_correct = $is_correct;

        return $this;
    }
}
