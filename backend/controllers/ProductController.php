<?php

namespace backend\controllers;

use common\models\base\activity\VirtualProduct;
use Yii;
use yii\filters\AccessControl;
use common\models\base\fund\product;
use common\models\base\fund\productSearch;
use common\models\base\fund\Order;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\fund\Thirdproduct;
use common\models\fund\FundProductThirdproduct;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * ProductController implements the CRUD actions for product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
        ];
    }

    /**
     * Lists all product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new productSearch();
        $dataProvider = $searchModel->search(\App::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);
        $model->rate = ($model->rate*100).'%';
    	$model->start_at = date('Y-m-d H:i:s',$model->start_at);
    	$model->end_at = date('Y-m-d H:i:s',$model->end_at);
    	$model->create_at = date('Y-m-d H:i:s',$model->create_at);
    	$model->update_at == 0 ? 0 : date('Y-m-d H:i:s',$model->update_at);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionList($id){
        $data = Order::find()
            ->andWhere(['product_id'=>$id]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('list',[
            'model' => $model,
            'pages' => $pages,
        ]);

    }

    public function actionThirdlist($id){
        $thirdModel =[];
        $fundtp = FundProductThirdproduct::find()->where(['product_id' => $id])->asArray()->all();
        foreach($fundtp as $v){
            array_push($thirdModel,Thirdproduct::find()->where(['id' => $v['thirdproduct_id']])->asArray()->one());
        }
        return $this->render('thirdlist',[
            'list' => $thirdModel,
        ]);
    }
    public function actionLock($id){
        $model = $this->findModel($id);
        $model = FundProductThirdproduct::find()->where(['product_id' => $id])->all();
        foreach ($model as $V)
        {
            $thirdproduct_id = $V->thirdproduct_id;
            $thirdModel = Thirdproduct::find()->where(['id' => $thirdproduct_id])->one();
            $thirdModel->process_status = 0;
            $thirdModel->save(false);
        }
        Product::updateAll(['status' => Product::STATUS_LOCK], ['id' => $id]);
        return $this->redirect(Url::to(['index']));

    }

    public function actionUnlock($id){
        $model = $this->findModel($id);
        $model = FundProductThirdproduct::find()->where(['product_id' => $id])->all();
        foreach ($model as $V)
        {
            $thirdproduct_id = $V->thirdproduct_id;
            $thirdModel = Thirdproduct::find()->where(['id' => $thirdproduct_id])->one();
            $thirdModel->process_status = 1;
            $thirdModel->save(false);
        }
        Product::updateAll(['status' => Product::STATUS_UNLOCK], ['id' => $id]);
        return $this->redirect(Url::to(['index']));
    }

    /**
     * Creates a new product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate()
    {
        $model = new product();
        $products=$this->getThirProducts();
        	if ($model->load(\App::$app->request->post()))
        	{
        		//判断是否为债权项目
        		$amount_empty = empty(\App::$app->request->post()['Product']['amount']);
        		$products_arr = \App::$app->request->post()['Product']['temp_products'];
        		
        		$model->start_at = strtotime(\App::$app->request->post()["Product"]['start_at']);
        		$end = \App::$app->request->post()["Product"]['end_at']?strtotime(Yii::$app->request->post()["Product"]['end_at']):0;
        		$model->end_at = $end;
        		$model->create_at = strtotime("now");
        		$model->type = \App::$app->request->post()['Product']['type'];

                $model->virtual_amonnt = \App::$app->request->post()['Product']['virtual_amonnt'];
                $model->virtual_invest_people = \App::$app->request->post()['Product']['virtual_invest_people'];

                $model->each_min = \App::$app->request->post()['Product']['each_min'];
                $model->each_max = \App::$app->request->post()['Product']['each_max'];
                $model->rate = \App::$app->request->post()['Product']['rate']*0.01;
                $model->intro = \App::$app->request->post()['Product']['intro'];
                $model->title = \App::$app->request->post()['Product']['title'];
                $model->ocreditor = \App::$app->request->post()['Product']['ocreditor'];
        		$model->create_user_id = \App::$app->user->id;
        		if($amount_empty)
        		{
                    echo "456";
	        		$total = 0;
	        		foreach ($products_arr as $K => $V)
	        		{
	        			$thirModel = Thirdproduct::findOne(['id' => $V]);
	        			$total += $thirModel->amount - $thirModel->invest_sum;
	        		}
	        		
	        		$model->amount = $total;
        		} else {
                   // $model->title = \App::$app->request->post()['Product']['amount'];
        			$model->amount = \App::$app->request->post()['Product']['amount'];
        		}

	        	$model->contract = UploadedFile::getInstance($model, 'contract');
	
	            if ($model->contract) 
	            {
	            	$contractName = mt_rand(1100,9900) .time() .'.'. $model->contract->extension;
	            	$model->contract->saveAs('../web/upload/'.$contractName);
	            	$model->contract = $contractName;
	            }

        		if($model->save())
        		{
                    //做虚拟显示的人数
                    //虚拟增加金额
                    $virtual_amonnt = $model->virtual_amonnt;
                    //虚拟增加人数
                    $virtual_invest_people = $model->virtual_invest_people;
                    //获取标的的编号
                    $pid = $model->id;
                    if(!empty($virtual_invest_people) && !empty($virtual_amonnt)){
                        $array_div = $this->randnum($virtual_amonnt,$virtual_invest_people);
                        foreach($array_div as $key => $value){
                            $virtual = new VirtualProduct();
                            $virtual->money = $value;
                            $virtual->name = '*'.$this->getname();
                            $virtual->phone = $this->getphone();
                            $virtual->pid = $pid;
                            if(!$virtual->save()){
                                return $virtual_invest_people.'--'.$virtual_amonnt.'--'.$array_div;
                            }
                        }
                    }

        			//把第三方项目存入中间表
	        		if($amount_empty)
	        		{
	        			foreach ($products_arr as $K => $V)
	        			{
	        				$newModel=new FundProductThirdproduct();
	        				$newModel->thirdproduct_id=$V;
	        				$newModel->product_id=$model->id;
	        				$newModel->save();
	        				$thirModel = Thirdproduct::findOne(['id' => $V]);
	        				$thirModel->process_status=1;
	        				$thirModel->save(false);
	        			}
	        		}else{
                        $thirdproduct = new Thirdproduct();
                        $thirdproduct->title = \App::$app->request->post()['Product']['title'];
                        $thirdproduct->intro = \App::$app->request->post()['Product']['intro'];
                        $thirdproduct->source = '本站';
                        $thirdproduct->creditor = \App::$app->request->post()['Product']['ocreditor'];
                        $thirdproduct->contract = $model->contract;
                        $thirdproduct->realname = $model->contract;
                        $thirdproduct->remarks = \App::$app->request->post()['Product']['intro'];
                        $thirdproduct->amount = $model->amount;
                        $thirdproduct->ocmoney = $model->amount;
                        $thirdproduct->start_at = $model->start_at;
                        $thirdproduct->end_at = $model->end_at;
                        $thirdproduct->status = Thirdproduct::STATUS_ACTIVE;
                        $thirdproduct->create_user_id =\App::$app->user->id;
                        $thirdproduct->check_user_id = \App::$app->user->id;
                        $thirdproduct->rate = \App::$app->request->post()['Product']['rate']*0.01;
                        $thirdproduct->process_status= 1;
                        $thirdproduct->intent = Thirdproduct::INTENT_CHECK;
                        $thirdproduct->maxcreditor = \App::$app->request->post()['Product']['maxcreditor'];
                        $thirdproduct->save();
                        $thirdmid = new FundProductThirdproduct();
                        $thirdmid->thirdproduct_id = $thirdproduct->id;
                        $thirdmid->product_id=$model->id;
                        $thirdmid->save();


                    }
        			return $this->redirect(['view', 'id' => $model->id]);
        		}
        	} else {
        		return $this->render('create', [
        				'model' => $model,
        				'products' => $products,
        		]);
        	}
        
    }

    /**
     * Updates an existing product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $thirdfundModel = [];
        $newthidModel = [];

        $modelmid = FundProductThirdproduct::find()->where(['product_id' => $id])->asArray()->all();
        if($modelmid){
            foreach ($modelmid as $v)
            {
                array_push($thirdfundModel,Thirdproduct::find()->where(['id' => $v['thirdproduct_id']])->asArray()->one());
            }

            $thmodels = Thirdproduct::find()
                ->andWhere(['intent'=>Thirdproduct::INTENT_CHECK])
                ->andWhere(['status'=>Thirdproduct::STATUS_ACTIVE])
                ->andWhere(['process_status' => Thirdproduct::PROCESS_STATUS_INACTIVE])
                ->asArray()
                ->all();
            $newthidModel = array_merge($thirdfundModel,$thmodels);
        }
        $arraynew =[];
        foreach($newthidModel as $n){
            $arraynew[$n['id']]['id'] = $n['id'];
            $arraynew[$n['id']]['title'] = $n['title'];
        }

        $model = new product();
        if ($model->load(Yii::$app->request->post()))
        	{

                if(\App::$app->request->post()['Product']['type'] == Product::TYPE_THIRD ){

                    foreach ($modelmid as $V)
                    {

                        $cthirdModel = Thirdproduct::find()->where(['id' => $V['thirdproduct_id']])->one();

                        $cthirdModel->process_status = 0;
                        $cthirdModel->save(false);
                    }

                    FundProductThirdproduct::deleteAll(['product_id' => $id]);
                }


                $products_arr = \App::$app->request->post()['Product']['temp_products'];

                $model->start_at = strtotime(\App::$app->request->post()["Product"]['start_at']);
                $end = \App::$app->request->post()["Product"]['end_at']?strtotime(Yii::$app->request->post()["Product"]['end_at']):0;
                $model->end_at = $end;
                $model->create_at = strtotime("now");
                $model->type = \App::$app->request->post()['Product']['type'];
                $model->each_min = \App::$app->request->post()['Product']['each_min'];
                $model->each_max = \App::$app->request->post()['Product']['each_max'];
                $model->rate = \App::$app->request->post()['Product']['rate']*0.01;
                $model->intro = \App::$app->request->post()['Product']['intro'];
                $model->title = \App::$app->request->post()['Product']['title'];
                $model->create_user_id = \App::$app->user->id;
                if(\App::$app->request->post()['Product']['type'] == Product::TYPE_THIRD)
                {

                    $total = 0;
                    foreach ($products_arr as $K => $V)
                    {
                        $thirModel = Thirdproduct::findOne(['id' => $V]);
                        $total += $thirModel->amount - $thirModel->invest_sum;
                    }

                    $model->amount = $total;
                } else {

                    // $model->title = \App::$app->request->post()['Product']['amount'];
                    $model->amount = \App::$app->request->post()['Product']['amount'];
                }

                $model->contract = UploadedFile::getInstance($model, 'contract');

                if ($model->contract)
                {
                    $contractName = mt_rand(1100,9900) .time() .'.'. $model->contract->extension;
                    $model->contract->saveAs('../web/upload/'.$contractName);
                    $model->contract = $contractName;
                }
                if(!$model->contract){
                    $new = $this->findModel($id);
                    $model->contract = $new->contract;
                }


                if($model->save())
                {
                    //把第三方项目存入中间表
                    if(\App::$app->request->post()['Product']['type'] == Product::TYPE_THIRD)
                    {
                        foreach ($products_arr as $K => $V)
                        {
                            $newModel=new FundProductThirdproduct();
                            $newModel->thirdproduct_id=$V;
                            $newModel->product_id=$model->id;
                            $newModel->save();
                            $thirModel = Thirdproduct::findOne(['id' => $V]);
                            $thirModel->process_status=1;
                            $thirModel->save(false);
                        }
                    }else{
                        $thirdproduct = new Thirdproduct();
                        $thirdproduct->title = \App::$app->request->post()['Product']['title'];
                        $thirdproduct->intro = \App::$app->request->post()['Product']['intro'];
                        $thirdproduct->source = '本站';
                        $thirdproduct->creditor = \App::$app->request->post()['Product']['ocreditor'];
                        $thirdproduct->contract = $model->contract;
                        $thirdproduct->realname = $model->contract;
                        $thirdproduct->remarks = \App::$app->request->post()['Product']['intro'];
                        $thirdproduct->amount = $model->amount;
                        $thirdproduct->ocmoney = $model->amount;
                        $thirdproduct->start_at = $model->start_at;
                        $thirdproduct->end_at = $model->end_at;
                        $thirdproduct->status = Thirdproduct::STATUS_ACTIVE;
                        $thirdproduct->create_user_id =\App::$app->user->id;
                        $thirdproduct->check_user_id = \App::$app->user->id;
                        $thirdproduct->rate = \App::$app->request->post()['Product']['rate']*0.01;
                        $thirdproduct->process_status= 1;
                        $thirdproduct->intent = Thirdproduct::INTENT_CHECK;
                        $thirdproduct->maxcreditor = \App::$app->request->post()['Product']['maxcreditor'];
                        $thirdproduct->save();
                        $thirdmid = new FundProductThirdproduct();
                        $thirdmid->thirdproduct_id = $thirdproduct->id;
                        $thirdmid->product_id=$model->id;
                        $thirdmid->save();
                    }

                    Product::deleteAll(['id'=>$id]);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
        	} else {
            //var_dump($newthidModel);
            $model = $this->findModel($id);
            $model['start_at'] = date("Y-m-d H:i", $model['start_at']);
            $model['end_at'] = date("Y-m-d H:i", $model['end_at']);
            $model['rate'] = $model['rate']*100;
            return $this->render('update', [
                'model' => $model,
            	'products' => $arraynew,
            ]);
        }
    }

    /**
     * Deletes an existing product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = FundProductThirdproduct::find()->where(['product_id' => $id])->all();
    	foreach ($model as $V)
    	{
    		$thirdproduct_id = $V->thirdproduct_id;
    		$thirdModel = Thirdproduct::find()->where(['id' => $thirdproduct_id])->one();
    		$thirdModel->process_status = 0;
    		$thirdModel->save(false);
    	}
        Product::updateAll(['status'=>Product::STATUS_DELETE],['id' =>$id]);
       // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 读取未进行的项目   2015年7月10日 09:19:45
     * @return multitype:NULL
     */
    protected function getThirProducts() {
    	$models = Thirdproduct::find()
            ->andWhere(['intent'=>Thirdproduct::INTENT_CHECK])
            ->andWhere(['status'=>Thirdproduct::STATUS_ACTIVE])
            ->andWhere(['process_status' => Thirdproduct::PROCESS_STATUS_INACTIVE])
            ->all();
    	return $models;
    }
//////////////////todo
    /**
     * 划分资金
     * @param $total
     * @param $div
     * @return array
     */
    public function randnum($total,$div){
        $total = $total; //待划分的数字
        $div = $div; //分成的份数
        $area = ceil($total/$div); //各份数间允许的最大差值
        $average = round($total / $div);
        $sum = 0;
        $result = array_fill( 1, $div, 0 );

        for( $i = 1; $i < $div; $i++ ){
            //根据已产生的随机数情况，调整新随机数范围，以保证各份间差值在指定范围内
            if( $sum > 0 ){
                $max = 0;
                $min = 0 - round( $area / 2 );
            }elseif( $sum < 0 ){
                $min = 0;
                $max = round( $area / 2 );
            }else{
                $max = round( $area / 2 );
                $min = 0 - round( $area / 2 );
            }

            //产生各份的份额
            $random = rand( $min, $max );
            $sum += $random;
            $result[$i] = $average + $random;
        }

        //最后一份的份额由前面的结果决定，以保证各份的总和为指定值
        $result[$div] = $average - $sum;
        foreach( $result as $temp ){
            $data[]=$temp;
        }
        return $data;
    }

    /**
     * 产生随机姓名
     * @return mixed
     */
    public function getname(){
        $testName = array(
            '伟','芳','娜','敏','静','秀英','丽','强','磊','洋','艳','勇','军','杰','娟','涛','超','明','霞','秀兰','刚','平','燕','辉',
            '玲','桂英','丹','萍','鹏','华','红','玉兰','飞','桂兰','英','梅','鑫','波','斌','莉','宇','浩','凯','秀珍','健','俊','帆',
            '雪','帅','慧','旭','宁','婷','玉梅','龙','林','玉珍','凤英','晶','欢','玉英','颖','红梅','佳','倩','阳','建华','亮','成',
            '琴','兰英','畅','建','云','洁','峰','建国','建军','柳','淑珍','春梅','海燕','晨','冬梅','秀荣','瑞','桂珍','莹','秀云','桂荣',
            '志强','秀梅','丽娟','婷婷','玉华','兵','雷','东','琳','雪梅','淑兰','丽丽','玉','秀芳','欣','淑英','桂芳','博','丽华','丹丹',
            '彬','桂香','坤','想','淑华','荣','秀华','桂芝','岩','杨','小红','金凤','文','利','楠','红霞','建平','瑜','桂花','璐','凤兰'
        );
        $length = count($testName);
        $key = mt_rand(0,$length-1);
        return $testName[$key];
    }

    /**
     * 获取随机手机号
     * @return string
     */
    public function getphone(){
        $testphone = array(
            134, 135, 136, 137, 138, 139, 147, 150, 151, 152, 157, 158, 159, 182, 187, 188, // china mobile
            130, 131, 132, 145, 155, 156, 185, 186, 145, // china unicom
            133 , 153 , 180 , 181 , 189, // chinatelecom
        );
        $chars = "0123456789";
        $str = '';
        for ($i = 0; $i < 4; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        $length = count($testphone);
        $key = mt_rand(0,$length-1);
        return $testphone[$key].'****'.$str;
    }
}
