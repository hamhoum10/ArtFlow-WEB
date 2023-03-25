<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock", indexes={@ORM\Index(name="commendeee_fk", columns={"id_commende"})})
 * @ORM\Entity
 */
class Stock
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
     * @ORM\Column(name="name_produit", type="string", length=255, nullable=false)
     */
    private $nameProduit;

    /**
     * @var string
     *
     * @ORM\Column(name="artiste", type="string", length=255, nullable=false)
     */
    private $artiste;

    /**
     * @var string
     *
     * @ORM\Column(name="addres", type="string", length=255, nullable=false)
     */
    private $addres;

    /**
     * @var int
     *
     * @ORM\Column(name="id_commende", type="integer", nullable=false)
     */
    private $idCommende;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255, nullable=false)
     */
    private $userName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_entr", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateEntr = 'CURRENT_TIMESTAMP';


}
