<?php

namespace App\Entity;

use App\Repository\MenusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenusRepository::class)]
class Menus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    /**
     * @var Collection<int, MenuSection>
     */
    #[ORM\OneToMany(targetEntity: MenuSection::class, mappedBy: 'menu', orphanRemoval: true)]
    private Collection $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection<int, MenuSection>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(MenuSection $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setMenu($this);
        }

        return $this;
    }

    public function removeSection(MenuSection $section): static
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getMenu() === $this) {
                $section->setMenu(null);
            }
        }

        return $this;
    }
}
