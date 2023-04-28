<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promocode
 *
 * @ORM\Table(name="promocode", uniqueConstraints={@ORM\UniqueConstraint(name="code", columns={"code"})})
 * @ORM\Entity
 */
class Promocode
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
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;


}
