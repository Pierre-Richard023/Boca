<?php

namespace App\Entity;

use App\Repository\MenuSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuSectionRepository::class)]
class MenuSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'sections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menus $menu = null;

    /**
     * @var Collection<int, Dishes>
     */
    #[ORM\OneToMany(targetEntity: Dishes::class, mappedBy: 'section', orphanRemoval: true)]
    private Collection $dishes;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getMenu(): ?Menus
    {
        return $this->menu;
    }

    public function setMenu(?Menus $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return Collection<int, Dishes>
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDish(Dishes $dish): static
    {
        if (!$this->dishes->contains($dish)) {
            $this->dishes->add($dish);
            $dish->setSection($this);
        }

        return $this;
    }

    public function removeDish(Dishes $dish): static
    {
        if ($this->dishes->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getSection() === $this) {
                $dish->setSection(null);
            }
        }

        return $this;
    }
}
