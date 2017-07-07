<?php

class JSONModel
{


    public function get_piechart_graph_json($titleArray, $dataArray)
    {

        $response = array();

        $response['credits'] = array('enabled' => 0);

        $response['exporting'] = array('enabled' => false);

        $response['chart'] = array('type' => 'pie', 'option3d' => array('enabled' => true, 'alpha' => 45, 'beta' => 0));

        $response['title'] = $titleArray;

        $response['tooltip'] = array("pointFormat" => '{series.name}: <b>{point.percentage:.1f}%</b>');

        $response['plotOptions'] = array(
            "pie" => array(
                "allowPointSelect" => true,
                'cursor' => 'pointer',
                'depth' => 30,
                'dataLabels' => array(
                    'enabled' => true,
                    'format' => '{point.name}'
                )));

        $response['series'] = array();
        array_push($response['series'], $dataArray);


        return $response;
    }

    public function get_donutchart_graph_json($titleArray, $dataArray)
    {

        $response = array();

        $response['credits'] = array('enabled' => 0);

        $response['exporting'] = array('enabled' => false);

        $response['chart'] = array('type' => 'pie', 'option3d' => array('enabled' => true, 'alpha' => 45, 'beta' => 0));
        $response['plotOptions'] = array('pie' => array('innerSize' => 70, 'depth' => 45));

        $response['title'] = $titleArray;

        $response['tooltip'] = array("pointFormat" => '{series.name}: <b>{point.percentage:.1f}%</b>');

        $response['series'] = array();
        $response['total'] = array('total'=>112);
        array_push($response['series'], $dataArray);

        return $response;
    }


    public function get_stat_bar_graph_json2($rows, $titleArray, $label_x, $key)
    {
        $response['credits'] = array('enabled' => 0);

        $response['exporting'] = array('enabled' => false);

        $response['chart'] = array('type' => 'column');

        $response['title'] = $titleArray;

        $response['yAxis'] = array();
        array_push($response['yAxis'], array('min' => 0, 'title' => array("text" => 'Farmers')));

        $response['tooltip'] = array("headerFormat" => '<span style="font-size:10px">{point.key}</span><table>',
            "pointFormat" => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.0f} Farmer(s)</b></td></tr>', "footerFormat" => '</table>', "shared" => true,
            "useHTML" => true);

        $response['plotOptions'] = array(
            "column" => array(
                "pointPadding" => 0.2,
                'borderWidth' => 0
            ));
        $temp["x"] = array();
        $temp["y"] = array();
        foreach ($rows as $row) {

            if ((double)$row["frequency"] != 0) {
                array_push($temp["x"], (double)$row["frequency"]);
                array_push($temp["y"], ucfirst(strtolower($row[$key])));
            }
        }
        $response['xAxis'] = array('categories' => $temp["y"], "crosshair" => true);

        $response['series'] = array();
        array_push($response['series'], array("name" => $label_x, "data" => $temp["x"]));

        return $response;
    }

    public function get_column_graph($district, $no_farmers, $youth, $old, $type)
    {
        $json['credits'] = array('enabled' => 0);

        $json['exporting'] = array('enabled' => false);

        $json['chart'] = array('type' => 'column');

        $json['title'] = array('text'=>'Number of farmers in districs');

        $json['yAxis'] = array('MIN' => 0, 'title' => array('text' => 'Farmers'));

        $json['tooltip'] = array("headerFormat" => '<span style="font-size:10px">{point.key}</span><table>',
            "pointFormat" => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} Farmer(s)</b></td></tr>',
            "footerFormat" => '</table>', "shared" => true,
            "useHTML" => true);

        $json['plotOptions'] = array(
            "column" => array(
                "pointPadding" => 0.2,
                'borderWidth' => 0
            ));
        $json['xAxis'] = array('categories' => $district, 'crosshair' => true);

        $json['series'] = array(array('name' => 'Total farmers', 'data' => $no_farmers),
            array('name' => 'Youth', 'data' => $youth),
            array('name' => 'Old', 'data' => $old)
        );
        return $json;
    }

    public function get_stat_district_json($rows)
    {
        $response['data'] = array();

        foreach ($rows as $row) {

            $data = array();
            $data["x"] = $row["district"];
            $data["y"] = (double)$row["frequency"];

            array_push($response['data'], $data);
        }

        return $response;
    }


    public function get_stat_county_json($rows)
    {
        $response['data'] = array();
        //$response["element"] = 'line-location';
        foreach ($rows as $row) {

            $data = array();
            $data["x"] = $row["subcounty"];
            $data["y"] = (double)$row["frequency"];

            array_push($response['data'], $data);
        }

        return $response;
    }

    public function get_stat_parish_json($rows)
    {
        $response['data'] = array();
        //$response["element"] = 'line-location';
        foreach ($rows as $row) {

            $data = array();
            $data["x"] = $row["parish"];
            $data["y"] = $row["frequency"];

            array_push($response['data'], $data);
        }
        return $response;
    }

    public function get_stat_village_json($rows)
    {
        $response['data'] = array();
        foreach ($rows as $row) {

            $data = array();
            $data["x"] = $row["village"];
            $data["y"] = $row["frequency"];

            array_push($response['data'], $data);
        }
        return $response;
    }


    public function bar_graph_json2($rows, $label_x, $key)
    {

        $response['credits'] = array('enabled' => 0);

        $response['exporting'] = array('enabled' => false);

        $response['chart'] = array('type' => 'line');

        $response['title'] = array('text'=>'graph of farmers');

        $response['yAxis'] = array();
        array_push($response['yAxis'], array('min' => 0, 'title' => array("text" => 'Farmers')));

//        $response['tooltip'] = array("valueSufix"=>'millions');

        $response['plotOptions'] = array(
            "column" => array(
                "pointPadding" => 0.2,
                'borderWidth' => 0
            ));
        $temp["x"] = array();
        $temp["y"] = array();
        foreach ($rows as $row) {

            if ((double)$row["frequency"] != 0) {
                array_push($temp["x"], (double)$row["frequency"]);
                array_push($temp["y"], ucfirst(strtolower($row[$key])));
            }

        }

        $response['xAxis'] = array('categories' => $temp["y"], "crosshair" => true);

        $response['series'] = array();
        array_push($response['series'], array("name" => $label_x, "data" => $temp["x"]));


        return $response;
    }

    public function drawSeedGraph($district, $no_farmers, $type)
    {
        $json['chart'] = array('type' => $type);

        $json['title'] = array('text'=>'Farmers and their source of seeds');


        $json['xAxis'] = array('categories' => $district, 'crosshair' => true);

        $json['yAxis'] = array('MIN' => 0, 'title' => array('text' => 'Farmers'));

        $json['credits'] = array('enabled' => false);

        $json['exporting'] = array('enabled' => false);

        $json['tooltip'] = array("headerFormat" => '<span style="font-size:10px">{point.key}</span><table>',
            "pointFormat" => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} Farmer(s)</b></td></tr>',
            "footerFormat" => '</table>', "shared" => true,
            "useHTML" => true);

        $json['plotOptions'] = array(
            "column" => array(
                "pointPadding" => 0.2,
                'borderWidth' => 0
            ));
        $json['series'] = array(array('name' => 'Number of farmers', 'data' => $no_farmers));
        return $json;
    }

    function drawActionGraph($district, $array_int, $no_action_int, $type)
    {
        $json['chart'] = array('type' => $type);

        $json['title'] = array('text'=>'Farmers who applied climate change action');


        $json['xAxis'] = array('categories' => $district);

        $json['yAxis'] = array('MIN' => 0, 'title' => array('text' => 'Farmers'));

        $json['credits'] = array('enabled' => false);

        $json['exporting'] = array('enabled' => false);

        $json['tooltip'] = array("headerFormat" => '<span style="font-size:10px">{point.key}</span><table>',
            "pointFormat" => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} Farmer(s)</b></td></tr>', "footerFormat" => '</table>', "shared" => true,
            "useHTML" => true);

        $json['plotOptions'] = array(
            "column" => array(
                "pointPadding" => 0.2,
                'borderWidth' => 0
            ));

        $json['series'] = array(array('name' => 'Used action', 'data' => $array_int),
            array('name' => 'No action', 'data' => $no_action_int)
        );
        return $json;
    }

    function getRegions($type, $categories, $farmers, $males_int, $females_int)
    {
        $json['chart'] = array('type' => $type);

        $json['title'] = array('text'=>'Farmers gender distribution in different regions');


        $json['xAxis'] = array('categories' => $categories, 'crosshair' => true);

        $json['yAxis'] = array('MIN' => 0, 'title' => array('text' => 'Farmers'));

        $json['credits'] = array('enabled' => false);

        $json['exporting'] = array('enabled' => false);

        $json['tooltip'] = array("headerFormat" => '<span style="font-size:10px">{point.key}</span><table>',
            "pointFormat" => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} Farmer(s)</b></td></tr>', "footerFormat" => '</table>', "shared" => true,
            "useHTML" => true);

        $json['plotOptions'] = array(
            "column" => array(
                "pointPadding" => 0.2,
                'borderWidth' => 0
            ));
        $json['series'] = array(array('name' => 'All', 'data' => $farmers),
            array('name' => 'Male', 'data' => $males_int),
            array('name' => 'Female', 'data' => $females_int)
        );
        return $json;
    }


}

?>