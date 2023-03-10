<?php

namespace App\Entity;

use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use DateTime;


#[ORM\Entity(repositoryClass: CartProductRepository::class)]
#[ORM\Table(name: 'cart_product')]
class CartProduct
{

    #[ORM\Id]
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user', referencedColumnName: 'uid')]
    protected User $user;

    #[ORM\Id]
    #[ManyToOne(targetEntity: Chapter::class)]
    #[JoinColumn(name: 'chapter', referencedColumnName: 'chapterId')]
    protected Chapter $chapter;


    #[ORM\Column(type: 'integer')]
    protected int $quantite;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $cartTime;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Chapter
     */
    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    /**
     * @param Chapter $chapter
     */
    public function setChapter(Chapter $chapter): void
    {
        $this->chapter = $chapter;
    }

    /**
     * @return int
     */
    public function getQuantite(): int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return DateTime
     */
    public function getCartTime(): DateTime
    {
        return $this->cartTime;
    }

    /**
     * @param DateTime $cartTime
     */
    public function setCartTime(DateTime $cartTime): void
    {
        $this->cartTime = $cartTime;
    }


}