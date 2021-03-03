<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image file on server FS.
 *
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={}
 * )
 * @ORM\Entity
 */
class File
{
    /**
     * @var int The id of file.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string The name of this file.
     *
     * @ORM\Column
     */
    public $name;

    /**
     * @var string The path of this file.
     *
     * @ORM\Column
     */
    public $path;

    public function getId(): ?int
    {
        return $this->id;
    }
}
