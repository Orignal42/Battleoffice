<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Entity\AddressBillingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
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
    private $payment_method;

 

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $id_api_response;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity=AddressBilling::class, cascade={"persist", "remove"})
     */
    private $Address;





    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }


    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdApiResponse(): ?string
    {
        return $this->id_api_response;
    }

    public function setIdApiResponse(string $id_api_response): self
    {
        $this->id_api_response = $id_api_response;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAddress(): ?AddressBilling
    {
        return $this->Address;
    }

    public function setAddress(?AddressBilling $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

   
}
