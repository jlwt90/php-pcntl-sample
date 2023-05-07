<?php

namespace Jlwt90\Workload;

/**
 * Generator generates workload for batch demo.
 */
interface Generator
{
    /**
     * Generate workload and return it
     * @param int $n number of mini-batches
     * @return Workload[] list of mini-batch workload
     */
    public function generateWorkload(int $n): array;
}