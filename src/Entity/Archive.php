<?php

namespace App\Entity;

use App\Repository\ArchiveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchiveRepository::class)
 */
class Archive
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Nomenclature::class, inversedBy="archives")
     */
    private $nomenclature;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_paper;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomenclature(): ?Nomenclature
    {
        return $this->nomenclature;
    }

    public function setNomenclature(?Nomenclature $nomenclature): self
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getDatePaper(): ?\DateTimeInterface
    {
        return $this->date_paper;
    }

    public function setDatePaper(\DateTimeInterface $date_paper): self
    {
        $this->date_paper = $date_paper;

        return $this;
    }
}
