<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Urls::class, mappedBy="category")
     */
    private $url;

    public function __construct()
    {
        $this->url = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Urls[]
     */
    public function getUrl(): Collection
    {
        return $this->url;
    }

    public function addUrl(Urls $url): self
    {
        if (!$this->url->contains($url)) {
            $this->url[] = $url;
            $url->setCategory($this);
        }

        return $this;
    }

    public function removeUrl(Urls $url): self
    {
        if ($this->url->removeElement($url)) {
            // set the owning side to null (unless already changed)
            if ($url->getCategory() === $this) {
                $url->setCategory(null);
            }
        }

        return $this;
    }
}
