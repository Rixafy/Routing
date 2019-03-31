<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Doctrination\Annotation\Translatable;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog")
 */
class Blog extends EntityTranslator
{
    use UniqueTrait;
    use ActiveTrait;
    use DateTimeTrait;

    /**
     * @Translatable
     * @var string
     */
    protected $name;

    /**
     * @Translatable
     * @var string
     */
    protected $title;

    /**
     * @Translatable
     * @var string
     */
    protected $description;

    /**
     * @Translatable
     * @var string
     */
    protected $keywords;

    /**
     * One Blog has Many BlogCategories
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogCategory\BlogCategory", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogCategory[]
     */
    private $categories;

    /**
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPost\BlogPost", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    /**
     * One Blog has Many BlogTags
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogTag\BlogTag", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

    /**
     * One Blog has Many BlogPublishers
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPublisher\BlogPublisher", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogPublisher[]
     */
    private $publishers;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogTranslation[]
     */
    protected $translations;
}