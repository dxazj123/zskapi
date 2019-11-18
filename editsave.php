<?php 
	require('MongoConfig.php');

	$data=[];

	$id=$_POST['id'];

	$question=$_POST['question'];

	$answer=$_POST['answer'];

	$status=isset($_POST['status'])?$_POST['status']:3;

	$qgchannel=isset($_POST['qgchannel'])?$_POST['qgchannel']:[];

	$province=isset($_POST['province'])?$_POST['province']:0;

	$city=isset($_POST['city'])?$_POST['city']:[];

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

	$id=new MongoDB\BSON\ObjectId($id);

	$bulk->update(['_id'=>$id],['$set'=>['question'=>$question,'answer'=>$answer,'status'=>$status,'qgchannel'=>$qgchannel,'province'=>$province,'city'=>$city]], ['multi' => true, 'upsert' =>false]);
	//创建更新语句
	$result=$manager->executeBulkWrite('zskapi.needcheck', $bulk, $writeConcern);
	$affectLineSum=$result->getModifiedCount();
	
    if($affectLineSum){
    	$data['code']=1;
    	$data['msg']='修改成功';
    	echo json_encode($data);
    }else{
    	$data['code']=0;
    	$data['msg']='修改失败';
    	echo json_encode($data);
    }
    exit;
 ?>