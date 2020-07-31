<?php
    namespace App\Console\Commands;
    use App\Repositories\Post\PostRepositoryInterface;
    use Illuminate\Console\Command;
    
    class AddIndex extends Command{
        protected $signature ="Add:Index";
        protected $description ="ReIndex Post";
        protected $_postRepository;
        public function __construct(PostRepositoryInterface $_postRepository)
        {
            parent::__construct();
            $this->_postRepository = $_postRepository;
        }
        public function handle(){
            $all_data = $this->_postRepository->getAllData();
            foreach ($all_data as $data){
                $contentSearch = strip_tags($data->content);
                $contentSearch = $data->title . " ". $contentSearch;
                $data['content_search'] = $contentSearch;
                $data->addToIndex();
            }
        }
    }