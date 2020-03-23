<?php

require_once __DIR__.'/../../vendor/autoload.php';
use App\Entity\BugReport;
use App\Repository\BugReportRepository;
use App\Helpers\DbQueryBuilderFactory;
use App\Database\QueryBuilder;

if(isset($_POST, $_POST['add']))
{
    $reportType = $_POST['report_type'];
    $email      = $_POST['email'];
    $link       = $_POST['link'];
    $message    = $_POST['message'];

    $bugReport = new BugReport;
    $bugReport->setReportType($reportType);
    $bugReport->setEmail($email);
    $bugReport->setLink($link);
    $bugReport->setMessage($message);

    /** @var QueryBuilder $queryBuilder */
    $queryBuilder = DbQueryBuilderFactory::make();
    /** @var BugReportRepository $repository */
    $repository = new BugReportRepository($queryBuilder);
    $newReport = $repository->create($bugReport);
}