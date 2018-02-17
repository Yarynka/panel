<?php

namespace Pterodactyl\Transformers\Api\Application;

use Pterodactyl\Models\Node;
use Pterodactyl\Services\Acl\Api\AdminAcl;

class NodeTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     *
     * @var array
     */
    protected $availableIncludes = ['allocations', 'location', 'servers'];

    /**
     * Return the resource name for the JSONAPI output.
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return Node::RESOURCE_NAME;
    }

    /**
     * Return a node transformed into a format that can be consumed by the
     * external administrative API.
     *
     * @param \Pterodactyl\Models\Node $node
     * @return array
     */
    public function transform(Node $node): array
    {
        $response = collect($node->toArray())->mapWithKeys(function ($value, $key) {
            // I messed up early in 2016 when I named this column as poorly
            // as I did. This is the tragic result of my mistakes.
            $key = ($key === 'daemonSFTP') ? 'daemonSftp' : $key;

            return [snake_case($key) => $value];
        })->toArray();

        $response[$node->getUpdatedAtColumn()] = $this->formatTimestamp($node->updated_at);
        $response[$node->getCreatedAtColumn()] = $this->formatTimestamp($node->created_at);

        return $response;
    }

    /**
     * Return the nodes associated with this location.
     *
     * @param \Pterodactyl\Models\Node $node
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\NullResource
     */
    public function includeAllocations(Node $node)
    {
        if (! $this->authorize(AdminAcl::RESOURCE_ALLOCATIONS)) {
            return $this->null();
        }

        $node->loadMissing('allocations');

        return $this->collection(
            $node->getRelation('allocations'), $this->makeTransformer(AllocationTransformer::class), 'allocation'
        );
    }

    /**
     * Return the nodes associated with this location.
     *
     * @param \Pterodactyl\Models\Node $node
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeLocation(Node $node)
    {
        if (! $this->authorize(AdminAcl::RESOURCE_LOCATIONS)) {
            return $this->null();
        }

        $node->loadMissing('location');

        return $this->item(
            $node->getRelation('location'), $this->makeTransformer(LocationTransformer::class), 'location'
        );
    }

    /**
     * Return the nodes associated with this location.
     *
     * @param \Pterodactyl\Models\Node $node
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\NullResource
     */
    public function includeServers(Node $node)
    {
        if (! $this->authorize(AdminAcl::RESOURCE_SERVERS)) {
            return $this->null();
        }

        $node->loadMissing('servers');

        return $this->collection(
            $node->getRelation('servers'), $this->makeTransformer(ServerTransformer::class), 'server'
        );
    }
}