<?php
namespace App\Entity;

class BugReport extends Entity
{
    private $id;
    private $report_type;
    private $email;
    private $link;
    private $message;
    private $created_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'report_type' => $this->getReportType(),
            'email' => $this->getEmail(),
            'message' => $this->getMessage(),
            'link' => $this->getLink(),
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }


    //------------------------------------------- Getters and Setters ---------------------------------//

    public function getCreatedAt()
    {
        return $this->created_at;
    }


    /**
     * @return mixed
     */
    public function getReportType()
    {
        return $this->report_type;
    }


    /**
     * @param mixed $report_type
     * @return BugReport
     */
    public function setReportType(string $report_type)
    {
        $this->report_type = $report_type;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param mixed $email
     * @return BugReport
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }


    /**
     * @param mixed $link
     * @return BugReport
     */
    public function setLink(string $link)
    {
        $this->link = $link;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * @param mixed $message
     * @return BugReport
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }
}