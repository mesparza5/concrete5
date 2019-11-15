<?php
namespace Concrete\Core\Entity\Summary;

use Concrete\Core\Html\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="SummaryCategories"
 * )
 */
class Category
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $name = '';
    
    /**
     * @ORM\Column(type="string")
     */
    protected $handle;

    /**
     * @ORM\ManyToMany(targetEntity="Template", mappedBy="categories")
     */
    protected $templates;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param mixed $handle
     */
    public function setHandle($handle): void
    {
        $this->handle = $handle;
    }

    /**
     * @return mixed
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param mixed $templates
     */
    public function setTemplates($templates): void
    {
        $this->templates = $templates;
    }

    public function export(\SimpleXMLElement $node)
    {
        $container = $node->addChild('category');
        $container->addAttribute('handle', $this->getHandle());
        $container->addAttribute('name', h($this->getName()));
        $container->addAttribute('package', '');
    }





}