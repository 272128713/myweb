<?php
/**
 * User: 王秀泽
 * Date: 2015/11/15
 * Time: 17:47
 * Last: is_point_in_polygon 判断一个坐标是否在一个多边形内 2015-11-15 17:51:20
 */


/**
 * 判断一个坐标是否在一个多边形内（由多个坐标围成的）
 * 基本思想是利用射线法，计算射线与多边形各边的交点，如果是偶数，则点在多边形外，否则
 * 在多边形内。还会考虑一些特殊情况，如点在多边形顶点上，点在多边形边上等特殊情况。
 * @param $point 指定点坐标
 * @param $pts 多边形坐标 顺时针方向
 * @return 判断一个坐标是否在一个多边形内
 */
function is_point_in_polygon($point, $pts)
{
    $N = count($pts);
    $boundOrVertex = true; //如果点位于多边形的顶点或边上，也算做点在多边形内，直接返回true
    $intersectCount = 0;//cross points count of x 
    $precision = 2e-10; //浮点类型计算时候与0比较时候的容差
    $p1 = 0;//neighbour bound vertices
    $p2 = 0;
    $p = $point; //测试点

    $p1 = $pts[0];//left vertex        
    for ($i = 1; $i <= $N; ++$i) {//check all rays
        // dump($p1);
        if ($p['longitude'] == $p1['longitude'] && $p['latitude'] == $p1['latitude']) {
            return $boundOrVertex;//p is an vertex
        }

        $p2 = $pts[$i % $N];//right vertex            
        if ($p['latitude'] < min($p1['latitude'], $p2['latitude']) || $p['latitude'] > max($p1['latitude'], $p2['latitude'])) {//ray is outside of our interests
            $p1 = $p2;
            continue;//next ray left point
        }

        if ($p['latitude'] > min($p1['latitude'], $p2['latitude']) && $p['latitude'] < max($p1['latitude'], $p2['latitude'])) {//ray is crossing over by the algorithm (common part of)
            if ($p['longitude'] <= max($p1['longitude'], $p2['longitude'])) {//x is before of ray
                if ($p1['latitude'] == $p2['latitude'] && $p['longitude'] >= min($p1['longitude'], $p2['longitude'])) {//overlies on a horizontal ray
                    return $boundOrVertex;
                }

                if ($p1['longitude'] == $p2['longitude']) {//ray is vertical                        
                    if ($p1['longitude'] == $p['longitude']) {//overlies on a vertical ray
                        return $boundOrVertex;
                    } else {//before ray
                        ++$intersectCount;
                    }
                } else {//cross point on the left side
                    $xinters = ($p['latitude'] - $p1['latitude']) * ($p2['longitude'] - $p1['longitude']) / ($p2['latitude'] - $p1['latitude']) + $p1['longitude'];//cross point of longitude
                    if (abs($p['longitude'] - $xinters) < $precision) {//overlies on a ray
                        return $boundOrVertex;
                    }

                    if ($p['longitude'] < $xinters) {//before ray
                        ++$intersectCount;
                    }
                }
            }
        } else {//special case when ray is crossing through the vertex
            if ($p['latitude'] == $p2['latitude'] && $p['longitude'] <= $p2['longitude']) {//p crossing over p2
                $p3 = $pts[($i + 1) % $N]; //next vertex
                if ($p['latitude'] >= min($p1['latitude'], $p3['latitude']) && $p['latitude'] <= max($p1['latitude'], $p3['latitude'])) { //p.latitude lies between p1.latitude & p3.latitude
                    ++$intersectCount;
                } else {
                    $intersectCount += 2;
                }
            }
        }
        $p1 = $p2;//next ray left point
    }

    if ($intersectCount % 2 == 0) {//偶数在多边形外
        return false;
    } else { //奇数在多边形内
        return true;
    }
}