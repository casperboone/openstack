<?php declare(strict_types=1);

namespace OpenStack\Networking\v2\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;
use OpenStack\Networking\v2\Api;

/**
 * Represents a Neutron v2 LoadBalancer pool
 *
 * @property Api $api
 */
class LoadBalancerPool extends OperatorResource implements Creatable, Retrievable, Updateable, Deletable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var string
     */
    public $protocol;

    /**
     * @var string
     */
    public $lbAlgorithm;

    /**
     * @var array
     */
    public $sessionPersistence;

    /**
     * @var boolean
     */
    public $adminStateUp;

    /**
     * @var LoadBalancerListener[]
     */
    public $listeners;

    /**
     * @var LoadBalancerMember[]
     */
    public $members;

    /**
     * @var LoadBalancerHealthMonitor[]
     */
    public $healthmonitors;

    /**
     * @var string
     */
    public $healthmonitorId;

    /**
     * @var string
     */
    public $operatingStatus;

    /**
     * @var string
     */
    public $provisioningStatus;

    protected $resourcesKey = 'pools';
    protected $resourceKey = 'pool';

    protected $aliases = [
        'tenant_id'           => 'tenantId',
        'admin_state_up'      => 'adminStateUp',
        'lb_algorithm'        => 'lbAlgorithm',
        'session_persistence' => 'sessionPersistence',
        'healthmonitor_id'    => 'healthmonitorId',
        'loadbalancer_id'     => 'loadbalancerId',
        'operating_status'    => 'operatingStatus',
        'provisioning_status' => 'provisioningStatus'
    ];

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postLoadBalancerPool(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->execute($this->api->getLoadBalancerPool(), ['id' => (string)$this->id]);
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putLoadBalancerPool());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteLoadBalancerPool());
    }

    /**
     * Add a member to this pool
     *
     * @param array $userOptions
     */
    public function addMember(array $userOptions = []): LoadBalancerMember
    {
        $userOptions = array_merge(['poolId' => $this->id], $userOptions);
        return $this->model(LoadBalancerMember::class)->create($userOptions);
    }

    /**
     * Get an instance of a member
     *
     * @param string $memberId
     * @return LoadBalancerMember
     */
    public function getMember(string $memberId): LoadBalancerMember
    {
        return $this->model(LoadBalancerMember::class, ['poolId' => $this->id, 'id' => $memberId]);
    }

    /**
     * Delete a member
     *
     * @param string $memberId
     */
    public function deleteMember(string $memberId)
    {
        $this->model(LoadBalancerMember::class, ['poolId' => $this->id, 'id' => $memberId])->delete();
    }

    /**
     * Add a healthmonitor to this load balancer pool
     *
     * @param array $userOptions
     * @return LoadBalancerHealthMonitor
     */
    public function addHealthMonitor(array $userOptions = []): LoadBalancerHealthMonitor
    {
        $userOptions = array_merge(['poolId' => $this->id], $userOptions);
        return $this->model(LoadBalancerHealthMonitor::class)->create($userOptions);
    }
}
