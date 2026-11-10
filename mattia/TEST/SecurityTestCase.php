<?php

/**
 * Semplice micro-framework per test di sicurezza (Zero dependencies)
 * Eseguibile sia da CLI che via Browser.
 */
class SecurityTestCase
{
    protected array $results = [];
    protected int $passed = 0;
    protected int $failed = 0;

    protected function assert(bool $condition, string $testName, string $details = ''): void
    {
        if ($condition) {
            $this->passed++;
            $this->results[] = [
                'status'  => 'PASSED',
                'name'    => $testName,
                'details' => $details
            ];
        } else {
            $this->failed++;
            $this->results[] = [
                'status'  => 'FAILED',
                'name'    => $testName,
                'details' => $details
            ];
        }
    }

    protected function assertEquals($expected, $actual, string $testName, string $details = ''): void
    {
        $this->assert($expected === $actual, $testName, $details . " [Atteso: " . var_export($expected, true) . ", Ricevuto: " . var_export($actual, true) . "]");
    }

    protected function assertNotContains(string $needle, string $haystack, string $testName, string $details = ''): void
    {
        $found = (strpos($haystack, $needle) !== false);
        $this->assert(!$found, $testName, $details . " [Trovata stringa non permessa: " . htmlspecialchars($needle) . "]");
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getPassedCount(): int
    {
        return $this->passed;
    }

    public function getFailedCount(): int
    {
        return $this->failed;
    }
}

