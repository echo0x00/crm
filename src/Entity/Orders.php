<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
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
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $comment = "";

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_delivery;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */

    private $track_number = "";

    /**
     * @ORM\ManyToOne(targetEntity=Agents::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;

    /**
     * @ORM\Column(type="integer")
     */
    private $summ = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $sms_status = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $editor_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_edited;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $address = "";

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = 0;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="order", cascade={"persist"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public static function getNextNumber($em): int
    {
        $qb = $em->createQueryBuilder();
        $qb->select('count(odrs.id)');
        $qb->from(Orders::class,'odrs');

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count+1;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->date_order;
    }

    public function setDateOrder(\DateTimeInterface $date_order): self
    {
        $this->date_order = $date_order;

        return $this;
    }

    public function getDateDelivery(): ?\DateTimeInterface
    {
        return $this->date_delivery;
    }

    public function setDateDelivery(\DateTimeInterface $date_delivery): self
    {
        $this->date_delivery = $date_delivery;

        return $this;
    }

    public function getDateEdited(): ?\DateTimeInterface
    {
        return $this->date_edited;
    }

    public function setDateEdited(\DateTimeInterface $date_edited): self
    {
        $this->date_edited = $date_edited;

        return $this;
    }

    public function getTrackNumber(): ?string
    {
        return $this->track_number;
    }

    public function setTrackNumber(?string $track_number): self
    {
        if ($track_number == null) {
            $track_number = "";
        }
        $this->track_number = $track_number;

        return $this;
    }

    public function getAgent(): ?Agents
    {
        return $this->agent;
    }

    public function setAgent(?Agents $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getSumm(): ?int
    {
        return $this->summ;
    }

    public function setSumm(int $summ): self
    {
        $this->summ = $summ;

        return $this;
    }

    public function getSmsStatus(): ?int
    {
        return $this->sms_status;
    }

    public function setSmsStatus(?int $sms_status): self
    {
        if ($sms_status == null) {
            $sms_status = 0;
        }
        $this->sms_status = $sms_status;

        return $this;
    }

    public function getEditorId(): ?int
    {
        return ($this->editor_id) ? $this->editor_id : 0;
    }

    public function setEditorId(int $editor_id): self
    {
        $this->editor_id = $editor_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted = false): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrder($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrder() === $this) {
                $product->setOrder(null);
            }
        }

        return $this;
    }
}
