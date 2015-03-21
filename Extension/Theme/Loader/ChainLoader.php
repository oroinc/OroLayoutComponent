<?php

namespace Oro\Component\Layout\Extension\Theme\Loader;

class ChainLoader implements LoaderInterface
{
    /** @var LoaderInterface[] */
    protected $loaders = [];

    /**
     * @param LoaderInterface[] $loaders
     */
    public function __construct(array $loaders = [])
    {
        array_walk($loaders, [$this, 'addLoader']);
    }

    /**
     * @param LoaderInterface $loader
     */
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FileResource $resource)
    {
        $result = false;
        foreach ($this->loaders as $loader) {
            if ($loader->supports($resource)) {
                $result = true;

                break;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function load(FileResource $resource)
    {
        $update = false;
        foreach ($this->loaders as $loader) {
            if ($loader->supports($resource)) {
                $update = $loader->load($resource);

                break;
            }
        }

        return $update;
    }
}
