<?php

require_once __DIR__.'/../../vendor/autoload.php';
use App\Entity\BugReport;
use App\Repository\BugReportRepository;
use App\Helpers\DbQueryBuilderFactory;
use App\Database\QueryBuilder;
use App\Logger\Logger;
use App\Exception\BadRequestException;
use App\Helpers\App;

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

    $logger = new Logger;

    try{
        $application = new App();
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = DbQueryBuilderFactory::make();
        /** @var BugReportRepository $repository */
        $repository = new BugReportRepository($queryBuilder);
        /** @var BugReport $newReport */
        $newReport = $repository->create($bugReport);
    }catch (Throwable $exception)
    {
        $logger->critical($exception->getMessage(), $_POST);
        throw new BadRequestException($exception->getMessage(), [$exception], 400);
    }

    $logger->info('new bug report created', ['id' => $newReport->getId(), 'type' => $newReport->getReportType()]);

    //$bugReports = $repository->findAll();
}