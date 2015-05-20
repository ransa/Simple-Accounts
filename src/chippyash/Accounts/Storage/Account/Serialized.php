<?php
/**
 * Accounts
 
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Accounts\Storage\Account;

use chippyash\Accounts\AccountsException;
use chippyash\Accounts\AccountStorageInterface;
use chippyash\Accounts\Chart;
use chippyash\Type\String\StringType;

/**
 * Serialized PHP Account storage method
 */
class Serialized implements AccountStorageInterface
{
    /**
     * @var StringType
     */
    protected $baseDir;

    /**
     * @param StringType $baseDir Path to storage files
     *
     * @throws AccountsException
     */
    public function __construct(StringType $baseDir)
    {
        if (!file_exists($baseDir())) {
            throw new AccountsException("Invalid directory: {$baseDir}");
        }
        $this->baseDir = $baseDir;
    }

    /**
     * Fetch a chart from storage
     *
     * @param StringType $name
     * @return Chart
     */
    public function fetch(StringType $name)
    {
        return unserialize(file_get_contents($this->normalizeName($name)));
    }

    /**
     * Send a chart to storage
     *
     * @param Chart $chart
     * @return bool
     */
    public function send(Chart $chart)
    {
        return (file_put_contents($this->normalizeName($chart->getName()), serialize($chart)) > 0);
    }

    /**
     * Normalize name for filing
     *
     * @param StringType $name
     * @return string
     */
    protected function normalizeName(StringType $name)
    {
        return $this->baseDir . '/' . strtolower(str_replace(' ', '_', $name())) . '.saccount';
    }
}