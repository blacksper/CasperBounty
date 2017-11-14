<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 13.11.2017
 * Time: 14:30
 */

namespace CasperBounty\ResultsBundle\Service;


use CasperBounty\TasksBundle\Entity\Tasks;

class ResultsService
{
    public function parseNmap(Tasks $task,$result){
        $cmd=$task->getProfileid()->getCmd();
        if(strstr('[FILENAME]',$cmd)){
            $filename=$result['filename'];
            echo $filename;
        }else{

        }


    }

}