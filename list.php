<?php 
	require('MongoConfig.php');


	$page=isset($_GET['page'])?$_GET['page']:1;
	$limit=isset($_GET['limit'])?$_GET['limit']:10;

	$filter = ['qgchannel'=>['$in'=>[new \MongoDB\BSON\Regex('^15','i')]]];
	$skip=($page-1)*$limit;
	$options = [
	    'projection' => ['_id' => 1,'question'=>1,'answer'=>1,'status'=>1,'qgchannel'=>1,'province'=>1,'city'=>1,'addtime'=>1],
	    'sort' => ['addtime' => -1],
	    'skip'=>$skip,
	    'limit'=>$limit
	];

	//创建查询语句用的条件及字段过滤选项
	$query = new MongoDB\Driver\Query($filter,$options);
	//创建查询对象


	$command = new MongoDB\Driver\Command(['count' => 'needcheck','query'=>['qgchannel'=>['$in'=>[new \MongoDB\BSON\Regex('^15','i')]]]]);
  	$result = $manager->executeCommand('zskapi',$command);
  	$res = $result->toArray();
  	$cnt = 0;
  	if ($res) {
    
     	$cnt = $res[0]->n;
  	}

	$cursor = $manager->executeQuery('zskapi.needcheck', $query)->toArray();

	$info=[];
	$data=[];
	// 执行查询语句
	if(count($cursor)>0){

		foreach ($cursor as $key => $value) {
			 $doc = (array)$value;
			 $doc['id']=(string)$doc['_id'];
			 $info[]=$doc;
		}
		$data['code']=0;
		$data['msg']='success';
		$data['count']=$cnt;
		$data['data']=$info;
		echo json_encode($data);
	}else{
		$data['code']=1;
		$data['msg']='暂无数据';
		$data['count']=0;
		$data['data']=[];
		echo json_encode($data);
	}
	
 ?>