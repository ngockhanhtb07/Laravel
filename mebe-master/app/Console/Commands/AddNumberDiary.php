<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class AddNumberDiary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'User:Diary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command delete index elastic search';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $_postRepository;
    protected $_userRepository;
    public function __construct(PostRepositoryInterface $postRepository, UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->_postRepository= $postRepository;
        $this->_userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->_userRepository->getListUser();
        foreach ($users as $user) {
            $number_diary = $this->_postRepository->getNumberDiary($user->user_id);
            $this->_userRepository->setDirayNumber($user->external_id,$number_diary);
        }

    }
}
