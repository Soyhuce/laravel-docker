<?php

namespace Soyhuce\Docker\DTO;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ImageItem extends DockerItem
{
    protected $exceptKeys = ['repoTags'];

    public string $id;

    public Carbon $created;

    public array $repoTags;

    public int $size;

    public string $imageName;

    public ?string $tagName;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        if (!$this->imageName) {
            $this->fetchImageAndTag();
        }
    }

    public static function fromResponse(array $data): self
    {
        return new self([
            'id' => $data['Id'],
            'created' => Carbon::createFromTimestamp($data['Created']),
            'repoTags' => $data['RepoTags'],
            'size' => $data['Size'],
        ]);
    }

    private function fetchImageAndTag(): void
    {
        [$imageName, $tagName] = Str::of(implode('', $this->repoTags))->explode(':');

        $this->imageName = $imageName;
        $this->tagName = $tagName;
    }
}
