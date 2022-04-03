<?php

namespace App\Entity;

use App\Repository\NomenclatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NomenclatureRepository::class)
 */
class Nomenclature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="nomenclature")
     */
    private $products;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_deleted;

    /**
     * @ORM\OneToMany(targetEntity=Archive::class, mappedBy="nomenclature")
     */
    private $archives;

    public function __construct()
    {
        $this->archives = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): self
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * @return Collection|Archive[]
     */
    public function getArchives(): Collection
    {
        return $this->archives;
    }

    public function addArchive(Archive $archive): self
    {
        if (!$this->archives->contains($archive)) {
            $this->archives[] = $archive;
            $archive->setNomenclature($this);
        }

        return $this;
    }

    public function removeArchive(Archive $archive): self
    {
        if ($this->archives->removeElement($archive)) {
            // set the owning side to null (unless already changed)
            if ($archive->getNomenclature() === $this) {
                $archive->setNomenclature(null);
            }
        }

        return $this;
    }
}
