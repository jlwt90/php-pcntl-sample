<?php

namespace Jlwt90\Workload;

/**
 * NumberGenerator generates workload with numbers from $start to $end (inclusive).
 */
class NumberGenerator implements Generator
{
    private string $jobPrefix = 'mini';
    private readonly int $start;

    private readonly int $end;

    /**
     * @param int $start
     * @param int $end
     */
    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @inheritDoc
     */
    public function generateWorkload(int $n): array
    {
        $res = [];
        if ($n <= 0) {
            return $res;
        }

        for ($i = 0; $i < $n; $i++) {
            $res["{$this->jobPrefix}" . $i] = [];
        }

        $idx = 0;
        for ($i = $this->start; $i <= $this->end; $i++) {
            $k = "{$this->jobPrefix}" . ($idx % $n);
            $res[$k][] = $i;
            $idx++;
        }

        // convert to Workload
        return array_map(fn($k, $v) => new Workload($k, $v), array_keys($res), array_values($res));
    }

    /**
     * @param string $jobPrefix
     */
    public function setJobPrefix(string $jobPrefix): void
    {
        $this->jobPrefix = $jobPrefix;
    }
}