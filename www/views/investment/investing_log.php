<?php 
use yii\widgets\LinkPager;
use common\models\base\fund\Product;
use common\models\base\activity\VirtualProduct;
?> 
<link href="<?php echo Yii::$app->homeUrl;?>myAssetsLib/css/font-awesome.min.css" rel="stylesheet"></link>
<link href="<?php echo Yii::$app->homeUrl;?>myAssetsLib/css/zy.css" rel="stylesheet"></link>
<link href="<?php echo Yii::$app->homeUrl;?>myAssetsLib/css/index.css" rel="stylesheet"></link>
<style>
.pagination > li.prev a , .pagination > li.prev span , .pagination > li.next a , .pagination > li.next span{
  text-decoration: none;
}
.pagination > li.prev a i, .pagination > li.prev span i, .pagination > li.next a i, .pagination > li.next span i{
  height: 40px;
  line-height: 40px;
}
</style>  
<div class="xj-touzidiv xj-touzidiv2">
                    <table class="xj-touzitab">
                        <tr>
                            <th>姓名</th>
                            <th>投资金额 </th>
                            <th>投资时间</th>
                        </tr>
                        <?php
                        if($invest_new){
                            foreach ($invest_new as $K=>$V){?>
                        <tr>
                            <td><?php for ($n=0;$n<mb_strlen($V['real_name'],'utf-8')-1;$n++)
                                {echo '* ';} echo mb_substr($V['real_name'], mb_strlen($V['real_name'],'utf-8')-1,1,'utf-8');?></td>
                            <td><?php if(isset($V['start_money'])) echo number_format($V['start_money'],2); ?>元</td>
                    		<td><?php if(isset($V['start_at'])) echo date("Y-m-d",$V['start_at']); ?></td>
                        </tr>

                        <?php }}?>
                    </table>

                    <div class="page-nember3">
                    <?php if($invest_new){
		            	echo LinkPager::widget([
		            			'pagination' => $pages_new,
		            			'maxButtonCount' => 0,
		            			//'options' =>['class' =>'perv-next'],
		            			//'prevPageLabel' =>'&lt',
		            			//'nextPageLabel' =>'&gt',
		            			'prevPageLabel' =>'',
		            			'nextPageLabel' =>'',
		            	]);
		           	  }?>

		           	</div>            
</div>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
    $(document).ready(function(){
        $(".xj-touzitab tr:odd").addClass("tzOdd"); 
        $(".page-nember3 .pagination > li.prev span").html("<i class='icon-caret-left'></i>");
        $(".page-nember3 .pagination > li.prev a").html("<i class='icon-caret-left'></i>");
        $(".page-nember3 .pagination > li.next span").html("<i class='icon-caret-right'></i>");
        $(".page-nember3 .pagination > li.next a").html("<i class='icon-caret-right'></i>");
        
    });
</script>