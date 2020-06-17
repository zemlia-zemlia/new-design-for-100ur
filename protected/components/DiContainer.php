<?php

use App\components\serviceProviders\LogServiceProvider;
use App\repositories\AnswerRepository;
use App\repositories\QuestionRepository;
use App\repositories\UserRepository;
use App\services\AnswerService;
use App\services\CommentService;
use App\services\LeadService;
use App\services\QuestionRSSFeedService;
use App\services\QuestionService;
use League\Container\Container;

class DiContainer extends CComponent
{
    public $container;

    public function init()
    {
        $this->container = new Container();

        // load service providers
        $this->container->addServiceProvider(new LogServiceProvider());

        // bind classes
        $this->container->add(CommentService::class);
        $this->container->add(LeadService::class);
        $this->container->add(QuestionRSSFeedService::class);

        $this->container->add(AnswerService::class)
            ->addArgument(AnswerRepository::class)
            ->addArgument(UserRepository::class);

        $this->container->add(QuestionService::class)
            ->addArgument(QuestionRepository::class)
            ->addArgument(LeadService::class);

        $this->container->add(AnswerRepository::class);
        $this->container->add(UserRepository::class);
        $this->container->add(QuestionRepository::class);
    }
}