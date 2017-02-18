<?php


/*
    
    由于只是 demo 就没写的那么复杂
    
    可用 ajax 定时取获取数据然后更新 html 的内容
    
    
    可以用服务器端定时
*/

//修改array 里的参数就可以得到想要的结果
$Order = array(
    'number' =>'1009',
    'type' =>'bid', //买
    'quantity' =>'15',
    'price' =>'500',
    
    /*
    'number' =>'1009',
    'type' =>'ask', //卖
    'quantity' =>'7',
    'price' =>'460',
*/
    
    
);



$list = array(
    
    array(
        'number' =>'1001',
        'type' =>'bid', //买
        'quantity' =>'6',
        'price' =>'470',
    ),
    array(
        'number' =>'1002',
        'type' =>'ask', //卖
        'quantity' =>'8',
        'price' =>'480',
    ),    
    array(
        'number' =>'1004',
        'type' =>'bid',
        'quantity' =>'5',
        'price' =>'460',
    ),
    array(
        'number' =>'1003',
        'type' =>'bid', 
        'quantity' =>'7',
        'price' =>'460',
    ),
    array(
        'number' =>'1005',
        'type' =>'ask',
        'quantity' =>'5',
        'price' =>'490',
    ),
);





usort($list, "multi_compare");  

$result = array();

sss( $list, $Order, $result );

if( $Order['quantity'] > 0 )
{
    $list[] = $Order;
}

usort($list, "multi_compare");  




//计算对比
function sss( &$list, &$Order, &$result )
{

    foreach( $list as $k => &$v )
    {
        
        if( $Order['type'] == 'bid' && $v['type'] == 'ask' )
        {
            if( $Order['price'] >= $v['price'] )
            {
                $s = $Order['quantity'] - $v['quantity'];
                
                $result[$k]['money'] = ($v['price'] + $Order['price'])/2;
                $result[$k]['number'] = min($Order['quantity'], $v['quantity']);
                $result[$k]['list'] = array( $v, $Order );

//                 echo $s;
                if( $s == 0 )
                {
                    unset( $list[$k] );
                    
                    $Order['quantity'] = 0;
                    
                    break;
                }
                else if( $s > 0 )
                {
                    unset( $list[$k] );
                    
                    $Order['quantity'] = $s;
                }                
                else if( $s < 0 )
                {
                    $v['quantity'] = -$s;
                    
                    $Order['quantity'] = $s;

                    break;                    
                }
            }   
        }
        else if( $Order['type'] == 'ask' && $v['type'] == 'bid' )
        {
            if( $Order['price'] <= $v['price'] )
            {
                $s = $v['quantity'] - $Order['quantity'];
                

                $result[$k]['money'] = ($v['price'] + $Order['price'])/2;
                $result[$k]['list'] = array( $v, $Order );
                $result[$k]['number'] = min($Order['quantity'], $v['quantity']);

                                
                if( $s == 0 )
                {
                    unset( $list[$k] );
                    
                    $Order['quantity'] = 0;

                    break;
                }
                else if( $s > 0 )
                {
                    $v['quantity'] = $s;
                    
                    $Order['quantity'] = -$s;

                    break;
                }
                else if( $s < 0 )
                {
                    
                    unset( $list[$k] );
                    
                    $Order['quantity'] = -$s;
                    
                }
            }
        }
    }
}





// list排序
function multi_compare( $a, $b )  
{  
    $criteria = array(  
        'price' =>'desc',  
        'number' =>'asc' 
    );  
    
    foreach( $criteria as $what => $order )
    {  
        if( $a[$what] == $b[$what] )
        {  
            continue;  
        }  
        
        return ( ( $order == 'desc' )? -1: 1 ) * ( ( $a[$what] < $b[$what] )? -1: 1 );  
    }  
    
    return 0;  
}     


include('./api.tpl.html');