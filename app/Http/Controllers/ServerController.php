<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Ec2\Ec2Client;
use Atymic\Twitter\Facade\Twitter;
use File;
use Illuminate\Support\Str;


class ServerController extends Controller
{
    public $region = "us-east-1";
    public $version = "2016-11-15";
    public $key = "************";
    public $secret = "************";
    public $instance = array('************');


    public function getServerIp()
    {
        $ec2Client = new Ec2Client([
            'region' => $this->region,
            'version' => $this->version,
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
          ]]);

        $result = $ec2Client->describeInstances();

        foreach ($result['Reservations'] as $reservation) {
            foreach ($reservation['Instances'] as $instance) {
                if ($instance['State']['Name'] == 'running') {
                    $dns = $instance['PublicDnsName'];
                    $ip = $instance['PublicIpAddress'];
                }else {
                    return response()->json([
                        "data" => "Instance is Stoped"
                    ]);
                }
            }
        }

        return redirect('https://btubulutbilisim.herokuapp.com/api/getServerIpFromServer/'.$ip);
    }



    public function getTweetsFromClient(Request $request)
    {
        $post = $request->tweets;
        
        // $data = json_encode($post);
        $data = $post;

        $file = 'tweets.json';
        $destinationPath = public_path()."/tweets/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);

        return response()->json([
            'statu' => 'success'
        ]);
    }



    public function showTweets()
    {
        $url = public_path().'/tweets/tweets.json';
        $response = file_get_contents($url);

        $tweets = array();

        $da = json_decode($response);

        foreach ($da as $key) {
            array_push($tweets,
                    [
                        'tweetId' => $key->tweetId,
                        'text' => $key->text
                    ]
                );
        }

        return view('welcome')->with('tweets', $tweets);
    }


    public function getTweetsFromServer()
    {
        $url = public_path().'/tweets/tweets.json';
        $response = file_get_contents($url);

        $tweets = array();

        $da = json_decode($response);

        foreach ($da as $key) {
            array_push($tweets,
                    [
                        'tweetId' => $key->tweetId,
                        'text' => $key->text
                    ]
                );
        }
        
        return $tweets;
    }











}
