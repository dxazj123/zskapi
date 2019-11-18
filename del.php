<?php 
	require('MongoConfig.php');

	$id=isset($_POST['id'])?$_POST['id']:0;
	$ids=isset($_POST['ids'])?$_POST['ids']:0;
	if($id){
		$id=new MongoDB\BSON\ObjectId($id);
		$bulk->delete(['_id'=>$id],['limit'=>1]);
		$result = $manager->executeBulkWrite('zskapi.needcheck', $bulk, $writeConcern);
	    $affectLineSum = $result->getDeletedCount();
	    $data=[];
	    if($affectLineSum){
	    	$data['code']=1;
	    	$data['msg']='删除成功';
	    	echo json_encode($data);
	    }else{
	    	$data['code']=0;
	    	$data['msg']='删除失败';
	    	echo json_encode($data);
	    }
	    exit;
	}
	if($ids){
		$idArr=[];
		foreach ($ids as $v) {
			$idArr[]=new MongoDB\BSON\ObjectId($v);
		}
		
		$bulk->delete(['_id'=>['$in'=>$idArr]]);
		$result = $manager->executeBulkWrite('zskapi.needcheck', $bulk, $writeConcern);
	    $affectLineSum = $result->getDeletedCount();
		if($affectLineSum){
			$data['code']=1;
			$data['msg']='删除成功';
			echo json_encode($data);
		}else{
	    	$data['code']=0;
	    	$data['msg']='删除失败';
	    	echo json_encode($data);
	    }
		exit;
	}
 ?>