<?php

namespace App\Helper;

use Carbon\Carbon;
use Firebase\JWT\JWT;

/**
 * Class Token represents jwt token structure
 *
 * @package App\Helper
 */
class Token
{
    /**
     * Contains issuedAt jwt value
     *
     * @var int $issuedAt
     */
    protected int $issuedAt;

    /**
     * Returns issuedAt jwt value
     *
     * @return int
     */
    public function getIssuedAt(): int
    {
        return $this->issuedAt;
    }

    /**
     * Sets issuedAt jwt value
     *
     * @param int $issuedAt
     */
    public function setIssuedAt(int $issuedAt): void
    {
        $this->issuedAt = $issuedAt;
    }

    /**
     * Contains notBefore jwt value
     *
     * @var int $notBefore
     */
    protected int $notBefore;

    /**
     * Returns notBefore jwt value
     *
     * @return int
     */
    public function getNotBefore(): int
    {
        return $this->notBefore;
    }

    /**
     * Sets notBefore jwt value
     *
     * @param int $notBefore
     */
    public function setNotBefore(int $notBefore): void
    {
        $this->notBefore = $notBefore;
    }

    /**
     * Contains expire jwt value
     *
     * @var int $expire
     */
    protected int $expire;

    /**
     * Returns expire jwt value
     *
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * Sets expire jwt value
     *
     * @param int $expire
     */
    public function setExpire(int $expire): void
    {
        $this->expire = $expire;
    }

    /**
     * Contains account id
     *
     * @var int $accountId
     */
    protected int $accountId;

    /**
     * Returns account id
     *
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * Sets account id
     *
     * @param int $accountId
     */
    public function setAccountId(int $accountId): void
    {
        $this->accountId = $accountId;
    }

    /**
     * Contains application id
     *
     * @var int $applicationId
     */
    protected int $applicationId;

    /**
     * Returns application id
     *
     * @return int
     */
    public function getApplicationId(): int
    {
        return $this->applicationId;
    }

    /**
     * Sets application id
     *
     * @param int $applicationId
     */
    public function setApplicationId(int $applicationId): void
    {
        $this->applicationId = $applicationId;
    }
}