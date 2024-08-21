<?php

namespace Notifications\Domain\Model\Notification;

class Notification
{
    private Title $title;
    private Description $description;
    private Action $url;
    private Icon $image;

    public function __construct(
        Title $title,
        Description $description,
        Action $url,
        Icon $image
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->image = $image;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function getAction(): Action
    {
        return $this->url;
    }

    public function getIcon(): Icon
    {
        return $this->image;
    }
}
