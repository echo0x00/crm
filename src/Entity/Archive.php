<?php
namespace App\Entity;

use App\Repository\ArchiveRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Repository\NomenclatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchiveRepository::class)
 *
 * #ApiResource(
 *     collectionOperations={"get" = {"archive_context" = {"groups" = "archive:list"}}, "post"},
 *     itemOperations={"get" = {"archive_context" = {"groups" = "archive:item"}}},
 *     order={"date_paper" = "DESC", "count" = "DESC"}
 * )]
 */

class Archive implements \JsonSerializable
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
     * #Groups({"archive:list", "archive:item"})
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

    public function getNomenclatureId(): ?int
    {
        return $this->nomenclature->getId();
    }

    public function getNomenclatureTitle(): ?string
    {
        return $this->nomenclature->getShortName();
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

    public function getDatePaperTimestamp()
    {
        return $this->date_paper->getTimestamp();
    }

    public function getDatePaper()
    {
        return $this->date_paper;
    }

    public function setDatePaper(\DateTimeInterface $date_paper): self
    {
        $this->date_paper = $date_paper;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): ?array
    {
        return [
            "id" => $this->getId(),
            "datePaper" => date("Y-m-d", $this->getDatePaperTimestamp()),
            "count" => $this->getCount(),
            "nomenclature_id" => $this->getNomenclatureId(),
            "nomenclature_title" => $this->getNomenclatureTitle()
        ];
    }
}
