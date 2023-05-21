<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CommentController extends Controller
{
    //

    public function __construct()
    {
        date_default_timezone_set(env('TIME_ZONE'));
        $this->today = date('Y-m-d H:i:s', time());


    }

    public function saveComment(Request $request)
    {

        $check=DB::table("comments")
        ->insert(
            [
            "author_name"=>$request->input("name"),
            "author_email"=>$request->input("email"),
            "comment"=>$request->input("comment"),
            "created_at"=>$this->today
            ]
            );

            if($check)
            {
             return response([

                 "status"=>true,
                 "result"=>$check,

             ]);
            }
            else{
             return response([

                 "status"=>false,
                 "result"=>$check,

             ]);
            }


    }
    public function readComment(Request $request){



$startFrom=$request->input("startFrom")??0; // Startfrom limit
$dataLimit=$request->input("dataLimit")??10; // data limit to be shown



   $check=DB::select('select *from comments order by id limit :limit offset :offset',array('limit'=>$dataLimit,'offset'=>$startFrom));

   if($check)
   {
    return response([

        "status"=>true,
        "result"=>$check,

    ]);
   }
   else{
    return response([

        "status"=>false,
        "result"=>$check,

    ]);
   }



    }


    public function searchComment(Request $request){//by name or by email


        $searchInput=$request->input('search');
        $searchInput = filter_var($searchInput, FILTER_SANITIZE_STRING);




           $check=DB::select("select *from comments where author_name Like '%$searchInput%' or author_email Like '%$searchInput%' limit 10");

               if($check)
               {
                return response([

                    "status"=>true,
                    "result"=>$check,

                ]);
               }
               else{
                return response([

                    "status"=>false,
                    "result"=>$check,

                ]);
               }



            }

}
