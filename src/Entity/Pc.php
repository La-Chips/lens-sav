<?php

namespace App\Entity;

use App\Repository\PcRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pc
 *
 * @ORM\Table(name="pc")
 * @ORM\Entity(repositoryClass=PcRepository::class)
 */
class Pc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=false)
     */
    private $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=false)
     */
    private $modele;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Details", mappedBy="pc")
     */
    private $details;



    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Emplacement::class, inversedBy="pc")
     */
    private $emplacement;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku2;



    /**
     * @ORM\OneToMany(targetEntity=PcComposants::class, mappedBy="pc",cascade={"persist"})
     */
    private $pcComposants;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->details = new \Doctrine\Common\Collections\ArrayCollection();
        $this->composant = new ArrayCollection();
        $this->pcComposants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }





    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEmplacement()
    {
        if ($this->emplacement == null) {
            return ' ';
        }
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function substract($qte)
    {
        $this->Quantite = $this->Quantite - $qte;
    }

    public function getQteFab()
    {
        if (!$this->isBuildable()) {
            return 0;
        }

        $qte = null;
        foreach ($this->getComposants() as $key => $composant) {
            if ($composant->getQuantite() == 0) {
                $qte = 0;
                break;
            } else {
                if ($qte == null) {
                    $qte = $composant->getQuantite();
                } elseif ($qte > $composant->getQuantite()) {
                    $qte = $composant->getQuantite();
                }
            }
        }
        return $qte;
    }

    private function isBuildable()
    {
        if (count($this->getComposants()) == 0) {
            return false;
        }

        foreach ($this->getComposants() as $key => $value) {

            if ($value->getQuantite() == 0) {
                return false;
            }
        }
        return true;
    }

    public function getCompoLimitant()
    {
        $compoLim = null;
        $out = [];
        foreach ($this->getComposants() as $key => $value) {
            if ($compoLim == null) {
                $compoLim = $value;
                array_push($out, $value);
            } elseif ($compoLim->getQuantite() > $value->getQuantite()) {
                $out = [];
                $compoLim = $value;
                array_push($out, $value);
            } elseif ($compoLim->getQuantite() == $value->getQuantite()) {
                array_push($out, $value);
            }
        }
        return $out;
    }

    public function build()
    {
        foreach ($this->getComposants() as $key => $value) {
            $pcComposant = $this->getPcComposant($value);
            $value->substract($pcComposant->getQuantite());
        }
    }


    public function __toString()
    {
        return $this->modele;
    }

    public function getHT($tva)
    {
        return $this->prix * (1 - $tva / 100);
    }

    public function getSku2(): ?string
    {
        return $this->sku2;
    }

    public function setSku2(?string $sku2): self
    {
        $this->sku2 = $sku2;

        return $this;
    }



    /**
     * @return Collection|PcComposants[]
     */

    public function getPcComposants()
    {
        return $this->pcComposants;
    }

    public function setPcComposants($pcComposants)
    {
        $this->pcComposants = $pcComposants;
        return $this;
    }

    public function getPcComposant($composant)
    {
        foreach ($this->pcComposants as $key => $value) {
            if ($value->getComposant() == $composant) {
                return $value;
            }
        }
        return null;
    }

    public function getComposants(): Collection
    {
        $composants = new ArrayCollection();
        foreach ($this->pcComposants as $key => $value) {
            $composants->add($value->getComposant());
        }

        return $composants;
    }



    public function addComposant($composant): self
    {
        $pcComposant = new PcComposants();
        $pcComposant->setPc($this);
        $pcComposant->setComposant($composant);
        $pcComposant->setQuantite(1);

        if (!$this->haveCompo($composant)) {
            $this->pcComposants[] = $pcComposant;
            $pcComposant->setPc($this);
        } else {
            $this->getPcComposant($composant)->add(1);
        }

        return $this;
    }

    public function removeComposant($composant)
    {

        $pcCompo = $this->getPcComposant($composant);
        $this->removePcComposant($pcCompo);
    }

    public function removePcComposant(PcComposants $pcComposant): self
    {
        if ($this->pcComposants->removeElement($pcComposant)) {
            // set the owning side to null (unless already changed)
            if ($pcComposant->getPc() === $this) {
                $pcComposant->setPc(null);
            }
        }

        return $this;
    }



    public function haveCompo($composant)
    {
        if ($this->getComposants()->contains($composant))
            return true;
        return false;
    }
}