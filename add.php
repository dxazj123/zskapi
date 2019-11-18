<?php 
	require('MongoConfig.php');
	$data=[];

	$question=$_POST['question'];
	$answer=$_POST['answer'];
	
	if(trim($question)==''){
		$data['code']=0;
		$data['msg']='标准问题不能为空';
		 echo json_encode($data);
		exit;
	}
	if(trim($answer)==''){
		$data['code']=0;
		$data['msg']='标准答案不能为空';
		 echo json_encode($data);
		exit;
	}
	$qgchannel=isset($_POST['qgchannel'])?$_POST['qgchannel']:[];
	$province=isset($_POST['province'])?$_POST['province']:0;
	$city=isset($_POST['city'])?$_POST['city']:[];
	$status=3;
	$flag=0;
	$addtime=time();

	$filter = ['question'=>'1'];

	// 总记录数
	$command = new MongoDB\Driver\Command(['count' => 'needcheck','query'=>$filter]);
  	$result = $manager->executeCommand('zskapi',$command);
  	$res = $result->toArray();
  	
  	if($res){
  		if($res[0]->n>0){
  			$data['code']=0;
  			$data['msg']='标准答案已存在';
  		 	echo json_encode($data);
  			exit;
  		}
  	}

	$bulk->insert(['question' =>$question,'answer'=>$answer,'qgchannel'=>$qgchannel,'province'=>$province,'city'=>$city,'status'=>$status,'flag'=>$flag,'addtime'=>$addtime]);
	$result = $manager->executeBulkWrite('zskapi.needcheck', $bulk);

	$affectLineSum = $result->getInsertedCount();
	
	if($affectLineSum>0){
	    $data['code']=1;
	    $data['msg']='添加成功';
	    echo json_encode($data);
	} else {
	   	$data['code']=0;
	   	$data['msg']='添加失败';
	   	echo json_encode($data);
	}
 ?>