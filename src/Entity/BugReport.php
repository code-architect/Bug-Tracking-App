<?php declare( strict_types=1 );


namespace App\Entity;


class BugReport extends Entity
{
    private $id;
    private $report_type;
    private $email;
    private $link;
    private $message;
    private $created_at;

    public function getId (): int
    {
        return (int) $this->id;
    }

    public function setReportType(string $reportType)
    {
        $this->report_type = $reportType;
        return $this;
    }

    public function getReportType(): string
    {
        return $this->report_type;
    }

    /**
     * @return string
     */
    public function getEmail (): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return BugReport
     */
    public function setEmail (string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink (): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return BugReport
     */
    public function setLink (?string $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage (): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return BugReport
     */
    public function setMessage (string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function toArray (): array
    {
        return [
            'report_type' => $this->getReportType(),
            'email' => $this->getEmail(),
            'message' => $this->getMessage(),
            'link' => $this->getLink(),
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
}