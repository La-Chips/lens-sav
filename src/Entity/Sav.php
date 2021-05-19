<?php

namespace App\Entity;

use App\Repository\SavRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SavRepository::class)
 */
class Sav
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="savs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="savs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Technicien;

    /**
     * @ORM\ManyToOne(targetEntity=Probleme::class, inversedBy="savs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $probleme;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $probleme_info;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temp_min;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temp_max;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="savs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resultat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $echange_client;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $echange_boutique;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infos_sup;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $diagnostic_date;

    /**
     * @ORM\Column(name="crystalDisk", type="string", length=255, nullable=false)
     */
    private $crystalDisk;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hdd_upTime;

    /**
     * @ORM\ManyToMany(targetEntity=Operations::class, inversedBy="savs")
     */
    private $operations;

    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getTechnicien(): ?User
    {
        return $this->Technicien;
    }

    public function setTechnicien(?User $Technicien): self
    {
        $this->Technicien = $Technicien;

        return $this;
    }

    public function getProbleme(): ?Probleme
    {
        return $this->probleme;
    }

    public function setProbleme(?Probleme $probleme): self
    {
        $this->probleme = $probleme;

        return $this;
    }

    public function getProblemeInfo(): ?string
    {
        return $this->probleme_info;
    }

    public function setProblemeInfo(?string $probleme_info): self
    {
        $this->probleme_info = $probleme_info;

        return $this;
    }

    public function getTempMin(): ?int
    {
        return $this->temp_min;
    }

    public function setTempMin(?int $temp_min): self
    {
        $this->temp_min = $temp_min;

        return $this;
    }

    public function getTempMax(): ?int
    {
        return $this->temp_max;
    }

    public function setTempMax(?int $temp_max): self
    {
        $this->temp_max = $temp_max;

        return $this;
    }

    public function getResultat(): ?Status
    {
        return $this->resultat;
    }

    public function setResultat(?Status $resultat): self
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getEchangeClient(): ?string
    {
        return $this->echange_client;
    }

    public function setEchangeClient(?string $echange_client): self
    {
        $this->echange_client = $echange_client;

        return $this;
    }

    public function getEchangeBoutique(): ?string
    {
        return $this->echange_boutique;
    }

    public function setEchangeBoutique(?string $echange_boutique): self
    {
        $this->echange_boutique = $echange_boutique;

        return $this;
    }

    public function getInfosSup(): ?string
    {
        return $this->infos_sup;
    }

    public function setInfosSup(?string $infos_sup): self
    {
        $this->infos_sup = $infos_sup;

        return $this;
    }

    public function getDiagnosticDate(): ?\DateTimeInterface
    {
        return $this->diagnostic_date;
    }

    public function setDiagnosticDate(
        ?\DateTimeInterface $diagnostic_date
    ): self {
        $this->diagnostic_date = $diagnostic_date;

        return $this;
    }

    public function getCrystalDisk(): ?string
    {
        return $this->crystalDisk;
    }

    public function setCrystalDisk(?string $crystalDisk): self
    {
        $this->crystalDisk = $crystalDisk;

        return $this;
    }

    public function getHddUpTime(): ?int
    {
        return $this->hdd_upTime;
    }

    public function setHddUpTime(?int $hdd_upTime): self
    {
        $this->hdd_upTime = $hdd_upTime;

        return $this;
    }

    /**
     * @return Collection|Operations[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operations $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
        }

        return $this;
    }

    public function removeOperation(Operations $operation): self
    {
        $this->operations->removeElement($operation);

        return $this;
    }
}