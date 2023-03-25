<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parler
 *
 * @ORM\Table(name="parler", indexes={@ORM\Index(name="id_evemt", columns={"id_evemt"}), @ORM\Index(name="id_client", columns={"id_client"})})
 * @ORM\Entity
 */
class Parler
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_comment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComment;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=false)
     */
    private $commentaire;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     * })
     */
    private $idClient;

    /**
     * @var \Evemt
     *
     * @ORM\ManyToOne(targetEntity="Evemt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evemt", referencedColumnName="id")
     * })
     */
    private $idEvemt;


}
