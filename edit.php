<?php 
	require('MongoConfig.php');

	$id=$_GET['id'];
	

	$id=new MongoDB\BSON\ObjectId($id);

	$filter = ['_id' =>$id];
	$options = [
	    'projection' => ['_id' => 1,'question'=>1,'answer'=>1,'status'=>1,'qgchannel'=>1,'province'=>1,'city'=>1,'addtime'=>1],
	    'sort' => ['addtime' => -1],
	];
	//创建查询语句用的条件及字段过滤选项
	$query = new MongoDB\Driver\Query($filter,$options);
	//创建查询对象
	$cursor = $manager->executeQuery('zskapi.needcheck', $query)->toArray();
	// 执行查询语句
	$info=[];
	foreach ($cursor as $key => $value) {
		$doc = (array)$value;
		$doc['_id']=(string)$doc['_id'];
		$info=$doc;
	}
	echo  json_encode($info);
 ?>
 