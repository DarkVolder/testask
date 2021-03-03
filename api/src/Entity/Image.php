<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\AddPictureController;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * An image.
 *
 * @ORM\Entity
 * @ApiResource(
 *     denormalizationContext={"groups"={"read", "write"}},
 *     itemOperations={
 *       "get"={},
 *       "post"={
 *         "method"="POST",
 *         "path"="/file",
 *         "access_control"="is_granted('ROLE_USER')",
 *         "controller"=AddPictureController::class,
 *         "defaults"={"_api_receive"=false},
 *         "denormalization_context"={"groups"={""}},
 *         "swagger_context"={
 *              "parameters"={
 *                  {"name"="file", "in"="formData", "type"="file"},
 *              },
 *           },
 *        },
 *     },
 *     attributes={
 *       "normalization_context"={"groups"={"GetFile"}},
 *       "denormalization_context"={"groups"={"SetFile"}},
 *       "pagination_items_per_page"=12,
 *     }
 * )
 * @ApiFilter(NumericFilter::class, properties={"category", "id"})
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 */
class Image
{
    public const CATEGORY_NEW = 1;
    public const CATEGORY_POPULAR = 2;

    /**
     * @var int The id of this image.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("GetFile")
     */
    private $id;

    /**
     * @var string The name of this image.
     * @Groups("GetFile")
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @var string The description of this image.
     * @Groups("GetFile")
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @var int The type of category of this image.
     * @Groups("GetFile")
     * @ORM\Column(type="integer")
     */
    public $category;

    /**
     * @var File
     * @ORM\OneToOne(targetEntity="App\Entity\File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="Pictures")
     */
    private $owner;

    public function __construct()
    {
        $this->file = new File();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
