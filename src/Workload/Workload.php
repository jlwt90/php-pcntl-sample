<?php

namespace Jlwt90\Workload;

/**
 * Workload is the work profile for a mini-batch.
 */
class Workload
{
    /**
     * @var string name of the workload.
     */
    public readonly string $name;

    /**
     * @var array collection of workload data.
     */
    public readonly array $data;

    /**
     * @param string $name
     * @param $data
     */
    public function __construct(string $name, $data)
    {
        $this->name = $name;
        $this->data = $data;
    }
}