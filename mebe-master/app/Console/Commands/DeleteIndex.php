<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class DeleteIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:delete {numberOfUser=1}';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $numberOfUser = $this->argument('numberOfUser');
        if($this->confirm('Are you sure create user?')) {
            $this->deleteIndex($numberOfUser);
        }

    }

    protected function deleteIndex($index_name){
        try{
            $client = new Client(['base_uri' => 'http://localhost:9200/']);
            $response =  $client->request('DELETE',$index_name);
            echo ("status code: ".$response->getStatusCode());
        }
        catch (\Exception $exception){
            echo ($exception);
        }
    }
}
